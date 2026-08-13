<?php
/** @var array $signs @var ?array $today @var ?array $focus */
$lang = current_lang();
$focus = $focus ?? $today;
?>
<section class="section-head" style="--cat-color: var(--link)">
  <h1 class="section-head__title">Baka-scopes</h1>
  <p class="section-head__blurb">Astrology, but we made it up even harder than usual.</p>
</section>

<?php if ($focus): ?>
<div class="horo-feature">
  <div class="horo-feature__name"><?= e($lang === 'ja' ? $focus['name_ja'] : $focus['name_en']) ?></div>
  <p class="horo-feature__text"><?= e($lang === 'ja' ? $focus['ja'] : $focus['en']) ?></p>
</div>
<?php endif; ?>

<div class="horo-grid">
  <?php foreach ($signs as $s): ?>
    <a class="horo-card<?= ($focus && $s['sign'] === $focus['sign']) ? ' is-active' : '' ?>"
       href="<?= url('/horoscope/' . e($s['sign'])) ?>">
      <span class="horo-card__name"><?= e($lang === 'ja' ? $s['name_ja'] : $s['name_en']) ?></span>
      <span class="horo-card__text"><?= e($lang === 'ja' ? $s['ja'] : $s['en']) ?></span>
    </a>
  <?php endforeach; ?>
</div>
