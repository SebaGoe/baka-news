<?php
/** @var array $stories @var int $sources @var ?array $activeCat */
$activeCat = $activeCat ?? null;
$lang = current_lang();
$catName = $activeCat ? ($lang === 'ja' ? $activeCat['name_ja'] : $activeCat['name_en']) : null;
?>
<section class="newsroom real-edition">
  <div class="section-head" style="--cat-color: <?= e($activeCat['color'] ?? 'var(--link)') ?>">
    <?php if ($activeCat): ?>
      <h1 class="section-head__title"><?= e($catName) ?> &mdash; Real &amp; Weird</h1>
      <p class="section-head__blurb">Genuinely true stories filed under <b><?= e($catName) ?></b>. Every headline links out to its source.</p>
    <?php else: ?>
      <h1 class="section-head__title">Real Edition &mdash; True but Ridiculous</h1>
      <p class="section-head__blurb">
        Genuinely real, genuinely absurd news of the weird &mdash; live from
        <b><?= (int) $sources ?></b> sources plus a 30-year archive. Every headline links out to its
        original article, because we didn&rsquo;t make these up. We wish we had.
      </p>
    <?php endif; ?>
  </div>

  <div class="real-banner">
    <span class="real-banner__stamp">REAL</span>
    <p>You are reading the <b>Real Edition</b>. These stories actually happened somewhere on Earth.
       Tap any story to read it at the source. Want our invented nonsense instead?
       <a class="real-banner__switch" href="?mode=fake">Switch to Baka (Fake) News &rarr;</a></p>
  </div>

  <?php if (empty($stories)): ?>
    <p class="empty">
      <?php if ($activeCat): ?>
        No genuinely weird <b><?= e($catName) ?></b> news right now. The real world is being suspiciously sensible here &mdash; check back later or pick another section.
      <?php else: ?>
        The real world is briefly behaving itself. Try again shortly.
      <?php endif; ?>
    </p>
  <?php else: ?>
  <div class="grid">
    <?php foreach ($stories as $i => $s): ?>
      <?= \Baka\View::partial('partials/real-card', [
        's'    => $s,
        'size' => $i === 0 ? 'lead' : ($i < 3 ? 'feature' : 'standard'),
      ]) ?>
    <?php endforeach; ?>
  </div>
  <p class="real-foot">Each story has its own page here, and links out to the original source. Baka News did not write these and cannot vouch for anything beyond &ldquo;yes, this really got published.&rdquo;</p>
  <?php endif; ?>
</section>
