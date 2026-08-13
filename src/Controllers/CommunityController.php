<?php
declare(strict_types=1);

namespace Baka\Controllers;

use Baka\Content;
use Baka\Db;
use Baka\View;

final class CommunityController
{
    // ---------- Guestbook ----------
    public function guestbook(): string
    {
        $entries = Db::pdo()
            ->query('SELECT * FROM guestbook ORDER BY id DESC LIMIT 100')
            ->fetchAll();

        return View::render('pages/guestbook', [
            'title'      => 'Baka News — Guest Book',
            'entries'    => $entries,
            'categories' => Content::categories(),
        ]);
    }

    public function signGuestbook(): void
    {
        if (!csrf_check()) {
            redirect('/guestbook');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $msg  = trim((string) ($_POST['message'] ?? ''));
        $mood = trim((string) ($_POST['mood'] ?? 'Cheerful'));
        $home = trim((string) ($_POST['homepage'] ?? ''));

        if ($name !== '' && $msg !== '') {
            $stmt = Db::pdo()->prepare(
                'INSERT INTO guestbook (name, message, mood, homepage) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                mb_substr($name, 0, 40),
                mb_substr($msg, 0, 500),
                mb_substr($mood, 0, 16) ?: 'Cheerful',
                filter_var($home, FILTER_VALIDATE_URL) ? $home : null,
            ]);
        }
        redirect('/guestbook');
    }

    // ---------- Webring ----------
    public function webring(): string
    {
        $members = Db::pdo()
            ->query('SELECT * FROM webring WHERE approved = 1 ORDER BY id ASC')
            ->fetchAll();

        return View::render('pages/webring', [
            'title'      => 'Baka News — The Baka Ring',
            'members'    => $members,
            'categories' => Content::categories(),
        ]);
    }

    public function webringNext(): void { $this->ringHop(+1); }
    public function webringPrev(): void { $this->ringHop(-1); }

    public function webringRandom(): void
    {
        $row = Db::pdo()
            ->query('SELECT url FROM webring WHERE approved = 1 ORDER BY RANDOM() LIMIT 1')
            ->fetch();
        redirect($row['url'] ?? '/webring');
    }

    private function ringHop(int $dir): void
    {
        $members = Db::pdo()
            ->query('SELECT url FROM webring WHERE approved = 1 ORDER BY id ASC')
            ->fetchAll(\PDO::FETCH_COLUMN);
        if (!$members) {
            redirect('/webring');
        }
        $from = $_GET['from'] ?? '/';
        $idx = array_search($from, $members, true);
        $idx = $idx === false ? 0 : ($idx + $dir + count($members)) % count($members);
        redirect($members[$idx]);
    }

    public function webringJoin(): void
    {
        if (!csrf_check()) {
            redirect('/webring');
        }
        $name = trim((string) ($_POST['site_name'] ?? ''));
        $ur   = trim((string) ($_POST['url'] ?? ''));
        $desc = trim((string) ($_POST['description'] ?? ''));

        if ($name !== '' && filter_var($ur, FILTER_VALIDATE_URL)) {
            $stmt = Db::pdo()->prepare(
                'INSERT INTO webring (site_name, url, description) VALUES (?, ?, ?)'
            );
            $stmt->execute([mb_substr($name, 0, 60), $ur, mb_substr($desc, 0, 140)]);
        }
        redirect('/webring');
    }

    // ---------- User page directory ----------
    public function submitPage(): string
    {
        $pages = Db::pdo()
            ->query('SELECT * FROM user_pages WHERE approved = 1 ORDER BY id DESC LIMIT 100')
            ->fetchAll();

        return View::render('pages/user-pages', [
            'title'      => 'Baka News — The People\'s Homepages',
            'pages'      => $pages,
            'categories' => Content::categories(),
        ]);
    }

    public function storePage(): void
    {
        if (!csrf_check()) {
            redirect('/submit-page');
        }
        $title = trim((string) ($_POST['title'] ?? ''));
        $ur    = trim((string) ($_POST['url'] ?? ''));
        $blurb = trim((string) ($_POST['blurb'] ?? ''));
        $badge = trim((string) ($_POST['badge'] ?? 'WWW'));

        if ($title !== '' && filter_var($ur, FILTER_VALIDATE_URL)) {
            $stmt = Db::pdo()->prepare(
                'INSERT INTO user_pages (title, url, blurb, badge) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                mb_substr($title, 0, 60),
                $ur,
                mb_substr($blurb, 0, 160),
                mb_substr($badge, 0, 4) ?: 'WWW',
            ]);
        }
        redirect('/submit-page');
    }

    // ---------- Visitor counter (JSON, called by JS once per session) ----------
    public function counterJson(): void
    {
        if (empty($_SESSION['counted'])) {
            $_SESSION['counted'] = true;
            $value = Db::bumpVisits();
        } else {
            $value = Db::visits();
        }
        json_out(['visits' => $value]);
    }
}
