<section class="form-page">
  <div class="section-head" style="--cat-color: #8a531b">
    <h1 class="section-head__title">Advertise Your Fake Business</h1>
    <p class="section-head__blurb">Keep it silly, keep it clean. The ghost is watching.</p>
  </div>
  <form class="retro-form" method="post" action="<?= url('/ads/submit') ?>">
    <?= csrf_field() ?>
    <label>Business name <input name="title" maxlength="60" required value="<?= old('title') ?>"></label>
    <label>Pitch <textarea name="body" maxlength="200" rows="3" required><?= old('body') ?></textarea></label>
    <button class="btn-retro" type="submit">Publish my fake ad</button>
  </form>
</section>
