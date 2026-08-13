<?php
declare(strict_types=1);

/**
 * Bootstrap. Loaded by public/index.php before anything else.
 * Uses a tiny hand-rolled PSR-4 autoloader so the app runs WITHOUT composer install.
 * (If composer's vendor/autoload.php exists, that is used instead.)
 */

define('BAKA_ROOT', dirname(__DIR__));
define('BAKA_DATA', BAKA_ROOT . '/data');
define('BAKA_PUBLIC', BAKA_ROOT . '/public');
define('BAKA_STORAGE', getenv('BAKA_STORAGE') ?: BAKA_ROOT . '/storage');
define('BAKA_DB_PATH', getenv('BAKA_DB_PATH') ?: BAKA_ROOT . '/data/db/baka.sqlite');

// Make sure writable dirs exist.
@mkdir(BAKA_STORAGE, 0775, true);
@mkdir(dirname(BAKA_DB_PATH), 0775, true);

if (is_file(BAKA_ROOT . '/vendor/autoload.php')) {
    require BAKA_ROOT . '/vendor/autoload.php';
} else {
    // Minimal PSR-4 autoloader for the Baka\ namespace.
    spl_autoload_register(static function (string $class): void {
        if (!str_starts_with($class, 'Baka\\')) {
            return;
        }
        $relative = str_replace('\\', '/', substr($class, strlen('Baka\\')));
        $file = BAKA_ROOT . '/src/' . $relative . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
    require __DIR__ . '/helpers.php';
}

// Session for coupon redemptions / easter-egg progress.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Boot the database (creates tables on first run).
\Baka\Db::init();
