<?php
/** @var string $lang */
use Baka\Content;
$w = Content::weather();
?>
<header class="masthead">
  <div class="masthead__meta masthead__meta--left">
    <div>Vol. C-Reasoning &middot; No. <?= random_int(100, 999) ?></div>
    <div><?= date('l, F j, Y') ?></div>
    <div class="masthead__weather">Forecast: <?= e($lang === 'ja' ? $w['ja'] : $w['en']) ?></div>
  </div>

  <a class="masthead__title" href="<?= url('/') ?>">
    <span class="masthead__title-main">Baka News</span>
    <span class="masthead__title-sub">The World's Least Reliable Newspaper</span>
  </a>

  <div class="masthead__meta masthead__meta--right">
    <?php $mode = current_mode(); ?>
    <div class="edition-toggle" role="group" aria-label="Edition">
      <a class="edition-toggle__btn<?= $mode === 'fake' ? ' is-active' : '' ?>" href="?mode=fake"<?= $mode === 'fake' ? ' aria-current="true"' : '' ?>>Baka (Fake)</a>
      <a class="edition-toggle__btn edition-toggle__btn--real<?= $mode === 'real' ? ' is-active' : '' ?>" href="?mode=real"<?= $mode === 'real' ? ' aria-current="true"' : '' ?>>Real News</a>
    </div>
    <div><?= $mode === 'real' ? '"Genuinely True, Genuinely Absurd"' : '"All the News That Never Happened"' ?></div>
    <div class="masthead__tools">
      <form class="masthead__search" action="<?= url('/search') ?>" method="get" role="search">
        <label class="sr-only" for="site-search">Search stories</label>
        <input id="site-search" type="search" name="q" placeholder="search stories&hellip;" value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit">Search</button>
      </form>
      <a class="btn-tiny" href="<?= url('/random') ?>">Surprise me</a>
      <button class="btn-tiny" id="night-toggle" type="button" aria-pressed="false">Night edition</button>
    </div>
    <div class="lang-toggle" role="group" aria-label="Translation language">
      <?php foreach (['native' => 'Original', 'en' => 'English', 'ja' => '日本語'] as $code => $label): ?>
        <a href="?lang=<?= $code ?>" class="lang-toggle__btn<?= current_lang() === $code ? ' is-active' : '' ?>"<?= current_lang() === $code ? ' aria-current="true"' : '' ?>><?= $label ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="masthead__rule"></div>
</header>
