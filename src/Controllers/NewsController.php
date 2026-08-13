<?php
declare(strict_types=1);

namespace Baka\Controllers;

use Baka\Content;
use Baka\Db;
use Baka\View;

final class NewsController
{
    /** Search across all articles + languages (or the real feed in Real mode). */
    public function search(): string
    {
        $q = (string) ($_GET['q'] ?? '');
        if (current_mode() === 'real') {
            if ($q !== '' && \Baka\RealNews::needsRefresh()) {
                @\Baka\RealNews::refresh();
            }
            return View::render('pages/real-search', [
                'title'      => $q !== '' ? 'Real search: ' . $q . ' — Baka News' : 'Baka News — Real Search',
                'query'      => $q,
                'stories'    => $q !== '' ? \Baka\RealNews::search($q) : [],
                'categories' => Content::categories(),
            ]);
        }
        return View::render('pages/search', [
            'title'      => $q !== '' ? 'Search: ' . $q . ' — Baka News' : 'Baka News — Search',
            'query'      => $q,
            'results'    => Content::search($q),
            'categories' => Content::categories(),
        ]);
    }

    /** A single real weird-news story detail page. */
    public function realArticle(array $params): string
    {
        if (\Baka\RealNews::needsRefresh()) {
            @\Baka\RealNews::refresh();
        }
        $story = \Baka\RealNews::item($params['id'] ?? '');
        if (!$story) {
            http_response_code(404);
            return View::render('pages/404', ['title' => '404 — Story Wandered Off']);
        }
        return View::render('pages/real-article', [
            'title'      => $story['title'] . ' — Baka News (Real)',
            'story'      => $story,
            'related'    => \Baka\RealNews::related($story),
            'categories' => Content::categories(),
        ]);
    }

    /** Cheap health check for uptime pingers (keeps a free host awake). */
    public function health(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo "ok\n";
        exit;
    }

    /** Refresh the real-news snapshot (only refetches when past its TTL). */
    public function refreshReal(): void
    {
        $refreshed = false;
        if (\Baka\RealNews::needsRefresh()) {
            \Baka\RealNews::refresh();
            $refreshed = true;
        }
        json_out([
            'ok'          => true,
            'refreshed'   => $refreshed,
            'items'       => count(\Baka\RealNews::items(500)),
            'sources'     => \Baka\RealNews::sourceCount(),
        ]);
    }

    /** JSON rounds for the "Real or Baka?" guessing game: mixed real + fake headlines. */
    public function gameMix(): void
    {
        if (\Baka\RealNews::needsRefresh()) {
            @\Baka\RealNews::refresh();
        }
        $real = \Baka\RealNews::items(500);
        $fake = Content::articles();
        shuffle($real);
        shuffle($fake);
        $n = 8;
        $rounds = [];
        foreach (array_slice($real, 0, $n) as $r) {
            $rounds[] = [
                't'    => $r['title'],
                'real' => true,
                'meta' => ($r['source'] ?? 'Real news') . ' — ' . ($r['domain'] ?? ''),
                'url'  => '/real/story/' . $r['id'],
            ];
        }
        foreach (array_slice($fake, 0, $n) as $a) {
            $rounds[] = [
                't'    => t($a['headline'], 'en'),
                'real' => false,
                'meta' => 'Baka News (invented in-house)',
                'url'  => '/article/' . $a['id'],
            ];
        }
        shuffle($rounds);
        json_out(['rounds' => $rounds]);
    }

    /** "Surprise me" — bounce to a random story in the active edition. */
    public function random(): void
    {
        if (current_mode() === 'real') {
            if (\Baka\RealNews::needsRefresh()) {
                @\Baka\RealNews::refresh();
            }
            $s = \Baka\RealNews::randomItem();
            redirect($s ? '/real/story/' . $s['id'] : '/');
        }
        $a = Content::randomArticle();
        redirect($a ? '/article/' . $a['id'] : '/');
    }

    /** POST /article/react {id, emoji} -> JSON updated counts. */
    public function react(): void
    {
        $id = (string) ($_POST['id'] ?? '');
        $emoji = (string) ($_POST['emoji'] ?? '');
        $allowed = ['ha', 'whoa', 'hmm', 'oof', 'boo'];
        if (!Content::article($id) || !in_array($emoji, $allowed, true)) {
            json_out(['ok' => false], 400);
        }
        Db::react($id, $emoji);
        json_out(['ok' => true, 'counts' => Db::reactionsFor($id)]);
    }

    private function reactionsView(string $id): array
    {
        return Db::reactionsFor($id);
    }

    public function landing(): string
    {
        if (current_mode() === 'real') {
            // Keep the served snapshot warm; first visit after the TTL refreshes it.
            if (\Baka\RealNews::needsRefresh()) {
                @\Baka\RealNews::refresh();
            }
            return View::render('pages/real-landing', [
                'title'      => 'Baka News — Real Edition (Genuinely True, Genuinely Absurd)',
                'categories' => Content::categories(),
                'stories'    => \Baka\RealNews::items(40),
                'sources'    => \Baka\RealNews::sourceCount(),
            ]);
        }
        return View::render('pages/landing', [
            'title'       => 'Baka News — All the News That Never Happened',
            'categories'  => Content::categories(),
            'articles'    => Content::articles(),
            'activeCat'   => null,
        ]);
    }

    public function category(array $params): string
    {
        $slug = $params['slug'] ?? '';
        $cat = Content::category($slug);
        if (!$cat) {
            http_response_code(404);
            return View::render('pages/404', ['title' => '404 — Category Vanished']);
        }

        // In the Real edition, filter the real weird-news feed by this section.
        if (current_mode() === 'real') {
            if (\Baka\RealNews::needsRefresh()) {
                @\Baka\RealNews::refresh();
            }
            $stories = array_values(array_filter(
                \Baka\RealNews::items(500),
                fn($s) => ($s['category'] ?? '') === $slug
            ));
            return View::render('pages/real-landing', [
                'title'      => 'Baka News — Real: ' . ($cat['name_en'] ?? $slug),
                'categories' => Content::categories(),
                'stories'    => $stories,
                'sources'    => \Baka\RealNews::sourceCount(),
                'activeCat'  => $cat,
            ]);
        }

        return View::render('pages/landing', [
            'title'      => 'Baka News — ' . ($cat['name_en'] ?? $slug),
            'categories' => Content::categories(),
            'articles'   => Content::articlesByCategory($slug),
            'activeCat'  => $cat,
        ]);
    }

    public function article(array $params): string
    {
        $article = Content::article($params['id'] ?? '');
        if (!$article) {
            http_response_code(404);
            return View::render('pages/404', ['title' => '404 — Article Escaped']);
        }
        return View::render('pages/article', [
            'title'      => t($article['headline']),
            'article'    => $article,
            'related'    => Content::related($article),
            'reactions'  => Db::reactionsFor($article['id']),
            'categories' => Content::categories(),
        ]);
    }
}
