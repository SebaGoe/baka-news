<?php
/**
 * Router for PHP's built-in server (php -S 0.0.0.0:$PORT server.php).
 * Serves existing static files from /public with correct MIME types;
 * everything else is handled by public/index.php. Works locally and on Render.
 */
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = realpath(__DIR__ . '/public' . $uri);
$root = realpath(__DIR__ . '/public');

// Serve a real file inside /public (guard against path traversal).
if ($uri !== '/' && $file && $root && str_starts_with($file, $root) && is_file($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css', 'js' => 'application/javascript', 'mjs' => 'application/javascript',
        'svg' => 'image/svg+xml', 'json' => 'application/json', 'xml' => 'application/xml',
        'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif',
        'webp' => 'image/webp', 'ico' => 'image/x-icon', 'txt' => 'text/plain',
        'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf',
    ];
    $mime = $types[$ext] ?? 'application/octet-stream';
    $text = in_array($ext, ['css', 'js', 'mjs', 'svg', 'json', 'xml', 'txt'], true);
    header('Content-Type: ' . $mime . ($text ? '; charset=utf-8' : ''));
    header('Content-Length: ' . filesize($file));
    header('Cache-Control: public, max-age=3600');
    readfile($file);
    return true;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/public/index.php';
