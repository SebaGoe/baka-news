<?php
declare(strict_types=1);

namespace Baka\Controllers;

use Baka\Content;
use Baka\Db;
use Baka\View;

final class PageController
{
    public function arcade(): string
    {
        return View::render('pages/arcade', [
            'title'      => 'Baka News — The Arcade',
            'categories' => Content::categories(),
        ]);
    }

    public function about(): string
    {
        return View::render('pages/about', [
            'title'      => 'Baka News — About (Legally Distinct From Truth)',
            'categories' => Content::categories(),
        ]);
    }

    public function construction(): string
    {
        return View::render('pages/construction', [
            'title'      => 'Baka News — Under Construction',
            'categories' => Content::categories(),
        ]);
    }

    /** "I'm feeling lucky" — jump to a random fun corner of our own site. */
    public function randomPage(): void
    {
        $pages = [
            '/construction', '/construction', // weighted: the 90s classic
            '/arcade', '/coupons', '/guestbook', '/webring',
            '/horoscope', '/classifieds', '/ads', '/about',
        ];
        // Sometimes surprise with a random story in the active edition.
        if (current_mode() === 'real') {
            $s = \Baka\RealNews::randomItem();
            if ($s) { $pages[] = '/real/story/' . $s['id']; }
        } else {
            $a = Content::randomArticle();
            if ($a) { $pages[] = '/article/' . $a['id']; }
        }
        redirect($pages[array_rand($pages)]);
    }

    // ---------- Horoscopes ----------
    public function horoscope(): string
    {
        return View::render('pages/horoscope', [
            'title'      => 'Baka News — Baka-scopes',
            'signs'      => Content::horoscopes(),
            'today'      => Content::horoscopeOfTheDay(),
            'categories' => Content::categories(),
        ]);
    }

    public function horoscopeSign(array $params): string
    {
        $sign = Content::horoscope($params['sign'] ?? '');
        if (!$sign) {
            http_response_code(404);
            return View::render('pages/404', ['title' => '404 — Sign Not In The Stars']);
        }
        return View::render('pages/horoscope', [
            'title'      => 'Baka-scope — ' . $sign['name_en'],
            'signs'      => Content::horoscopes(),
            'today'      => $sign,
            'focus'      => $sign,
            'categories' => Content::categories(),
        ]);
    }

    // ---------- Classifieds ----------
    public function classifieds(): string
    {
        return View::render('pages/classifieds', [
            'title'      => 'Baka News — Classifieds',
            'ads'        => Db::classifieds(),
            'categories' => Content::categories(),
        ]);
    }

    public function storeClassified(): void
    {
        if (!csrf_check()) {
            redirect('/classifieds');
        }
        $section = (string) ($_POST['section'] ?? 'wanted');
        $allowed = ['wanted', 'for-sale', 'lost-found', 'services'];
        $title = trim((string) ($_POST['title'] ?? ''));
        $body  = trim((string) ($_POST['body'] ?? ''));
        $contact = trim((string) ($_POST['contact'] ?? ''));

        if ($title !== '' && $body !== '') {
            $stmt = Db::pdo()->prepare(
                'INSERT INTO classifieds (section, title, body, contact) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                in_array($section, $allowed, true) ? $section : 'wanted',
                mb_substr($title, 0, 70),
                mb_substr($body, 0, 300),
                mb_substr($contact, 0, 80) ?: null,
            ]);
        }
        redirect('/classifieds');
    }

    // ---------- Poll voting (JSON) ----------
    public function votePoll(): void
    {
        $optionId = (int) ($_POST['option'] ?? 0);
        $poll = Db::activePoll();
        $valid = $poll && in_array($optionId, array_column($poll['options'], 'id'), true);

        if (!$valid) {
            json_out(['ok' => false, 'error' => 'No such option'], 400);
        }
        if (!empty($_SESSION['voted_poll_' . $poll['id']])) {
            json_out(['ok' => false, 'error' => 'already-voted', 'poll' => Db::activePoll()], 200);
        }
        Db::votePoll($optionId);
        $_SESSION['voted_poll_' . $poll['id']] = true;
        json_out(['ok' => true, 'poll' => Db::activePoll()]);
    }

    // ---------- RSS feed ----------
    public function rss(): void
    {
        header('Content-Type: application/rss+xml; charset=utf-8');
        $base = rtrim(getenv('BAKA_BASE_URL') ?: '', '/');
        $items = '';
        foreach (array_slice(Content::articles(), 0, 20) as $a) {
            $link = $base . '/article/' . $a['id'];
            $title = htmlspecialchars(t($a['headline'], 'en'), ENT_XML1);
            $desc  = htmlspecialchars(t($a['dek'], 'en'), ENT_XML1);
            $date  = date(DATE_RSS, strtotime(($a['date'] ?? date('Y-m-d')) . ' 09:00'));
            $items .= "    <item>\n"
                . "      <title>{$title}</title>\n"
                . "      <link>{$link}</link>\n"
                . "      <guid>{$link}</guid>\n"
                . "      <description>{$desc}</description>\n"
                . "      <pubDate>{$date}</pubDate>\n"
                . "    </item>\n";
        }
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . "<rss version=\"2.0\"><channel>\n"
            . "  <title>Baka News</title>\n"
            . "  <link>{$base}/</link>\n"
            . "  <description>100% fake, 100% cute. The world's least reliable newspaper.</description>\n"
            . $items
            . "</channel></rss>\n";
        exit;
    }
}
