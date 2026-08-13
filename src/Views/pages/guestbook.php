<?php /** @var array $entries */
$moods = ['Cheerful','Cool','Wistful','Chaotic','Melting','Dreamy','Nostalgic','Spooky']; ?>
<section class="guestbook">
  <div class="section-head" style="--cat-color: var(--link-visited)">
    <h1 class="section-head__title">Guest Book</h1>
    <p class="section-head__blurb">Sign it like it's 1999. Say hello, leave a note, wave at the ghost.</p>
  </div>

  <form class="retro-form retro-form--inline" method="post" action="<?= url('/guestbook') ?>">
    <?= csrf_field() ?>
    <label>Mood
      <select name="mood">
        <?php foreach ($moods as $m): ?><option><?= e($m) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>Name <input name="name" maxlength="40" required></label>
    <label>Homepage <input name="homepage" placeholder="https://" maxlength="120"></label>
    <label>Message <textarea name="message" rows="2" maxlength="500" required></textarea></label>
    <button class="btn-retro" type="submit">Sign the book</button>
  </form>

  <ol class="gb-list">
    <?php foreach ($entries as $en): ?>
      <li class="gb-entry">
        <div class="gb-entry__mood"><?= e($en['mood']) ?></div>
        <div class="gb-entry__body">
          <div class="gb-entry__name">
            <?php if (!empty($en['homepage'])): ?>
              <a href="<?= e($en['homepage']) ?>" rel="nofollow noopener"><?= e($en['name']) ?></a>
            <?php else: ?><?= e($en['name']) ?><?php endif; ?>
            <span class="gb-entry__date"><?= e($en['created_at']) ?></span>
          </div>
          <div class="gb-entry__msg"><?= nl2br(e($en['message'])) ?></div>
        </div>
      </li>
    <?php endforeach; ?>
    <?php if (empty($entries)): ?><li class="empty">Be the first to sign. The ghost gets lonely.</li><?php endif; ?>
  </ol>
</section>
