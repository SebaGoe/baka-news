<?php
/**
 * Baka News health check. Run: php tools/check.php
 * Fails (exit 1) if any emoji sneaks into the shipped site, or any content
 * JSON is invalid. Handy in CI or a pre-commit hook.
 */
declare(strict_types=1);
$root = dirname(__DIR__);

function isEmoji(int $o): bool {
    return ($o >= 0x2600 && $o <= 0x27BF) || ($o >= 0x1F000 && $o <= 0x1FAFF)
        || ($o >= 0x1F1E6 && $o <= 0x1F1FF) || ($o >= 0xFE00 && $o <= 0xFE0F)
        || ($o >= 0x2B00 && $o <= 0x2BFF) || ($o >= 0x2190 && $o <= 0x21FF)
        || ($o >= 0x2300 && $o <= 0x23FF) || ($o >= 0x25A0 && $o <= 0x25FF)
        || ($o >= 0x2580 && $o <= 0x259F) || ($o >= 0x2460 && $o <= 0x24FF)
        || in_array($o, [0x203C, 0x2049, 0x2122, 0x2139, 0x24C2, 0x200D], true);
}

$problems = [];
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($rii as $f) {
    $path = $f->getPathname();
    if (preg_match('#/(vendor|\.git|data/db|docs)/#', $path)) continue;
    // External third-party news text (real edition) isn't our shipped content.
    if (preg_match('#/real-(news-seed|archive)\.json$#', $path)) continue;
    $ext = strtolower($f->getExtension());
    if (!in_array($ext, ['php', 'js', 'css', 'json'], true)) continue;
    $txt = (string) file_get_contents($path);
    if ($ext === 'json' && json_decode($txt) === null && json_last_error() !== JSON_ERROR_NONE) {
        $problems[] = "INVALID JSON: " . str_replace($root . '/', '', $path) . ' — ' . json_last_error_msg();
    }
    $len = mb_strlen($txt);
    for ($i = 0; $i < $len; $i++) {
        if (isEmoji(mb_ord(mb_substr($txt, $i, 1)))) {
            $problems[] = "EMOJI in " . str_replace($root . '/', '', $path) . " near: " . trim(mb_substr($txt, max(0, $i - 12), 25));
            break;
        }
    }
}

if ($problems) {
    fwrite(STDERR, "Baka check FAILED:\n - " . implode("\n - ", $problems) . "\n");
    exit(1);
}
echo "Baka check passed: no emoji, all JSON valid.\n";
