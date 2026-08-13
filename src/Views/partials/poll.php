<?php
use Baka\Db;
$poll = Db::activePoll();
if (!$poll) return;
$lang = current_lang();
$voted = !empty($_SESSION['voted_poll_' . $poll['id']]);
?>
<section class="rail__box poll" id="poll" data-poll-id="<?= (int) $poll['id'] ?>" data-voted="<?= $voted ? '1' : '0' ?>" aria-label="Reader poll">
  <div class="rail__box-title">Reader Poll</div>
  <p class="poll__q"><?= e($lang === 'ja' ? $poll['question_ja'] : $poll['question_en']) ?></p>
  <div class="poll__options">
    <?php foreach ($poll['options'] as $o):
      $pct = $poll['total'] > 0 ? round($o['votes'] / $poll['total'] * 100) : 0; ?>
      <button class="poll__opt" data-option="<?= (int) $o['id'] ?>">
        <span class="poll__label"><?= e($lang === 'ja' ? $o['label_ja'] : $o['label_en']) ?></span>
        <span class="poll__bar" style="--pct: <?= $pct ?>%" aria-hidden="true"></span>
        <span class="poll__pct"><?= $pct ?>%</span>
      </button>
    <?php endforeach; ?>
  </div>
  <p class="poll__total"><?= (int) $poll['total'] ?> votes &middot; results are legally meaningless</p>
</section>
