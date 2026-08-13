<?php /** @var array $pages */ ?>
<section class="user-pages">
  <div class="section-head" style="--cat-color: #34566e">
    <h1 class="section-head__title">The People's Homepages</h1>
    <p class="section-head__blurb">Add your personal corner of the internet. Blinking text encouraged.</p>
  </div>

  <form class="retro-form" method="post" action="<?= url('/submit-page') ?>">
    <?= csrf_field() ?>
    <label>Tag <input name="badge" maxlength="4" value="WWW"></label>
    <label>Page title <input name="title" maxlength="60" required></label>
    <label>URL <input name="url" placeholder="https://" maxlength="200" required></label>
    <label>Blurb <input name="blurb" maxlength="160"></label>
    <button class="btn-retro" type="submit">Pin my page</button>
  </form>

  <div class="linkwall">
    <?php foreach ($pages as $p): ?>
      <a class="linkwall__item" href="<?= e($p['url']) ?>" rel="nofollow noopener">
        <span class="linkwall__badge"><?= e($p['badge']) ?></span>
        <b><?= e($p['title']) ?></b>
        <span><?= e($p['blurb']) ?></span>
      </a>
    <?php endforeach; ?>
    <?php if (empty($pages)): ?><p class="empty">No homepages yet. Plant the first flag.</p><?php endif; ?>
  </div>
</section>
