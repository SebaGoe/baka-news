<?php
declare(strict_types=1);

/**
 * Global helpers. Kept tiny and framework-free.
 */

if (!function_exists('e')) {
    /** HTML-escape. Use for EVERYTHING that reaches a template. */
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('current_lang')) {
    /**
     * Reader's chosen display language for translations: 'native' | 'en' | 'ja'.
     * Controlled by ?lang= and remembered in a cookie. Defaults to 'en'.
     */
    function current_lang(): string
    {
        $allowed = ['native', 'en', 'ja'];
        if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed, true)) {
            setcookie('baka_lang', $_GET['lang'], time() + 31536000, '/');
            return $_GET['lang'];
        }
        $cookie = $_COOKIE['baka_lang'] ?? 'en';
        return in_array($cookie, $allowed, true) ? $cookie : 'en';
    }
}

if (!function_exists('current_mode')) {
    /**
     * Which edition the reader is viewing: 'fake' (our invented Baka News) or
     * 'real' (genuinely true but absurd stories). Set via ?mode= and remembered
     * in a cookie. Defaults to 'fake'.
     */
    function current_mode(): string
    {
        $allowed = ['fake', 'real'];
        if (isset($_GET['mode']) && in_array($_GET['mode'], $allowed, true)) {
            setcookie('baka_mode', $_GET['mode'], time() + 31536000, '/');
            return $_GET['mode'];
        }
        $cookie = $_COOKIE['baka_mode'] ?? 'fake';
        return in_array($cookie, $allowed, true) ? $cookie : 'fake';
    }
}

if (!function_exists('t')) {
    /**
     * Pick the right translation from a {native,en,ja} bag for the current lang.
     * Falls back native -> en so nothing is ever blank.
     */
    function t(array $bag, ?string $lang = null): string
    {
        $lang = $lang ?? current_lang();
        return (string) ($bag[$lang] ?? $bag['native'] ?? $bag['en'] ?? reset($bag) ?: '');
    }
}

if (!function_exists('url')) {
    function url(string $path = '/'): string
    {
        $base = rtrim(getenv('BAKA_BASE_URL') ?: '', '/');
        return $base . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Versioned URL for a static asset. Appends ?v=<filemtime> so browsers
     * pick up CSS/JS changes immediately instead of serving a stale cache.
     */
    function asset(string $path): string
    {
        $file = BAKA_PUBLIC . $path;
        $v = is_file($file) ? filemtime($file) : null;
        return url($path) . ($v ? '?v=' . $v : '');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . url($path), true, 302);
        exit;
    }
}

if (!function_exists('json_out')) {
    function json_out(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('old')) {
    /** Flash-back a previously submitted form value after validation error. */
    function old(string $key, string $default = ''): string
    {
        return e($_SESSION['_old'][$key] ?? $default);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_check')) {
    function csrf_check(): bool
    {
        return isset($_POST['_csrf'], $_SESSION['_csrf'])
            && hash_equals($_SESSION['_csrf'], (string) $_POST['_csrf']);
    }
}
