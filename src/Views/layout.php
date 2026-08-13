<?php
/** @var string $title @var string $content */
use Baka\View;
$lang = current_lang();
?>
<!DOCTYPE html>
<html lang="<?= e($lang === 'ja' ? 'ja' : 'en') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Baka News') ?></title>
    <meta name="description" content="Baka News — 100% fake, 100% cute. The world's least reliable newspaper.">
    <link rel="icon" href="<?= url('/assets/img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="alternate" type="application/rss+xml" title="Baka News" href="<?= url('/feed.xml') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/browser.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/retro.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/newspaper.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/features.css') ?>">
    <script>
      try { if (localStorage.getItem('baka_night') === '1') document.documentElement.setAttribute('data-theme', 'night'); } catch (e) {}
    </script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to the nonsense</a>

    <div class="browser">
      <!-- Title bar -->
      <div class="browser__titlebar">
        <span class="browser__title"><span class="browser__logo" aria-hidden="true">N</span> Baka News &mdash; Netscape Navigator</span>
        <span class="browser__winbtns">
          <button type="button" class="winbtn winbtn--min" id="win-min" aria-label="Minimize window" aria-pressed="false"></button>
          <button type="button" class="winbtn winbtn--max" id="win-max" aria-label="Maximize window" aria-pressed="false"></button>
          <button type="button" class="winbtn winbtn--close" id="win-close" aria-label="Close window">&times;</button>
        </span>
      </div>

      <!-- Menu bar -->
      <nav class="browser__menubar" aria-label="Browser menu">
        <button class="menu" data-menu="file" aria-expanded="false"><u>F</u>ile</button>
        <button class="menu" data-menu="edit" aria-expanded="false"><u>E</u>dit</button>
        <button class="menu" data-menu="view" aria-expanded="false"><u>V</u>iew</button>
        <button class="menu" data-menu="go" aria-expanded="false"><u>G</u>o</button>
        <button class="menu" data-menu="bookmarks" aria-expanded="false"><u>B</u>ookmarks</button>
        <button class="menu" data-menu="options" aria-expanded="false"><u>O</u>ptions</button>
        <button class="menu" data-menu="help" aria-expanded="false"><u>H</u>elp</button>
      </nav>

      <!-- Toolbar -->
      <div class="browser__toolbar">
        <button class="tbtn" id="nav-back" type="button"><span class="tbtn__i" aria-hidden="true">&lsaquo;</span>Back</button>
        <button class="tbtn" id="nav-fwd" type="button"><span class="tbtn__i" aria-hidden="true">&rsaquo;</span>Forward</button>
        <button class="tbtn" id="nav-reload" type="button">Reload</button>
        <a class="tbtn" id="nav-home" href="<?= url('/') ?>">Home</a>
        <button class="tbtn" id="nav-search" type="button">Search</button>
        <button class="tbtn" id="nav-print" type="button">Print</button>
        <button class="tbtn tbtn--stop" id="nav-stop" type="button">Stop</button>
        <span class="browser__throbber" id="throbber" aria-hidden="true"><span class="throbber__n">N</span></span>
      </div>

      <!-- Location / address bar -->
      <form class="browser__location" id="addressbar" role="search" autocomplete="off">
        <label for="address" class="browser__loclabel">Location:</label>
        <input id="address" name="address" type="text" spellcheck="false"
               value="http://www.bakanews.web/<?= e(trim($_SERVER['REQUEST_URI'] ?? '/', '/')) ?>"
               aria-describedby="address-hint">
        <button type="submit" class="browser__go">Go</button>
        <span id="address-hint" class="sr-only">Type a path to visit, or a secret number to see what happens.</span>
      </form>

      <!-- Viewport: the actual newspaper -->
      <div class="browser__viewport">
        <div class="page-frame">
            <?= View::partial('partials/marquee') ?>
            <?= View::partial('partials/masthead', ['lang' => $lang]) ?>
            <?= View::partial('partials/nav', ['categories' => $categories ?? [], 'activeCat' => $activeCat ?? null]) ?>
            <?= View::partial('partials/ticker') ?>

            <main id="main" class="layout">
                <div class="layout__body">
                    <?= $content ?>
                </div>
                <?= View::partial('partials/sidebar') ?>
            </main>

            <?= View::partial('partials/footer') ?>
        </div>
      </div>

      <!-- Status bar -->
      <div class="browser__status">
        <span class="browser__lock" aria-hidden="true">SSL</span>
        <span class="browser__status-text" id="statusbar" aria-live="polite">Document: Done</span>
        <span class="browser__progress" id="progress" aria-hidden="true"></span>
      </div>
    </div>

    <?= View::partial('partials/mascot') ?>

    <script src="<?= asset('/assets/js/counter.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/modal.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/swipe.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/mascot.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/main.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/eggs.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/browser.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/arcade.js') ?>" defer></script>
    <script src="<?= asset('/assets/js/features.js') ?>" defer></script>
</body>
</html>
