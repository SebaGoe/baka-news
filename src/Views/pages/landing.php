<?php
/** @var array $articles @var ?array $activeCat */
use Baka\Content;
use Baka\View;
$lang = current_lang();
?>
<section class="newsroom" data-swipe-root>
  <?php if ($activeCat): ?>
    <div class="section-head" style="--cat-color: <?= e($activeCat['color']) ?>">
      <h1 class="section-head__title"><?= e($lang === 'ja' ? $activeCat['name_ja'] : $activeCat['name_en']) ?></h1>
      <p class="section-head__blurb"><?= e($lang === 'ja' ? $activeCat['blurb_ja'] : $activeCat['blurb_en']) ?></p>
    </div>
  <?php endif; ?>

  <?php if (empty($articles)): ?>
    <p class="empty">No news here yet. Suspicious. Something may have eaten it.</p>
  <?php endif; ?>

  <div class="grid">
    <?php $i = 0; foreach ($articles as $a):
      $size = $a['size'] ?? 'standard';
      $head = t($a['headline']);
      $dek  = t($a['dek']);
      $lead = ($a['body'][$lang] ?? $a['body']['native'] ?? [])[0] ?? '';
      $firstCat = Content::category(($a['categories'] ?? [])[0] ?? '');
    ?>
      <?php $showImg = in_array($size, ['lead', 'feature'], true); ?>
      <article class="card card--<?= e($size) ?><?= $showImg ? ' card--has-img' : '' ?>">
        <a class="card__link" href="<?= url('/article/' . e($a['id'])) ?>">
          <?php if ($showImg): ?>
            <img class="card__img" src="<?= asset(Content::articleImage($a)) ?>" alt="" width="400" height="280" loading="lazy">
          <?php endif; ?>
          <div class="card__kicker">
            <?php if (!empty($a['flag'])): ?><span class="origin-code"><?= e($a['flag']) ?></span><?php endif; ?>
            <span class="card__origin"><?= e($a['country']) ?></span>
            <?php if ($firstCat): ?>
              <span class="card__cat" style="--c: <?= e($firstCat['color']) ?>"><?= e($lang === 'ja' ? $firstCat['name_ja'] : $firstCat['name_en']) ?></span>
            <?php endif; ?>
          </div>
          <h2 class="card__headline"><?= e($head) ?></h2>
          <?php if ($dek): ?><p class="card__dek"><?= e($dek) ?></p><?php endif; ?>
          <?php if (in_array($size, ['lead', 'feature'], true) && $lead): ?>
            <p class="card__lead"><span class="dropcap"><?= e(mb_substr($lead, 0, 1)) ?></span><?= e(mb_substr($lead, 1)) ?></p>
          <?php endif; ?>
          <span class="card__more">Read the full story</span>
        </a>
      </article>
      <?php
        if (++$i % 4 === 0):
          $ad = Content::feedAd(intdiv($i, 4) - 1);
          if ($ad): ?>
            <div class="card card--ad"><?= View::partial('partials/ad', ['ad' => $ad]) ?></div>
      <?php endif; endif; ?>
    <?php endforeach; ?>
  </div>
</section>
