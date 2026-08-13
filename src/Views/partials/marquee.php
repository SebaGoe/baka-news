<?php
use Baka\Content;
$heads = [];
foreach (array_slice(Content::articles(), 0, 6) as $a) {
    $heads[] = t($a['headline'], 'en');
}
$heads[] = 'You are visitor no. <b id="marquee-visits">&hellip;</b>';
$heads[] = 'Free coupons in the Coupon Vault';
$line = implode(' &nbsp;&mdash;&nbsp; ', $heads);
?>
<div class="marquee" aria-hidden="true">
  <div class="marquee__track">
    <span><?= $line ?> &nbsp;&mdash;&nbsp; </span>
    <span><?= $line ?> &nbsp;&mdash;&nbsp; </span>
  </div>
</div>
