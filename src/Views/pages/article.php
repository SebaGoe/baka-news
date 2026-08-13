<?php
/** @var array $article @var array $related @var array $reactions */
use Baka\Content;
$reactions = $reactions ?? [];
$reactSet = ['ha' => 'Ha!', 'whoa' => 'Whoa', 'hmm' => 'Hmm', 'oof' => 'Oof', 'boo' => 'Boo'];
$lang = current_lang();
$body = $article['body'][$lang] ?? $article['body']['native'] ?? [];
$displayLang = $lang === 'native' ? ($article['lang'] ?? 'en') : $lang;
?>
<article class="story">
  <div class="story__kicker">
    <?php if (!empty($article['flag'])): ?><span class="origin-code"><?= e($article['flag']) ?></span><?php endif; ?>
    <span class="story__origin"><?= e($article['country']) ?></span>
    <?php foreach (($article['categories'] ?? []) as $cs): $cat = Content::category($cs); if ($cat): ?>
      <a class="story__cat" href="<?= url('/category/' . e($cat['slug'])) ?>" style="--cat-color: <?= e($cat['color']) ?>">
        <?= e($lang === 'ja' ? $cat['name_ja'] : $cat['name_en']) ?>
      </a>
    <?php endif; endforeach; ?>
  </div>

  <h1 class="story__headline" lang="<?= e($displayLang) ?>"><?= e(t($article['headline'])) ?></h1>
  <?php if ($dek = t($article['dek'])): ?><p class="story__dek" lang="<?= e($displayLang) ?>"><?= e($dek) ?></p><?php endif; ?>

  <div class="story__byline">
    By <b><?= e($article['author'] ?? 'Staff Ghostwriter') ?></b>
    &middot; <?= e(date('F j, Y', strtotime($article['date'] ?? 'now'))) ?>
    &middot; <span class="story__stamp">Not fact-checked</span>
  </div>

  <?php if ($lang !== 'native'): ?>
    <p class="story__translated">Translated from the original <?= e(strtoupper($article['lang'] ?? '')) ?>.
      <a href="?lang=native">Show the original</a></p>
  <?php endif; ?>

  <?php $isPhoto = !empty($article['image']); ?>
  <figure class="story__figure<?= $isPhoto ? ' story__figure--photo' : '' ?>">
    <img class="story__img" src="<?= asset(Content::articleImage($article)) ?>" alt="" width="400" height="280">
    <figcaption class="story__figcaption">
      <?php if ($isPhoto): ?>
        <span class="story__figcaption-cap">Unrelated real photo, printed with a straight face.</span>
        <span class="story__credit"><?= e($article['image_credit'] ?? 'Photo via Wikimedia Commons') ?></span>
      <?php else: ?>
        Artist's impression. No photographers were dispatched; none exist.
      <?php endif; ?>
    </figcaption>
  </figure>

  <div class="story__body" lang="<?= e($displayLang) ?>">
    <?php foreach ($body as $n => $p): ?>
      <p<?= $n === 0 ? ' class="has-dropcap"' : '' ?>>
        <?php if ($n === 0): ?><span class="dropcap"><?= e(mb_substr($p, 0, 1)) ?></span><?= e(mb_substr($p, 1)) ?>
        <?php else: ?><?= e($p) ?><?php endif; ?>
      </p>
    <?php endforeach; ?>
  </div>

  <div class="reactions" id="reactions" data-article="<?= e($article['id']) ?>">
    <span class="reactions__label" id="react-label">Reader reactions</span>
    <div class="reactions__row" role="group" aria-labelledby="react-label">
      <?php foreach ($reactSet as $key => $word): ?>
        <button class="reactions__btn" data-emoji="<?= e($key) ?>">
          <span class="reactions__word"><?= e($word) ?></span>
          <span class="reactions__count" data-count="<?= e($key) ?>"><?= (int) ($reactions[$key] ?? 0) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="story__actions no-print">
    <button class="btn-retro" type="button" onclick="window.print()">Print this story</button>
    <a class="btn-retro" href="<?= url('/random') ?>">Another story</a>
  </div>

  <p class="story__disclaimer">This article is entirely fabricated for your amusement. Please do not cite it in a thesis.</p>
</article>

<?php if ($related): ?>
<section class="related" aria-label="Related stories">
  <h2 class="related__title">Related Nonsense</h2>
  <div class="related__grid">
    <?php foreach ($related as $r): ?>
      <a class="related__item" href="<?= url('/article/' . e($r['id'])) ?>">
        <?php if (!empty($r['flag'])): ?><span class="origin-code"><?= e($r['flag']) ?></span><?php endif; ?>
        <span class="related__head"><?= e(t($r['headline'])) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<p class="back-link"><a href="<?= url('/') ?>">Back to the front page</a></p>
