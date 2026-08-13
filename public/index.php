<?php
declare(strict_types=1);

/**
 * Baka News — front controller.
 * All requests route through here (see Dockerfile / router usage).
 */

require __DIR__ . '/../src/bootstrap.php';

use Baka\Router;
use Baka\Controllers\NewsController;
use Baka\Controllers\CouponController;
use Baka\Controllers\AdsController;
use Baka\Controllers\CommunityController;
use Baka\Controllers\PageController;

$router = new Router();

// --- Reading ---
$router->get('/', [NewsController::class, 'landing']);
$router->get('/category/{slug}', [NewsController::class, 'category']);
$router->get('/article/{id}', [NewsController::class, 'article']);
$router->get('/search', [NewsController::class, 'search']);
$router->get('/random', [NewsController::class, 'random']);
$router->post('/article/react', [NewsController::class, 'react']);       // JSON
$router->get('/feed.xml', [PageController::class, 'rss']);               // RSS
$router->get('/healthz', [NewsController::class, 'health']);            // keep-alive
$router->get('/real/refresh', [NewsController::class, 'refreshReal']);  // JSON
$router->get('/real/story/{id}', [NewsController::class, 'realArticle']);

// --- Sections (newspaper staples) ---
$router->get('/horoscope', [PageController::class, 'horoscope']);
$router->get('/horoscope/{sign}', [PageController::class, 'horoscopeSign']);
$router->get('/classifieds', [PageController::class, 'classifieds']);
$router->post('/classifieds', [PageController::class, 'storeClassified']);
$router->post('/poll/vote', [PageController::class, 'votePoll']);        // JSON

// --- Fun ---
$router->get('/coupons', [CouponController::class, 'index']);
$router->post('/coupons/redeem', [CouponController::class, 'redeem']);   // JSON

// --- Ads ---
$router->get('/ads', [AdsController::class, 'index']);
$router->get('/ads/submit', [AdsController::class, 'form']);
$router->post('/ads/submit', [AdsController::class, 'store']);

// --- Community (guestbook / webring / user pages / counter) ---
$router->get('/guestbook', [CommunityController::class, 'guestbook']);
$router->post('/guestbook', [CommunityController::class, 'signGuestbook']);
$router->get('/webring', [CommunityController::class, 'webring']);
$router->get('/webring/next', [CommunityController::class, 'webringNext']);
$router->get('/webring/prev', [CommunityController::class, 'webringPrev']);
$router->get('/webring/random', [CommunityController::class, 'webringRandom']);
$router->post('/webring/join', [CommunityController::class, 'webringJoin']);
$router->get('/submit-page', [CommunityController::class, 'submitPage']);
$router->post('/submit-page', [CommunityController::class, 'storePage']);
$router->get('/counter.json', [CommunityController::class, 'counterJson']);   // JSON

// --- Static pages ---
$router->get('/about', [PageController::class, 'about']);
$router->get('/arcade', [PageController::class, 'arcade']);
$router->get('/construction', [PageController::class, 'construction']);
$router->get('/random-page', [PageController::class, 'randomPage']);
$router->get('/game/mix.json', [NewsController::class, 'gameMix']);        // JSON

$router->dispatch();
