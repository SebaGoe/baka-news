<?php /** @var array $categories @var ?array $activeCat */ ?>
<nav class="catnav" id="catnav" aria-label="Sections">
  <a href="<?= url('/') ?>" class="catnav__item<?= empty($activeCat) ? ' is-active' : '' ?>"<?= empty($activeCat) ? ' aria-current="page"' : '' ?>>Front Page</a>
  <?php foreach ($categories as $c): $on = ($activeCat['slug'] ?? '') === $c['slug']; ?>
    <a href="<?= url('/category/' . e($c['slug'])) ?>"
       class="catnav__item<?= $on ? ' is-active' : '' ?>"
       style="--cat-color: <?= e($c['color']) ?>"
       data-cat="<?= e($c['slug']) ?>"<?= $on ? ' aria-current="page"' : '' ?>>
      <span class="cat-dot" aria-hidden="true"></span><?= e(current_lang() === 'ja' ? $c['name_ja'] : $c['name_en']) ?>
    </a>
  <?php endforeach; ?>
  <span class="catnav__sep" aria-hidden="true"></span>
  <a href="<?= url('/horoscope') ?>" class="catnav__item catnav__item--extra">Baka-scopes</a>
  <a href="<?= url('/classifieds') ?>" class="catnav__item catnav__item--extra">Classifieds</a>
  <a href="<?= url('/coupons') ?>" class="catnav__item catnav__item--extra">Coupons</a>
</nav>
<p class="swipe-hint" aria-hidden="true">Swipe left or right to change section</p>
