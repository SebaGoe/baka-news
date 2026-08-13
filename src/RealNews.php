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
    /** General (non-weird) feeds — kept ONLY when a story reads as genuinely weird. */
    private const FEEDS_GENERAL = [
        'The Local (Germany)' => 'https://www.thelocal.de/feeds/rss.php',
        'The Local (Europe)'  => 'https://www.thelocal.com/feeds/rss.php',
    ];

    /** Ordinary / heavy / sad news that isn't fun-weird — dropped everywhere. */
    private const SKIP_TITLE = '/^(On This Day|Famous birthdays|UPI Almanac|Watch Live|Live updates|Preview:)'
        . '|\b(Putin|Zelensky|Trump|Biden|Gaza|Israel|Ukraine|consulate|Indo-Pacific|Duma|Justice Department|Supreme Court'
        . '|tariffs?|ceasefire|election|senate|parliament|GDP|inflation|interest rate|recession|layoffs?'
        . '|murder|murdered|indicted|manslaughter|homicide|shooting|stabb(?:ed|ing)|killed|kills?|rape|terror|assault'
        . '|dies|died|death|dead|obituary|funeral|mourning|passes away|passed away|tribute|aged \d+|cancer'
        . '|haemorrhage|hemorrhage|hospital|hospitalised|hospitalized|ICU|critical condition|coma|stroke|heart attack'
        . '|retires?|retirement|retiring|steps down|resigns?|sentenced|jailed|prison|court hears)\b/i';

    /** Positive weird signal — REQUIRED for a story to make the cut. Silly beats merely unusual. */
    private const WEIRD_MATCH = '/\b(bizarre|weird|strange|strangest|odd|oddest|unusual|freak|freakish|surreal|wacky|quirky'
        . '|myster|weirdest|craziest|wildest|unbelievab|you won\'?t believe|jaw[- ]dropping|baffl|stunned|astonish|shock'
        . '|record[- ]breaking|world record|guinness|world\'?s (?:largest|smallest|biggest|oldest|heaviest|tallest|longest|most)'
        . '|giant|massive|enormous|tiny|miniature|escape[ds]?|escaped|on the loose|stuck|trapped|rescued?|accidentally'
        . '|prank|goes viral|viral|ridiculous|hilarious|comical|absurd|florida man|drunk|boozy|naked|nude|underwear|pants'
        . '|toilet|loo|poo|poop|fart|smell|stink|sewage|manure|ghost|haunted|spooky|alien|ufo|bigfoot|yeti|nessie|loch ness'
        . '|conspiracy|psychic|fortune[- ]teller|lottery|jackpot|wins \S+ prize|unearth(?:ed)?|buried|treasure|hoard'
        . '|two[- ]headed|mutant|shaped like|looks like|resembl|dressed as|costume|cosplay|inflatable|rubber duck|gnome'
        . '|swallow(?:ed|s)?|ate|eats|bitten|attacked by|chased by|invaded by|infest|swarm|refuses?|demands?|elected'
        . '|mayor|crowned|world\'?s ugliest|contest|championship of|festival of|giant vegetable|pumpkin|marrow'
        . '|zoo|monkey|penguin|octopus|alligator|crocodile|goat|emu|llama|sloth|otter|raccoon|squirrel|pigeon|seagull'
        . '|hamster|tortoise|turtle|python|snake|spider|parrot|kangaroo|koala|wombat|capybara|flamingo|peacock|ostrich'
        . '|cheese|pizza|banana|sausage|beer|curry|nugget|sandwich|meatball)\b/i';

    private const TTL = 1800; // 30 min between live refreshes

    private static ?array $memo = null;

    /** @return array<int,array<string,string>> newest first, live + archive merged */
    public static function items(int $limit = 40): array
    {
        if (self::$memo === null) {
            // Live snapshot (ephemeral) -> committed seed -> archive-only.
            $snap = self::readJson(self::snapshotFile()) ?: self::readJson(self::seedFile());
            self::$memo = $snap ? self::merge($snap, []) : self::merge([], self::archive());
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
        $deadline = microtime(true) + 14; // never block a page render for long
        // Every live story must pass the weirdness gate; only the curated
        // archive is exempt. Weird-section and general feeds alike are filtered.
        foreach ([self::FEEDS, self::FEEDS_GENERAL] as $set) {
            foreach ($set as $name => $url) {
                if (microtime(true) > $deadline) {
                    break 2;
                }
                $xml = self::httpGet($url);
                if ($xml !== null) {
                    foreach (self::parse($xml, $name) as $it) {
                        $live[] = $it;
                    }
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

    /** Committed fallback so real news always shows, even before a live fetch. */
    public static function seedFile(): string
    {
        return BAKA_DATA . '/content/real-news-seed.json';
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
            if (empty($it['category'])) {
                $it['category'] = self::categorize(($it['title'] ?? '') . ' ' . ($it['blurb'] ?? ''));
            }
            $seen[$key] = true;
            $out[] = $it;
        }
        usort($out, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
        return $out;
    }

    /** Bucket a story into one of the site's categories by keyword. */
    private static function categorize(string $text): string
    {
        $t = mb_strtolower($text);
        $map = [
            'animals'  => '/\b(animal|zoo|dog|puppy|cat|kitten|cow|pig|horse|sheep|goat|duck|chicken|bird|fish|shark|whale|dolphin|seal|bear|fox|deer|moose|snake|python|spider|frog|bee|wasp|monkey|penguin|octopus|alligator|crocodile|emu|llama|sloth|otter|raccoon|squirrel|pigeon|seagull|hamster|tortoise|turtle|parrot|kangaroo|koala|wombat|capybara|flamingo|peacock|ostrich|pet|zoo)\b/',
            'food'     => '/\b(food|pizza|burger|sandwich|cheese|chocolate|beer|wine|coffee|curry|nugget|sausage|banana|cake|recipe|restaurant|chef|mcdonald|kfc|taco|meatball|pancake|pumpkin|vegetable|fruit)\b/',
            'tech'     => '/\b(ai|robot|app|iphone|android|google|facebook|tiktok|youtube|computer|internet|crypto|bitcoin|drone|gadget|software|video game|gamer|website|smartphone|viral video)\b/',
            'science'  => '/\b(space|nasa|scientist|study|research|planet|moon|mars|dinosaur|fossil|dna|experiment|telescope|comet|meteor|quantum|physics|asteroid|galaxy|lab)\b/',
            'politics' => '/\b(mayor|election|elected|government|council|president|minister|senate|parliament|vote|law|banned|ban\b|court|policy|official|candidate)\b/',
        ];
        foreach ($map as $slug => $re) {
            if (preg_match($re, $t)) {
                return $slug;
            }
        }
        // Travel/place stories lean "world"; everything else is pure "weird".
        return preg_match('/\b(country|island|village|town|city|border|tourist|travel|abroad|nation)\b/', $t) ? 'world' : 'weird';
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

    /** Pull a lead image from an RSS item: media:content/thumbnail, enclosure, or inline <img>. */
    private static function extractImage(\SimpleXMLElement $item, string $rawDesc): string
    {
        $media = $item->children('http://search.yahoo.com/mrss/');
        foreach (['content', 'thumbnail'] as $tag) {
            if (isset($media->$tag)) {
                foreach ($media->$tag as $node) {
                    $u = (string) ($node->attributes()['url'] ?? '');
                    if ($u !== '' && preg_match('/\.(jpe?g|png|webp|gif)/i', $u)) {
                        return $u;
                    }
                }
            }
        }
        if (isset($item->enclosure)) {
            $type = (string) ($item->enclosure->attributes()['type'] ?? '');
            $u = (string) ($item->enclosure->attributes()['url'] ?? '');
            if ($u !== '' && (str_starts_with($type, 'image') || preg_match('/\.(jpe?g|png|webp|gif)/i', $u))) {
                return $u;
            }
        }
        $content = (string) $item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
        $hay = $content !== '' ? $content : $rawDesc;
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', html_entity_decode($hay, ENT_QUOTES | ENT_HTML5), $m)) {
            return $m[1];
        }
        return '';
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
            $rawDesc = (string) $item->description;
            $blurb = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($rawDesc, ENT_QUOTES | ENT_HTML5))));
            // Weirdness gate: keep only genuinely silly/absurd stories.
            if (!preg_match(self::WEIRD_MATCH, $title . ' ' . mb_substr($blurb, 0, 160))) {
                continue;
            }
            $ts = strtotime((string) $item->pubDate) ?: time();
            $host = parse_url($link, PHP_URL_HOST) ?: '';
            $out[] = [
                'id'       => substr(md5($link), 0, 10),
                'title'    => $title,
                'blurb'    => mb_substr($blurb, 0, 240),
                'source'   => $source,
                'domain'   => preg_replace('/^www\./', '', (string) $host),
                'url'      => $link,
                'date'     => date('Y-m-d', $ts),
                'image'    => self::extractImage($item, $rawDesc),
                'category' => self::categorize($title . ' ' . $blurb),
            ];
        }
        return $out;
    }
}
