<?php
/** @var string $query @var array $stories */
?>
<section class="section-head" style="--cat-color: var(--link)">
  <h1 class="section-head__title">Real Search</h1>
  <?php if ($query !== ''): ?>
    <p class="section-head__blurb">
      <?= count($stories) ?> real result<?= count($stories) === 1 ? '' : 's' ?> for
      &ldquo;<b><?= e($query) ?></b>&rdquo; across the weird-but-true feed.
    </p>
  <?php else: ?>
    <p class="section-head__blurb">Search real weird news. Try &ldquo;ghost&rdquo;, &ldquo;florida&rdquo;, &ldquo;lottery&rdquo;, or &ldquo;snake&rdquo;.</p>
  <?php endif; ?>
</section>

<?php if ($query !== '' && !$stories): ?>
  <div class="notfound">
    <img class="notfound__ghost" src="<?= url('/assets/img/bakabake.svg') ?>" alt="">
    <p class="notfound__msg">No real weirdness found for that.</p>
    <p class="notfound__sub">The real world declined to be weird on demand. Try another word.</p>
  </div>
<?php elseif ($stories): ?>
  <section class="newsroom real-edition">
    <div class="grid">
      <?php foreach ($stories as $i => $s): ?>
        <?= \Baka\View::partial('partials/real-card', [
          's'    => $s,
          'size' => $i === 0 ? 'feature' : 'standard',
        ]) ?>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>
