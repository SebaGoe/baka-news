<?php /** @var array $ads @var array $submitted */
use Baka\View; ?>
<section class="ads-page">
  <div class="section-head" style="--cat-color: #8a531b">
    <h1 class="section-head__title">Advertising Emporium</h1>
    <p class="section-head__blurb">Premium ad space for your imaginary business. As seen between our fake articles.</p>
  </div>
  <a class="btn-retro" href="<?= url('/ads/submit') ?>">Advertise your fake business</a>

  <h2 class="ads-page__sub">Now Showing</h2>
  <div class="ad-gallery">
    <?php foreach ($ads as $ad): ?><?= View::partial('partials/ad', ['ad' => $ad]) ?><?php endforeach; ?>
  </div>

  <?php if ($submitted): ?>
    <h2 class="ads-page__sub">Submitted by Readers Like You</h2>
    <div class="ad-gallery">
      <?php foreach ($submitted as $s): ?>
        <div class="ad" style="--ad-bg: #efe7d2">
          <div class="ad__tag">Reader ad</div>
          <div class="ad__title"><?= e($s['title']) ?></div>
          <div class="ad__body"><?= e($s['body']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
