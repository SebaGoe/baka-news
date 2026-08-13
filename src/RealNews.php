<?php
declare(strict_types=1);

namespace Baka;

/**
 * Real "news of the weird" — genuinely true but absurd stories.
 *
 * Two ingredients:
 *   1. LIVE feeds — many public odd-news RSS feeds (see FEEDS), refreshed
 *      into a snapshot on a timer so requests stay fast.
 *   2. ARCHIVE  — a hand-curated, Wikipedia-verified set of famous weird
 *      events spanning ~30 years (data/content/real-archive.json), so the
 *      section always has decades of "Florida Man"-grade history to browse.
 *
 * We only ever show a headline, a short blurb, the source, and a link OUT to
 * the original article — never a reproduced full story.
 */
final class RealNews
{
    /** Public odd/weird-news feeds. name => url. Add more to widen coverage. */
    private const FEEDS = [
        'UPI Odd News'      => 'https://rss.upi.com/news/odd_news.rss',
        'UPI Animal News'   => 'https://rss.upi.com/news/animal-news.rss',
        'CBS Strange News'  => 'https://www.cbsnews.com/latest/rss/strange',
        'Sky News Strange'  => 'https://feeds.skynews.com/feeds/rss/strange.xml',
        'Mirror Weird News' => 'https://www.mirror.co.uk/news/weird-news/?service=rss',
        'NPR Strange News'  => 'https://feeds.npr.org/1008/rss.xml',
        'Metro Weird'       => 'https://www.metro.co.uk/news/weird/feed/',
        'Oddity Central'    => 'https://feeds.feedburner.com/odditycentral',
        'CBC Offbeat'       => 'https://www.cbc.ca/webfeed/rss/rss-offbeat',
        'Daily Star Weird'  => 'https://www.dailystar.co.uk/news/weird-news/rss.xml',
        'Express Weird'     => 'https://www.express.co.uk/posts/rss/71/weird',
        'Boing Boing'       => 'https://boingboing.net/feed',
        'Laughing Squid'    => 'https://laughingsquid.com/feed/',
        'Atlas Obscura'     => 'https://www.atlasobscura.com/feeds/latest',
    ];
    /** Daily filler / hard-news that sometimes rides along in odd-news feeds. */
    private const SKIP_TITLE = '/^(On This Day|Famous birthdays|UPI Almanac|Watch Live|Live updates|Preview:)|\b(Putin|Zelensky|consulate|Indo-Pacific|Duma|Justice Department|Supreme Court|tariffs?|ceasefire|murder|murdered|indicted|manslaughter|homicide|shooting|stabb(?:ed|ing)|killed|rape|terror)\b/i';
    private const TTL = 1800; // 30 min between live refreshes

    private static ?array $memo = null;

    /** @return array<int,array<string,string>> newest first, live + archive merged */
    public static function items(int $limit = 40): array
    {
        if (self::$memo === null) {
            $snap = self::readJson(self::snapshotFile());
            self::$memo = $snap ?: self::merge([], self::archive());
        }
        return array_slice(self::$memo, 0, $limit);
    }

    public static function available(): bool
    {
        return !empty(self::items(1));
    }

    /** Distinct outlets currently represented (for the UI blurb). */
    public static function sourceCount(): int
    {
        $domains = [];
        foreach (self::items(500) as $it) {
            $domains[$it['domain'] ?? ''] = true;
        }
        unset($domains['']);
        return count($domains);
    }

    public static function needsRefresh(): bool
    {
        $f = self::snapshotFile();
        return !is_file($f) || (time() - filemtime($f)) > self::TTL;
    }

    /** Fetch every live feed, merge with the archive, write the snapshot. */
    public static function refresh(): int
    {
        $live = [];
        foreach (self::FEEDS as $name => $url) {
            $xml = self::httpGet($url);
            if ($xml !== null) {
                foreach (self::parse($xml, $name) as $it) {
                    $live[] = $it;
                }
            }
        }
        $all = self::merge($live, self::archive());
        if ($all) {
            @file_put_contents(self::snapshotFile(), json_encode($all, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            self::$memo = $all;
        }
        return count($all);
    }

    /** @return array<int,array<string,string>> the verified 30-year archive */
    public static function archive(): array
    {
        return self::readJson(BAKA_DATA . '/content/real-archive.json') ?: [];
    }

    // ---- internals ----

    private static function snapshotFile(): string
    {
        return rtrim(BAKA_STORAGE, '/') . '/real-news.json';
    }

    private static function merge(array $live, array $archive): array
    {
        $out = [];
        $seen = [];
        foreach (array_merge($live, $archive) as $it) {
            $key = mb_strtolower(trim($it['title'] ?? ''));
            if ($key === '' || isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = $it;
        }
        usort($out, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
        return $out;
    }

    private static function readJson(string $file): ?array
    {
        if (!is_file($file)) {
            return null;
        }
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) && $data ? $data : null;
    }

    private static function httpGet(string $url): ?string
    {
        $ctx = stream_context_create(['http' => [
            'method' => 'GET', 'timeout' => 6, 'follow_location' => 1,
            'header' => "User-Agent: Mozilla/5.0 (compatible; BakaNews/1.0; weird-news reader)\r\n",
        ], 'ssl' => ['verify_peer' => true, 'verify_peer_name' => true]]);
        $body = @file_get_contents($url, false, $ctx);
        return ($body !== false && $body !== '') ? $body : null;
    }

    /** @return array<int,array<string,string>> */
    private static function parse(string $xml, string $source): array
    {
        $prev = libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xml);
        libxml_use_internal_errors($prev);
        if ($doc === false || !isset($doc->channel->item)) {
            return [];
        }
        $out = [];
        foreach ($doc->channel->item as $item) {
            $title = trim(html_entity_decode((string) $item->title, ENT_QUOTES | ENT_HTML5));
            $link  = trim((string) $item->link);
            if ($title === '' || $link === '' || preg_match(self::SKIP_TITLE, $title)) {
                continue;
            }
            $blurb = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $item->description, ENT_QUOTES | ENT_HTML5))));
            $ts = strtotime((string) $item->pubDate) ?: time();
            $host = parse_url($link, PHP_URL_HOST) ?: '';
            $out[] = [
                'id'     => substr(md5($link), 0, 10),
                'title'  => $title,
                'blurb'  => mb_substr($blurb, 0, 240),
                'source' => $source,
                'domain' => preg_replace('/^www\./', '', (string) $host),
                'url'    => $link,
                'date'   => date('Y-m-d', $ts),
            ];
        }
        return $out;
    }
}
