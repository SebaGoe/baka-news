<?php
/** @var array $story @var array $related */
$lang = current_lang();
$rc = \Baka\Content::category($story['category'] ?? 'weird');
$catName = $rc ? ($lang === 'ja' ? $rc['name_ja'] : $rc['name_en']) : null;
?>
<article class="story real-story">
  <div class="story__kicker">
    <span class="origin-code">REAL</span>
    <span class="story__origin"><?= e($story['source'] ?? 'News') ?></span>
    <?php if ($rc): ?>
      <a class="story__cat" href="<?= url('/category/' . e($rc['slug'])) ?>" style="--cat-color: <?= e($rc['color']) ?>"><?= e($catName) ?></a>
    <?php endif; ?>
  </div>

  <h1 class="story__headline"><?= e($story['title']) ?></h1>

  <div class="story__byline">
    Reported by <b><?= e($story['source'] ?? 'a real newsroom') ?></b>
    &middot; <?= e($story['date'] ?? '') ?>
    &middot; <span class="story__stamp story__stamp--real">Actually true</span>
  </div>

  <?php if (!empty($story['image'])): ?>
    <figure class="story__figure story__figure--photo">
      <img class="story__img" src="<?= e($story['image']) ?>" alt="" referrerpolicy="no-referrer"
           onerror="this.closest('.story__figure').style.display='none';">
      <figcaption class="story__figcaption">
        <span class="story__figcaption-cap">Photo from the original report.</span>
        <span class="story__credit"><?= e($story['domain'] ?? '') ?></span>
      </figcaption>
    </figure>
  <?php endif; ?>

  <div class="story__body">
    <?php if (!empty($story['blurb'])): ?>
      <p class="has-dropcap"><span class="dropcap"><?= e(mb_substr($story['blurb'], 0, 1)) ?></span><?= e(mb_substr($story['blurb'], 1)) ?></p>
    <?php endif; ?>
    <p class="real-story__note">This is a real, genuinely-published story. We didn&rsquo;t write it and only summarise the headline &mdash; read the full thing at the source:</p>
  </div>

  <div class="story__actions">
    <a class="btn-retro btn-retro--go" href="<?= e($story['url']) ?>" target="_blank" rel="noopener nofollow">
      Read the full story at <?= e($story['domain'] ?? 'the source') ?> &nearr;
    </a>
    <a class="btn-retro" href="<?= url('/random') ?>">Surprise me</a>
  </div>

  <p class="story__disclaimer">Real Edition: links go to third-party sites Baka News does not control. Weirdness verified; everything else is on them.</p>
</article>

<?php if ($related): ?>
<section class="related" aria-label="Related real stories">
  <h2 class="related__title">More Real Weirdness</h2>
  <div class="related__grid">
    <?php foreach ($related as $r): ?>
      <a class="related__item" href="<?= url('/real/story/' . e($r['id'])) ?>">
        <span class="origin-code"><?= e($r['domain'] ?? 'news') ?></span>
        <span class="related__head"><?= e($r['title']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<p class="back-link"><a href="<?= url('/') ?>">Back to the front page</a></p>
