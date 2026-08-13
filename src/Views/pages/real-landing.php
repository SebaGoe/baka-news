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
       Tap any story to read it at the source. Want nonsense again?
       <a class="real-banner__switch" href="?mode=fake">Back to Baka (Fake) News &rarr;</a></p>
  </div>

  <?php if (empty($stories)): ?>
    <p class="empty">The real world is briefly behaving itself. Try again shortly.</p>
  <?php else: ?>
  <div class="real-grid">
    <?php foreach ($stories as $s): ?>
      <article class="realcard">
        <a class="realcard__link" href="<?= e($s['url']) ?>" target="_blank" rel="noopener nofollow">
          <div class="realcard__kicker">
            <span class="origin-code"><?= e($s['source'] ?? 'News') ?></span>
            <span class="realcard__domain"><?= e($s['domain'] ?? '') ?></span>
            <span class="realcard__date"><?= e($s['date'] ?? '') ?></span>
          </div>
          <h2 class="realcard__headline"><?= e($s['title']) ?></h2>
          <?php if (!empty($s['blurb'])): ?>
            <p class="realcard__blurb"><?= e($s['blurb']) ?></p>
          <?php endif; ?>
          <span class="realcard__more">Read at <?= e($s['domain'] ?? 'source') ?> &nearr;</span>
        </a>
      </article>
    <?php endforeach; ?>
  </div>
  <p class="real-foot">All stories link to third-party sources and open in a new tab. Baka News did not write them and cannot vouch for anything beyond &ldquo;yes, this really got published.&rdquo;</p>
  <?php endif; ?>
</section>
