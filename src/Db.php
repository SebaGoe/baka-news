<?php
declare(strict_types=1);

namespace Baka;

use PDO;

/**
 * SQLite wrapper for DYNAMIC data only (guestbook, visitor counter,
 * webring members, user page submissions, submitted fake ads).
 * Static fake content lives in JSON via Content.php.
 */
final class Db
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO('sqlite:' . BAKA_DB_PATH, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$pdo->exec('PRAGMA journal_mode = WAL;');
        }
        return self::$pdo;
    }

    public static function init(): void
    {
        $db = self::pdo();
        $db->exec(<<<'SQL'
            CREATE TABLE IF NOT EXISTS guestbook (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                message TEXT NOT NULL,
                mood TEXT DEFAULT 'Cheerful',
                homepage TEXT,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
            CREATE TABLE IF NOT EXISTS counters (
                key TEXT PRIMARY KEY,
                value INTEGER NOT NULL DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS webring (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                site_name TEXT NOT NULL,
                url TEXT NOT NULL,
                description TEXT,
                approved INTEGER NOT NULL DEFAULT 1,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
            CREATE TABLE IF NOT EXISTS user_pages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                url TEXT NOT NULL,
                blurb TEXT,
                badge TEXT DEFAULT 'WWW',
                approved INTEGER NOT NULL DEFAULT 1,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
            CREATE TABLE IF NOT EXISTS submitted_ads (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                body TEXT NOT NULL,
                                approved INTEGER NOT NULL DEFAULT 1,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
            CREATE TABLE IF NOT EXISTS reactions (
                article_id TEXT NOT NULL,
                emoji TEXT NOT NULL,
                count INTEGER NOT NULL DEFAULT 0,
                PRIMARY KEY (article_id, emoji)
            );
            CREATE TABLE IF NOT EXISTS polls (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                question_en TEXT NOT NULL,
                question_ja TEXT NOT NULL,
                active INTEGER NOT NULL DEFAULT 1
            );
            CREATE TABLE IF NOT EXISTS poll_options (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                poll_id INTEGER NOT NULL,
                label_en TEXT NOT NULL,
                label_ja TEXT NOT NULL,
                votes INTEGER NOT NULL DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS classifieds (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                section TEXT NOT NULL DEFAULT 'wanted',
                title TEXT NOT NULL,
                body TEXT NOT NULL,
                contact TEXT,
                approved INTEGER NOT NULL DEFAULT 1,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            );
        SQL);

        // Seed the visitor counter with a suitably retro starting number.
        $db->exec("INSERT OR IGNORE INTO counters (key, value) VALUES ('visits', 1337)");

        // Seed a couple of webring members so the ring is never empty.
        $count = (int) $db->query('SELECT COUNT(*) FROM webring')->fetchColumn();
        if ($count === 0) {
            $db->exec("INSERT INTO webring (site_name, url, description) VALUES
                ('Baka News HQ', '/', 'You are here. Hi.'),
                ('Devippo', 'https://devippo.com/', 'Real code, real projects. A rare outbreak of things that actually exist.'),
                ('Devippo on YouTube', 'https://www.youtube.com/@devippo', 'Watch the builds happen, live and gloriously unfiltered.'),
                ('Anjin', 'https://www.anjin.tech/', 'Genuine tech from a genuine corner of the internet.')");
        }

        // Seed the homepage link-wall with a few real neighbours.
        if ((int) $db->query('SELECT COUNT(*) FROM user_pages')->fetchColumn() === 0) {
            $db->exec("INSERT INTO user_pages (title, url, blurb, badge) VALUES
                ('Devippo', 'https://devippo.com/', 'Actual real code and projects. Suspiciously real for this website.', 'WWW'),
                ('Devippo on YouTube', 'https://www.youtube.com/@devippo', 'Dev videos, builds, and assorted tinkering.', 'TUBE'),
                ('Anjin', 'https://www.anjin.tech/', 'A real technology homepage. We checked. It loads and everything.', 'TECH')");
        }

        // Seed the poll of the week.
        if ((int) $db->query('SELECT COUNT(*) FROM polls')->fetchColumn() === 0) {
            $db->exec("INSERT INTO polls (id, question_en, question_ja) VALUES
                (1, 'Most trustworthy news source?', '一番信頼できる報道機関は？')");
            $db->exec("INSERT INTO poll_options (poll_id, label_en, label_ja, votes) VALUES
                (1, 'A confident cat', '自信満々のネコ', 42),
                (1, 'The ghost in the corner', '隅にいる幽霊', 87),
                (1, 'A Magic 8-Ball', 'マジック8ボール', 61),
                (1, 'Vibes, mostly', 'だいたい雰囲気', 128)");
        }

        // Seed a few classifieds so the section is never empty.
        if ((int) $db->query('SELECT COUNT(*) FROM classifieds')->fetchColumn() === 0) {
            $db->exec("INSERT INTO classifieds (section, title, body, contact) VALUES
                ('wanted', 'WANTED: Left socks', 'Have 40 right socks. Seeking their partners. No questions asked.', 'sock@bakanews.web'),
                ('for-sale', 'FOR SALE: Slightly used echo', 'Barely said anything into it. Repeats itself. $5 obo.', 'hello-hello@bakanews.web'),
                ('lost-found', 'LOST: My train of thought', 'Last seen mid-sentence. Reward: a firm nod.', 'um@bakanews.web'),
                ('services', 'Ghost available for light haunting', 'Weekends only. Very polite. Will not rearrange furniture without asking.', 'boo@bakanews.web')");
        }
    }

    // ---------- Reactions ----------
    /** @return array<string,int> emoji => count */
    public static function reactionsFor(string $articleId): array
    {
        $stmt = self::pdo()->prepare('SELECT emoji, count FROM reactions WHERE article_id = ?');
        $stmt->execute([$articleId]);
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR) ?: [];
    }

    public static function react(string $articleId, string $emoji): int
    {
        $db = self::pdo();
        $db->prepare('INSERT INTO reactions (article_id, emoji, count) VALUES (?, ?, 1)
            ON CONFLICT(article_id, emoji) DO UPDATE SET count = count + 1')
           ->execute([$articleId, $emoji]);
        $stmt = $db->prepare('SELECT count FROM reactions WHERE article_id = ? AND emoji = ?');
        $stmt->execute([$articleId, $emoji]);
        return (int) $stmt->fetchColumn();
    }

    // ---------- Polls ----------
    public static function activePoll(): ?array
    {
        $poll = self::pdo()->query('SELECT * FROM polls WHERE active = 1 ORDER BY id DESC LIMIT 1')->fetch();
        if (!$poll) {
            return null;
        }
        $stmt = self::pdo()->prepare('SELECT * FROM poll_options WHERE poll_id = ? ORDER BY id ASC');
        $stmt->execute([$poll['id']]);
        $poll['options'] = $stmt->fetchAll();
        $poll['total'] = array_sum(array_column($poll['options'], 'votes'));
        return $poll;
    }

    public static function votePoll(int $optionId): bool
    {
        $stmt = self::pdo()->prepare('UPDATE poll_options SET votes = votes + 1 WHERE id = ?');
        $stmt->execute([$optionId]);
        return $stmt->rowCount() > 0;
    }

    // ---------- Classifieds ----------
    public static function classifieds(): array
    {
        return self::pdo()
            ->query('SELECT * FROM classifieds WHERE approved = 1 ORDER BY id DESC LIMIT 100')
            ->fetchAll();
    }

    public static function bumpVisits(): int
    {
        $db = self::pdo();
        $db->exec("UPDATE counters SET value = value + 1 WHERE key = 'visits'");
        return (int) $db->query("SELECT value FROM counters WHERE key = 'visits'")->fetchColumn();
    }

    public static function visits(): int
    {
        return (int) self::pdo()->query("SELECT value FROM counters WHERE key = 'visits'")->fetchColumn();
    }
}
