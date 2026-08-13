<?php
declare(strict_types=1);
/**
 * Generate public/assets/og.png (1200x630) — the social-share card.
 * Run once locally (needs GD + a serif TTF):  php tools/build_og.php
 */
$OUT = dirname(__DIR__) . '/public/assets/og.png';
$FONT_B = '/System/Library/Fonts/Supplemental/Georgia Bold.ttf';
$FONT_R = '/System/Library/Fonts/Supplemental/Georgia.ttf';
foreach ([$FONT_B, $FONT_R] as $f) {
    if (!is_file($f)) { fwrite(STDERR, "Missing font: $f\n"); exit(1); }
}

$W = 1200; $H = 630;
$im = imagecreatetruecolor($W, $H);
$paper = imagecolorallocate($im, 244, 239, 228);
$ink   = imagecolorallocate($im, 28, 27, 23);
$soft  = imagecolorallocate($im, 86, 80, 67);
$red   = imagecolorallocate($im, 158, 43, 37);
$blue  = imagecolorallocate($im, 27, 75, 143);
$sage  = imagecolorallocate($im, 223, 232, 207);
$sageL = imagecolorallocate($im, 155, 179, 126);
$white = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $paper);

// helper: centered ttf text, returns bbox height
$text = function (int $size, string $font, int $color, string $s, ?int $cx, int $y) use ($im, $W) {
    $bb = imagettfbbox($size, 0, $font, $s);
    $w = $bb[2] - $bb[0];
    $x = $cx === null ? (int)(($W - $w) / 2) : $cx - (int)($w / 2);
    imagettftext($im, $size, 0, $x, $y, $color, $font, $s);
    return $w;
};

// Newspaper rules top & bottom
imagefilledrectangle($im, 60, 54, $W - 60, 62, $ink);
imagefilledrectangle($im, 60, 70, $W - 60, 72, $ink);
imagefilledrectangle($im, 60, $H - 72, $W - 60, $H - 70, $ink);
imagefilledrectangle($im, 60, $H - 64, $W - 60, $H - 56, $ink);

// Masthead title
$text(94, $FONT_B, $ink, 'Baka News', null, 235);
// Tracked subtitle
$sub = 'T H E   W O R L D \' S   L E A S T   R E L I A B L E   N E W S P A P E R';
$text(21, $FONT_R, $soft, $sub, null, 300);

// Middle rule
imagefilledrectangle($im, 180, 340, $W - 180, 343, $soft);

// Tagline lines
$text(40, $FONT_B, $ink, 'Real weird news + invented nonsense', null, 415);
$text(30, $FONT_R, $soft, 'from all over the world  (100% fake, 100% cute)', null, 470);

// REAL / FAKE chips
$chip = function (int $x, string $label, int $bg) use ($im, $FONT_B, $white) {
    $bb = imagettfbbox(24, 0, $FONT_B, $label); $w = $bb[2] - $bb[0];
    imagefilledrectangle($im, $x, 520, $x + $w + 40, 566, $bg);
    imagettftext($im, 24, 0, $x + 20, 552, $white, $FONT_B, $label);
    return $w + 40;
};
$w1 = $chip(430, 'REAL', $blue);
$chip(430 + $w1 + 20, 'FAKE', $red);

// Little sheet ghost, top-right corner (clear of the title)
$gx = 1090; $gy = 165; $r = 60;
imagefilledellipse($im, $gx, $gy, $r * 2, $r * 2, $sage);
imagefilledrectangle($im, $gx - $r, $gy, $gx + $r, $gy + 78, $sage);
// wavy hem
for ($i = -2; $i <= 2; $i++) {
    imagefilledellipse($im, $gx + $i * 24, $gy + 78, 24, 24, $paper);
}
imagefilledellipse($im, $gx - 23, $gy - 4, 20, 28, $ink);
imagefilledellipse($im, $gx + 23, $gy - 4, 20, 28, $ink);

imagepng($im, $OUT, 6);
imagedestroy($im);
echo "Wrote " . $OUT . " (" . filesize($OUT) . " bytes)\n";
