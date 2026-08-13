<?php
use Baka\Content;
$rows = Content::exchange();
?>
<div class="exchange" aria-hidden="true">
  <span class="exchange__brand">Baka Exchange</span>
  <div class="exchange__track">
    <?php for ($rep = 0; $rep < 2; $rep++): ?>
      <?php foreach ($rows as $r): $up = $r['change'] >= 0; ?>
        <span class="exchange__item">
          <b><?= e($r['symbol']) ?></b>
          <?= number_format((float) $r['price'], 2) ?>
          <span class="exchange__chg <?= $up ? 'up' : 'down' ?>"><?= $up ? '+' : '&minus;' ?><?= number_format(abs((float) $r['change']), 2) ?></span>
        </span>
      <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>
