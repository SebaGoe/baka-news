<?php $ghost = url('/assets/img/bakabake.svg'); ?>
<section class="arcade" data-arcade>
  <div class="section-head" style="--cat-color: var(--red)">
    <h1 class="section-head__title">The Arcade</h1>
    <p class="section-head__blurb">Three games. Zero prizes. Infinite dignity on the line.</p>
  </div>

  <!-- Game selector -->
  <div class="arcade__tabs" role="tablist" aria-label="Choose a game">
    <button class="arcade__tab is-active" role="tab" aria-selected="true" data-game="whack">Whack-a-Ghost</button>
    <button class="arcade__tab" role="tab" aria-selected="false" data-game="snake">Ghost Snake</button>
    <button class="arcade__tab" role="tab" aria-selected="false" data-game="mines">Baka-sweeper</button>
    <button class="arcade__tab" role="tab" aria-selected="false" data-game="guess">Real or Baka?</button>
  </div>

  <!-- ============ Whack-a-Ghost ============ -->
  <div class="arcade__game is-active" data-game-panel="whack">
    <div class="arcade__hud">
      <span class="arcade__stat">Score <b id="whack-score" aria-live="polite">0</b></span>
      <span class="arcade__stat">Time <b id="whack-time" aria-live="polite">30</b></span>
      <span class="arcade__stat">Best <b id="whack-hi">0</b></span>
    </div>
    <div class="arcade__grid" id="whack-grid" role="group" aria-label="Whack-a-Ghost board">
      <?php for ($i = 1; $i <= 9; $i++): ?>
        <button class="hole" type="button" aria-label="Hole <?= $i ?>">
          <img class="hole__ghost" src="<?= e($ghost) ?>" alt="">
        </button>
      <?php endfor; ?>
    </div>
    <div class="arcade__actions">
      <button class="btn-retro" type="button" id="whack-start">Start game</button>
    </div>
    <p class="arcade__msg" id="whack-msg">Press <b>Start</b>. Ghosts pop up &mdash; click them (or press keys 1&ndash;9) before they duck. Reach <b>15</b> to unlock a Coupon Vault surprise.</p>
  </div>

  <!-- ============ Ghost Snake ============ -->
  <div class="arcade__game" data-game-panel="snake" hidden>
    <div class="arcade__hud">
      <span class="arcade__stat">Score <b id="snake-score" aria-live="polite">0</b></span>
      <span class="arcade__stat">Best <b id="snake-hi">0</b></span>
    </div>
    <canvas id="snake-canvas" class="arcade__canvas" width="336" height="336"
            role="img" aria-label="Ghost Snake board"></canvas>
    <div class="arcade__actions">
      <button class="btn-retro" type="button" id="snake-start">Start game</button>
    </div>
    <p class="arcade__msg" id="snake-msg">Use <b>arrow keys</b> or <b>WASD</b> (or the on-screen pad) to eat coupons. Don't bite your own tail.</p>
    <div class="dpad" aria-hidden="false">
      <button class="dpad__btn dpad__up"    data-dir="up"    aria-label="Up">&uarr;</button>
      <button class="dpad__btn dpad__left"  data-dir="left"  aria-label="Left">&larr;</button>
      <button class="dpad__btn dpad__right" data-dir="right" aria-label="Right">&rarr;</button>
      <button class="dpad__btn dpad__down"  data-dir="down"  aria-label="Down">&darr;</button>
    </div>
  </div>

  <!-- ============ Baka-sweeper ============ -->
  <div class="arcade__game" data-game-panel="mines" hidden>
    <div class="arcade__hud">
      <span class="arcade__stat">Ghosts <b id="mines-count">10</b></span>
      <span class="arcade__stat">Flags <b id="mines-flags">0</b></span>
      <span class="arcade__stat" id="mines-face-wrap"><button class="btn-retro btn-retro--sm" id="mines-reset" type="button">New board</button></span>
    </div>
    <div class="minesweeper" id="mines-grid" role="group" aria-label="Baka-sweeper board"></div>
    <p class="arcade__msg" id="mines-msg">Click to reveal. <b>Right-click</b> (or long-press) to flag a ghost. Clear every safe square without waking a ghost.</p>
  </div>

  <!-- ============ Real or Baka? ============ -->
  <div class="arcade__game" data-game-panel="guess" hidden>
    <div class="arcade__hud">
      <span class="arcade__stat">Score <b id="guess-score">0</b></span>
      <span class="arcade__stat">Round <b id="guess-round">0</b>/16</span>
      <span class="arcade__stat">Streak <b id="guess-streak">0</b></span>
    </div>
    <div class="guess" id="guess-stage">
      <p class="guess__prompt">Real news, or invented Baka nonsense? You decide.</p>
      <blockquote class="guess__headline" id="guess-headline">Press Start to play.</blockquote>
      <div class="guess__buttons">
        <button class="btn-retro guess__btn guess__btn--real" id="guess-real" type="button" disabled>It&rsquo;s REAL</button>
        <button class="btn-retro guess__btn guess__btn--fake" id="guess-fake" type="button" disabled>It&rsquo;s BAKA</button>
      </div>
      <p class="guess__verdict" id="guess-verdict" aria-live="polite"></p>
    </div>
    <div class="arcade__actions">
      <button class="btn-retro" type="button" id="guess-start">Start game</button>
    </div>
    <p class="arcade__msg">Sixteen headlines &mdash; half genuinely happened, half we made up. How sharp is your baka-dar?</p>
  </div>

  <div class="arcade__actions">
    <a class="btn-retro" href="<?= url('/') ?>">Back to the front page</a>
    <a class="btn-retro" href="<?= url('/coupons') ?>">Coupon Vault</a>
  </div>
</section>
