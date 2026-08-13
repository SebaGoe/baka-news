<footer class="footer">
  <nav class="footer__links" aria-label="Footer">
    <a href="<?= url('/') ?>">Front Page</a>
    <a href="<?= url('/horoscope') ?>">Baka-scopes</a>
    <a href="<?= url('/classifieds') ?>">Classifieds</a>
    <a href="<?= url('/coupons') ?>">Coupon Vault</a>
    <a href="<?= url('/ads') ?>">Advertise</a>
    <a href="<?= url('/guestbook') ?>">Guest Book</a>
    <a href="<?= url('/webring') ?>">Webring</a>
    <a href="<?= url('/arcade') ?>">Arcade</a>
    <a href="<?= url('/submit-page') ?>">Add Your Page</a>
    <a href="<?= url('/about') ?>">About</a>
    <a href="<?= url('/feed.xml') ?>">RSS</a>
  </nav>
  <div class="footer__badges" aria-hidden="true">
    <span class="badge88">Best viewed in<br><b>Netscape Navigator</b></span>
    <span class="badge88">Made with<br><b>100% HTML</b></span>
    <span class="badge88">Y2K<br><b>Ready</b></span>
    <span class="badge88" id="badge-achievements">Secrets found<br><b><span id="egg-count">0</span> / 5</b></span>
  </div>
  <div class="footer__fine">
    Baka News is satire. Every story is fake. Any resemblance to reality is deeply concerning.
    &copy; <?= date('Y') ?> Baka News. No rights reserved, no wrongs either.
  </div>
</footer>
