<?php /** @var array $members */ ?>
<section class="webring">
  <div class="section-head" style="--cat-color: #2b5f6e">
    <h1 class="section-head__title">The Baka Ring</h1>
    <p class="section-head__blurb">A ring of gloriously pointless websites, holding hands across the web.</p>
  </div>

  <div class="ring-controls">
    <a class="btn-retro" href="<?= url('/webring/prev?from=/') ?>">Previous</a>
    <a class="btn-retro" href="<?= url('/webring/random') ?>">Random</a>
    <a class="btn-retro" href="<?= url('/webring/next?from=/') ?>">Next</a>
  </div>

  <ul class="ring-list">
    <?php foreach ($members as $m): ?>
      <li class="ring-item">
        <a href="<?= e($m['url']) ?>" rel="nofollow noopener"><b><?= e($m['site_name']) ?></b></a>
        <span><?= e($m['description']) ?></span>
      </li>
    <?php endforeach; ?>
  </ul>

  <h2 class="ads-page__sub">Join the Ring</h2>
  <form class="retro-form" method="post" action="<?= url('/webring/join') ?>">
    <?= csrf_field() ?>
    <label>Site name <input name="site_name" maxlength="60" required></label>
    <label>URL <input name="url" placeholder="https://" maxlength="200" required></label>
    <label>Description <input name="description" maxlength="140"></label>
    <button class="btn-retro" type="submit">Add my site</button>
  </form>
  <p class="tiny">Paste-in badge: <code>&lt;a href="<?= e(url('/webring')) ?>"&gt;Member of the Baka Ring&lt;/a&gt;</code></p>
</section>
