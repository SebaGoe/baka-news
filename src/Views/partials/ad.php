<?php /** @var array $ad */
$L = current_lang() === 'ja' ? 'ja' : 'en'; ?>
<div class="ad" style="--ad-bg: <?= e($ad['bg'] ?? '#efe7d2') ?>">
  <div class="ad__tag">Advertisement</div>
  <div class="ad__title"><?= e($ad["title_$L"] ?? $ad['title_en'] ?? '') ?></div>
  <div class="ad__body"><?= e($ad["body_$L"] ?? $ad['body_en'] ?? '') ?></div>
  <a class="ad__cta" href="<?= url('/ads') ?>"><?= e($ad["cta_$L"] ?? $ad['cta_en'] ?? 'Learn more') ?></a>
</div>
