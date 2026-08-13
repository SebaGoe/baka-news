<?php /** @var array $coupons @var array $redeemed */
$lang = current_lang(); $L = $lang === 'ja' ? 'ja' : 'en';
$mono = function ($s) { $s = trim($s); return $s === '' ? '?' : mb_strtoupper(mb_substr($s, 0, 1)); };
?>
<section class="coupons">
  <div class="section-head" style="--cat-color: var(--red)">
    <h1 class="section-head__title">The Coupon Vault</h1>
    <p class="section-head__blurb">Incredible savings on things that do not exist. Some coupons are hidden &mdash; poke around.</p>
  </div>

  <div class="coupon-grid">
    <?php foreach ($coupons as $c):
      $locked = ($c['unlock'] ?? 'open') !== 'open';
      $done = !empty($redeemed[$c['id']]);
      $title = $c["title_$L"] ?? $c['title_en']; ?>
      <button class="coupon coupon--<?= e($c['rarity']) ?><?= $locked ? ' coupon--locked' : '' ?><?= $done ? ' is-redeemed' : '' ?>"
              data-coupon='<?= e(json_encode([
                "id"=>$c["id"],
                "title"=>$title,
                "desc"=>$c["desc_$L"] ?? $c["desc_en"],
                "fine"=>$c["fine_print_$L"] ?? $c["fine_print_en"] ?? "",
                "rarity"=>$c["rarity"],"unlock"=>$c["unlock"] ?? "open"
              ], JSON_UNESCAPED_UNICODE)) ?>'
              data-unlock="<?= e($c['unlock'] ?? 'open') ?>">
        <span class="coupon__rarity"><?= e($c['rarity']) ?></span>
        <span class="coupon__mono" aria-hidden="true"><?= $locked ? '&bull;' : e($mono($title)) ?></span>
        <span class="coupon__title"><?= $locked ? 'Secret coupon' : e($title) ?></span>
        <span class="coupon__hint"><?= $locked ? 'Locked &mdash; find the trick' : 'Tap to open' ?></span>
      </button>
    <?php endforeach; ?>
  </div>
  <p class="coupon-tip">A hint: try the <b>Konami code</b>, click the ghost a few times, and read the fine print everywhere.</p>
</section>
