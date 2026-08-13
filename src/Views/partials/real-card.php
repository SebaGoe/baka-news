<?php
/** @var array $s @var string $size */
$size = $size ?? 'standard';
$hasImg = !empty($s['image']);
$rc = \Baka\Content::category($s['category'] ?? 'weird');
?>
<article class="card card--<?= e($size) ?><?= $hasImg ? ' card--has-img' : '' ?>">
  <a class="card__link" href="<?= url('/real/story/' . e($s['id'])) ?>">
    <?php if ($hasImg): ?>
      <img class="card__img" src="<?= e($s['image']) ?>" alt="" loading="lazy" referrerpolicy="no-referrer"
           onerror="this.closest('.card').classList.remove('card--has-img'); this.remove();">
    <?php endif; ?>
    <div class="card__kicker">
      <span class="origin-code"><?= e($s['source'] ?? 'News') ?></span>
      <span class="card__origin card__origin--link"><?= e($s['domain'] ?? '') ?></span>
      <span class="realcard__date"><?= e($s['date'] ?? '') ?></span>
      <?php if ($rc): ?><span class="card__cat" style="--c: <?= e($rc['color']) ?>"><?= e($rc['name_en']) ?></span><?php endif; ?>
    </div>
    <h2 class="card__headline"><?= e($s['title']) ?></h2>
    <?php if (!empty($s['blurb'])): ?><p class="card__dek"><?= e($s['blurb']) ?></p><?php endif; ?>
    <span class="card__more">Read the story &rsaquo;</span>
  </a>
</article>
