<?php
/** @var string $query @var array $results */
?>
<section class="section-head" style="--cat-color: var(--ink-soft)">
  <h1 class="section-head__title">Search Results</h1>
  <?php if ($query !== ''): ?>
    <p class="section-head__blurb">
      <?= count($results) ?> result<?= count($results) === 1 ? '' : 's' ?> for
      &ldquo;<b><?= e($query) ?></b>&rdquo; &mdash; accuracy not guaranteed, nothing here is real anyway.
    </p>
  <?php else: ?>
    <p class="section-head__blurb">Type something in the search box above &mdash; it searches every story in every language. Try &ldquo;cat&rdquo;, &ldquo;politics&rdquo;, or a year like &ldquo;1998&rdquo;.</p>
  <?php endif; ?>
</section>

<?php if ($query !== '' && !$results): ?>
  <div class="notfound">
    <img class="notfound__ghost" src="<?= url('/assets/img/bakabake.svg') ?>" alt="">
    <p class="notfound__msg">No nonsense found.</p>
    <p class="notfound__sub">Bakabake looked everywhere. Even under the couch.</p>
  </div>
<?php elseif ($results): ?>
  <div class="grid grid--search">
    <?php foreach ($results as $a): ?>
      <article class="card card--standard">
        <a class="card__link" href="<?= url('/article/' . e($a['id'])) ?>">
          <div class="card__kicker">
            <?php if (!empty($a['flag'])): ?><span class="origin-code"><?= e($a['flag']) ?></span><?php endif; ?>
            <span class="card__origin"><?= e($a['country']) ?></span>
          </div>
          <h2 class="card__headline"><?= e(t($a['headline'])) ?></h2>
          <p class="card__dek"><?= e(t($a['dek'])) ?></p>
        </a>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
