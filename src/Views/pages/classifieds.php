<?php
/** @var array $ads */
$sections = ['wanted'=>'Wanted','for-sale'=>'For Sale','lost-found'=>'Lost & Found','services'=>'Services'];
$bySection = [];
foreach ($ads as $a) { $bySection[$a['section']][] = $a; }
?>
<section class="section-head" style="--cat-color: var(--ink)">
  <h1 class="section-head__title">The Classifieds</h1>
  <p class="section-head__blurb">Tiny ads, enormous nonsense. Post your own below.</p>
</section>

<div class="classifieds">
  <?php foreach ($sections as $key => $label): ?>
    <div class="classifieds__col">
      <h2 class="classifieds__head"><?= e($label) ?></h2>
      <?php if (empty($bySection[$key])): ?>
        <p class="empty">Nothing here yet. Suspicious.</p>
      <?php else: foreach ($bySection[$key] as $ad): ?>
        <div class="classified">
          <div class="classified__title"><?= e($ad['title']) ?></div>
          <div class="classified__body"><?= e($ad['body']) ?></div>
          <?php if (!empty($ad['contact'])): ?>
            <div class="classified__contact">Contact: <?= e($ad['contact']) ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<h2 class="ads-page__sub">Place a classified</h2>
<form class="retro-form" action="<?= url('/classifieds') ?>" method="post">
  <?= csrf_field() ?>
  <label>Section
    <select name="section">
      <?php foreach ($sections as $key => $label): ?>
        <option value="<?= e($key) ?>"><?= e($label) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Headline
    <input type="text" name="title" maxlength="70" required placeholder="Wanted: a nap, urgently">
  </label>
  <label>Details
    <textarea name="body" rows="3" maxlength="300" required placeholder="Describe your beautiful nonsense"></textarea>
  </label>
  <label>Contact (optional)
    <input type="text" name="contact" maxlength="80" placeholder="carrier pigeon no. 4">
  </label>
  <button class="btn-retro" type="submit">Pin it to the board</button>
</form>
