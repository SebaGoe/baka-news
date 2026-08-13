<?php
/** @var array $stories @var int $sources */
?>
<section class="newsroom real-edition">
  <div class="section-head" style="--cat-color: var(--link)">
    <h1 class="section-head__title">Real Edition &mdash; True but Ridiculous</h1>
    <p class="section-head__blurb">
      Genuinely real, genuinely absurd news of the weird &mdash; live from
      <b><?= (int) $sources ?></b> sources plus a 30-year archive. Every headline links out to its
      original article, because we didn&rsquo;t make these up. We wish we had.
    </p>
  </div>

  <div class="real-banner">
    <span class="real-banner__stamp">REAL</span>
    <p>You are reading the <b>Real Edition</b>. These stories actually happened somewhere on Earth.
       Tap any story to read it at the source. Want our invented nonsense instead?
       <a class="real-banner__switch" href="?mode=fake">Switch to Baka (Fake) News &rarr;</a></p>
  </div>

  <?php if (empty($stories)): ?>
    <p class="empty">The real world is briefly behaving itself. Try again shortly.</p>
  <?php else: ?>
  <div class="grid">
    <?php foreach ($stories as $i => $s):
      $size   = $i === 0 ? 'lead' : ($i < 3 ? 'feature' : 'standard');
      $hasImg = !empty($s['image']);
    ?>
      <article class="card card--<?= $size ?><?= $hasImg ? ' card--has-img' : '' ?>">
        <a class="card__link" href="<?= e($s['url']) ?>" target="_blank" rel="noopener nofollow">
          <?php if ($hasImg): ?>
            <img class="card__img" src="<?= e($s['image']) ?>" alt="" loading="lazy" referrerpolicy="no-referrer"
                 onerror="this.closest('.card').classList.remove('card--has-img'); this.remove();">
          <?php endif; ?>
          <?php $rc = \Baka\Content::category($s['category'] ?? 'weird'); ?>
          <div class="card__kicker">
            <span class="origin-code"><?= e($s['source'] ?? 'News') ?></span>
            <span class="card__origin card__origin--link"><?= e($s['domain'] ?? '') ?></span>
            <span class="realcard__date"><?= e($s['date'] ?? '') ?></span>
            <?php if ($rc): ?><span class="card__cat" style="--c: <?= e($rc['color']) ?>"><?= e($rc['name_en']) ?></span><?php endif; ?>
          </div>
          <h2 class="card__headline"><?= e($s['title']) ?></h2>
          <?php if (!empty($s['blurb'])): ?><p class="card__dek"><?= e($s['blurb']) ?></p><?php endif; ?>
          <span class="card__more">Read at <?= e($s['domain'] ?? 'source') ?> &nearr;</span>
        </a>
      </article>
    <?php endforeach; ?>
  </div>
  <p class="real-foot">All stories link to third-party sources and open in a new tab. Baka News did not write them and cannot vouch for anything beyond &ldquo;yes, this really got published.&rdquo;</p>
  <?php endif; ?>
</section>
