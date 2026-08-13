<?php
use Baka\Content;
use Baka\View;
$horo = Content::horoscopeOfTheDay();
$lang = current_lang();
?>
<aside class="rail" aria-label="Sidebar">
  <div class="counter-box">
    <div class="counter-box__label">You are visitor</div>
    <div class="counter-box__digits" id="visitor-counter" aria-live="polite">0000000</div>
    <div class="counter-box__label">since forever ago</div>
  </div>

  <?= View::partial('partials/poll') ?>

  <?php if ($horo): ?>
    <section class="rail__box horo-box" aria-label="Horoscope of the day">
      <div class="rail__box-title">Baka-scope of the Day</div>
      <div class="horo-box__sign"><?= e($lang === 'ja' ? $horo['name_ja'] : $horo['name_en']) ?></div>
      <p class="horo-box__text"><?= e($lang === 'ja' ? $horo['ja'] : $horo['en']) ?></p>
      <a class="horo-box__more" href="<?= url('/horoscope') ?>">All signs</a>
    </section>
  <?php endif; ?>

  <?php foreach (Content::ads('sidebar') as $ad): ?>
    <?= View::partial('partials/ad', ['ad' => $ad]) ?>
  <?php endforeach; ?>

  <?= View::partial('partials/webring-badge') ?>

  <div class="rail__box rail__box--construction">
    <div class="rail__box-title">Under Construction</div>
    <a href="<?= url('/construction') ?>">
      <img src="<?= asset('/assets/img/under-construction.svg') ?>" alt="Under construction" width="140" height="88" style="width:100%;height:auto;image-rendering:auto;">
    </a>
    <p class="tiny">This site is eternally 87% done.</p>
  </div>
</aside>
