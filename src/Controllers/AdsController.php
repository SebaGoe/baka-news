<?php
declare(strict_types=1);

namespace Baka\Controllers;

use Baka\Content;
use Baka\Db;
use Baka\View;

final class AdsController
{
    public function index(): string
    {
        $submitted = Db::pdo()
            ->query('SELECT * FROM submitted_ads WHERE approved = 1 ORDER BY id DESC LIMIT 50')
            ->fetchAll();

        return View::render('pages/ads', [
            'title'      => 'Baka News — Advertising Emporium',
            'ads'        => Content::ads(),
            'submitted'  => $submitted,
            'categories' => Content::categories(),
        ]);
    }

    public function form(): string
    {
        return View::render('pages/ads-submit', [
            'title'      => 'Baka News — Advertise Your Fake Business',
            'categories' => Content::categories(),
        ]);
    }

    public function store(): void
    {
        if (!csrf_check()) {
            redirect('/ads/submit');
        }
        $title = trim((string) ($_POST['title'] ?? ''));
        $body  = trim((string) ($_POST['body'] ?? ''));

        if ($title === '' || $body === '') {
            $_SESSION['_old'] = $_POST;
            redirect('/ads/submit');
        }

        $stmt = Db::pdo()->prepare(
            'INSERT INTO submitted_ads (title, body) VALUES (?, ?)'
        );
        $stmt->execute([
            mb_substr($title, 0, 60),
            mb_substr($body, 0, 200),
        ]);

        unset($_SESSION['_old']);
        redirect('/ads');
    }
}
