<?php
declare(strict_types=1);
/**
 * Build data/content/real-archive.json — a 30-year archive of REAL but absurd
 * events, each verified against Wikipedia (canonical title, URL, and one-line
 * summary pulled live). Anything that doesn't resolve to a real article is
 * dropped, so every shipped item links to a genuine, checkable source.
 *
 * Run once (or whenever you edit the list):  php tools/build_real_archive.php
 */
$ROOT = dirname(__DIR__);
$UA = 'BakaNews/1.0 (weird-but-true archive; contact goerick.sebastian@gmail.com)';

// [Wikipedia page title, event year, country/flavour tag]
$SEED = [
  ['Dolly (sheep)', 1996, 'UK'],
  ['Tickle Me Elmo', 1996, 'USA'],
  ['Tamagotchi', 1997, 'Japan'],
  ['Furby', 1998, 'USA'],
  ['Beanie Babies', 1999, 'USA'],
  ['Year 2000 problem', 1999, 'World'],
  ['Segway', 2001, 'USA'],
  ['Star Wars Kid', 2003, 'Canada'],
  ['Numa Numa', 2004, 'USA'],
  ['Leeroy Jenkins', 2005, 'USA'],
  ['Chuck Norris facts', 2005, 'USA'],
  ['Rickrolling', 2007, 'World'],
  ['Charlie Bit My Finger', 2007, 'UK'],
  ['Keyboard Cat', 2007, 'USA'],
  ['Chocolate Rain', 2007, 'USA'],
  ['Balloon boy hoax', 2009, 'USA'],
  ['Bed Intruder Song', 2010, 'USA'],
  ['Nyan Cat', 2011, 'USA'],
  ['Planking (fad)', 2011, 'Australia'],
  ['Grumpy Cat', 2012, 'USA'],
  ['Gangnam Style', 2012, 'South Korea'],
  ['Ecce Homo (Elías García Martínez)', 2012, 'Spain'],
  ['Florida Man', 2013, 'USA'],
  ['Harlem Shake (meme)', 2013, 'World'],
  ['Dogecoin', 2013, 'World'],
  ['Doge (meme)', 2013, 'Japan'],
  ['Ice Bucket Challenge', 2014, 'USA'],
  ['The dress', 2015, 'World'],
  ['Left Shark', 2015, 'USA'],
  ['Pizza Rat', 2015, 'USA'],
  ['Boaty McBoatface', 2016, 'UK'],
  ['Killing of Harambe', 2016, 'USA'],
  ['Tay (chatbot)', 2016, 'USA'],
  ['Damn Daniel', 2016, 'USA'],
  ['Baby Shark', 2016, 'South Korea'],
  ['Tide Pods', 2018, 'USA'],
  ['Yanny or Laurel', 2018, 'World'],
  ['Storm Area 51', 2019, 'USA'],
  ['Grogu', 2019, 'USA'],
  ['GameStop short squeeze', 2021, 'USA'],
  ['2021 Suez Canal obstruction', 2021, 'Egypt'],
  ['Bored Ape Yacht Club', 2021, 'World'],
];

function wiki(string $title, string $ua): ?array {
  $slug = str_replace(' ', '_', $title);
  $url = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($slug);
  $ch = curl_init($url);
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_USERAGENT=>$ua, CURLOPT_TIMEOUT=>20, CURLOPT_FOLLOWLOCATION=>true]);
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
  if ($code !== 200 || !$r) return null;
  $d = json_decode($r, true);
  if (!$d || ($d['type'] ?? '') === 'disambiguation') return null;
  $page = $d['content_urls']['desktop']['page'] ?? null;
  $extract = trim((string)($d['extract'] ?? ''));
  if (!$page || $extract === '') return null;
  $img = $d['originalimage']['source'] ?? $d['thumbnail']['source'] ?? '';
  return ['title'=>$d['title'] ?? $title, 'url'=>$page, 'extract'=>$extract, 'image'=>$img];
}

$out = [];
foreach ($SEED as [$title, $year, $tag]) {
  $w = wiki($title, $UA);
  if (!$w) { fwrite(STDERR, "  DROP (not found): $title\n"); continue; }
  $blurb = mb_substr($w['extract'], 0, 240);
  $out[] = [
    'id'     => substr(md5($w['url']), 0, 10),
    'title'  => $w['title'],
    'blurb'  => $blurb,
    'source' => 'Wikipedia archive',
    'domain' => 'en.wikipedia.org',
    'url'    => $w['url'],
    'date'   => sprintf('%04d-01-01', $year),
    'origin' => $tag,
    'image'  => $w['image'],
  ];
  printf("  ok %-40s %d\n", $w['title'], $year);
  usleep(120000); // be polite to the API
}

file_put_contents($ROOT.'/data/content/real-archive.json',
  json_encode($out, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n");
echo "Verified ".count($out)." / ".count($SEED)." archive items.\n";
