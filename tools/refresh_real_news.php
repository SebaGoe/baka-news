<?php
declare(strict_types=1);
/**
 * Pull every live weird-news feed and merge with the verified archive into the
 * snapshot the site serves. Safe to run on a schedule (cron) or by hand:
 *
 *   php tools/refresh_real_news.php
 */
require __DIR__ . '/../src/bootstrap.php';

$n = \Baka\RealNews::refresh();
// Also snapshot into the committed seed so a fresh deploy shows real news
// immediately, before any live fetch happens.
$snap = BAKA_STORAGE . '/real-news.json';
if (is_file($snap)) {
    copy($snap, \Baka\RealNews::seedFile());
}
echo "Refreshed real news: {$n} items ("
   . \Baka\RealNews::sourceCount() . " distinct sources). Seed updated.\n";
