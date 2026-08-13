<?php
declare(strict_types=1);

namespace Baka\Controllers;

use Baka\Content;
use Baka\Db;
use Baka\View;

final class NewsController
{
    /** Search across all articles + languages. */
    public function search(): string
    {
        $q = (string) ($_GET['q'] ?? '');
        return View::render('pages/search', [
            'title'      => $q !== '' ? 'Search: ' . $q . ' — Baka News' : 'Baka News — Search',
            'query'      => $q,
            'results'    => Content::search($q),
            'categories' => Content::categories(),
        ]);
    }

    /** "Surprise me" — bounce to a random article. */
    public function random(): void
    {
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
