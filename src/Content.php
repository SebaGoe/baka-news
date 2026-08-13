<?php
declare(strict_types=1);

namespace Baka;

/**
 * Loads and queries the static JSON content (articles, categories, coupons, ads).
 * Everything here is version-controlled fake content — no user data.
 */
final class Content
{
    private static array $cache = [];

    private static function load(string $name): array
    {
        if (!isset(self::$cache[$name])) {
            $file = BAKA_DATA . '/content/' . $name . '.json';
            $json = is_file($file) ? file_get_contents($file) : '{}';
            self::$cache[$name] = json_decode($json ?: '{}', true) ?: [];
        }
        return self::$cache[$name];
    }

    // ---- Categories ----
    public static function categories(): array
    {
        return self::load('categories')['categories'] ?? [];
    }

    public static function category(string $slug): ?array
    {
        foreach (self::categories() as $c) {
            if ($c['slug'] === $slug) {
                return $c;
            }
        }
        return null;
    }

    // ---- Articles ----
    public static function articles(): array
    {
        if (!isset(self::$cache['__articles'])) {
            $all = [];
            $seen = [];
            // Merge every content/articles*.json file — drop in a new file to import.
            foreach (glob(BAKA_DATA . '/content/articles*.json') ?: [] as $file) {
                $data = json_decode((string) file_get_contents($file), true) ?: [];
                foreach ($data['articles'] ?? [] as $a) {
                    $id = $a['id'] ?? null;
                    if ($id === null || isset($seen[$id])) {
                        continue; // skip dupes by id
                    }
                    $seen[$id] = true;
                    $all[] = $a;
                }
            }
            usort($all, fn($x, $y) => strcmp($y['date'] ?? '', $x['date'] ?? ''));
            self::$cache['__articles'] = $all;
        }
        return self::$cache['__articles'];
    }

    public static function articlesByCategory(string $slug): array
    {
        return array_values(array_filter(
            self::articles(),
            fn($a) => in_array($slug, $a['categories'] ?? [], true)
        ));
    }

    public static function article(string $id): ?array
    {
        foreach (self::articles() as $a) {
            if ($a['id'] === $id) {
                return $a;
            }
        }
        return null;
    }

    /** Pick a random article (for the "Surprise me" button). */
    public static function randomArticle(): ?array
    {
        $a = self::articles();
        return $a ? $a[array_rand($a)] : null;
    }

    /**
     * Full-text search across every article and every language.
     *
     * Covers headline, dek and body (native + English + Japanese) plus the
     * country, byline, publication date/year and section names — so queries
     * like "1998", "politics" or a reporter's name all find something.
     * Multi-word queries are AND-matched (every word must appear) and results
     * are ranked: headline hits outrank body hits.
     */
    public static function search(string $q): array
    {
        $q = trim($q);
        if ($q === '') {
            return [];
        }
        $tokens = array_values(array_filter(
            preg_split('/\s+/u', mb_strtolower($q)) ?: [],
            fn($t) => $t !== ''
        ));
        if (!$tokens) {
            return [];
        }

        // Map category slug -> searchable display names ("politics" / "Serious Politics").
        $catNames = [];
        foreach (self::categories() as $c) {
            $catNames[$c['slug']] = trim(
                ($c['slug'] ?? '') . ' ' . ($c['name_en'] ?? '') . ' ' . ($c['name_ja'] ?? '')
            );
        }

        $scored = [];
        foreach (self::articles() as $a) {
            $strong = mb_strtolower(self::flatten($a['headline'] ?? []) . ' ' . self::flatten($a['dek'] ?? []));
            $meta   = mb_strtolower(
                str_replace('-', ' ', (string) ($a['id'] ?? '')) . ' '
                . ($a['country'] ?? '') . ' ' . ($a['author'] ?? '') . ' '
                . ($a['date'] ?? '') . ' ' . mb_substr((string) ($a['date'] ?? ''), 0, 4) . ' '
                . implode(' ', array_map(fn($s) => $catNames[$s] ?? $s, $a['categories'] ?? []))
            );
            $weak = mb_strtolower(self::flatten($a['body'] ?? []));
            $all  = $strong . ' ' . $meta . ' ' . $weak;

            $score = 0;
            $matchedAll = true;
            foreach ($tokens as $tok) {
                if (!str_contains($all, $tok)) {
                    $matchedAll = false;
                    break;
                }
                if (str_contains($strong, $tok))      { $score += 5; }
                elseif (str_contains($meta, $tok))    { $score += 2; }
                else                                  { $score += 1; }
            }
            if ($matchedAll) {
                $scored[] = ['a' => $a, 's' => $score];
            }
        }

        // Rank by relevance; equal scores keep the date order from articles().
        usort($scored, fn($x, $y) => $y['s'] <=> $x['s']);
        return array_map(fn($r) => $r['a'], $scored);
    }

    /** Flatten a nested headline/dek/body structure into one searchable string. */
    private static function flatten($value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (!is_array($value)) {
            return (string) $value;
        }
        $out = [];
        array_walk_recursive($value, function ($leaf) use (&$out) {
            $out[] = (string) $leaf;
        });
        return implode(' ', $out);
    }

    /**
     * Resolve an illustration for an article. Uses an explicit `image` field
     * if the article provides one, otherwise falls back to a newspaper-style
     * plate for its first category. Always returns a usable public path.
     */
    public static function articleImage(array $article): string
    {
        if (!empty($article['image'])) {
            return $article['image'];
        }
        $slug = ($article['categories'] ?? [])[0] ?? '';
        $file = '/assets/img/news/' . $slug . '.svg';
        if ($slug !== '' && is_file(BAKA_PUBLIC . $file)) {
            return $file;
        }
        return '/assets/img/news/default.svg';
    }

    /** Related = shares >=1 category, excludes self, max $limit. */
    public static function related(array $article, int $limit = 4): array
    {
        $cats = $article['categories'] ?? [];
        $out = [];
        foreach (self::articles() as $a) {
            if ($a['id'] === $article['id']) {
                continue;
            }
            if (array_intersect($cats, $a['categories'] ?? [])) {
                $out[] = $a;
            }
            if (count($out) >= $limit) {
                break;
            }
        }
        return $out;
    }

    // ---- Coupons ----
    public static function coupons(): array
    {
        return self::load('coupons')['coupons'] ?? [];
    }

    public static function coupon(string $id): ?array
    {
        foreach (self::coupons() as $c) {
            if ($c['id'] === $id) {
                return $c;
            }
        }
        return null;
    }

    // ---- Ads ----
    public static function ads(?string $slot = null): array
    {
        $ads = self::load('ads')['ads'] ?? [];
        if ($slot === null) {
            return $ads;
        }
        return array_values(array_filter($ads, fn($a) => ($a['slot'] ?? '') === $slot));
    }

    /** Deterministic-ish ad for a feed position so layout is stable per request. */
    public static function feedAd(int $index): ?array
    {
        $feed = self::ads('feed');
        return $feed ? $feed[$index % count($feed)] : null;
    }

    // ---- Horoscopes ----
    public static function horoscopes(): array
    {
        return self::load('horoscopes')['horoscopes'] ?? [];
    }

    public static function horoscope(string $sign): ?array
    {
        foreach (self::horoscopes() as $h) {
            if ($h['sign'] === $sign) {
                return $h;
            }
        }
        return null;
    }

    /** A "sign of the day", stable per calendar day. */
    public static function horoscopeOfTheDay(): ?array
    {
        $signs = self::horoscopes();
        if (!$signs) {
            return null;
        }
        return $signs[(int) date('z') % count($signs)];
    }

    // ---- Baka Exchange (fake ticker) ----
    public static function exchange(): array
    {
        return self::load('exchange')['exchange'] ?? [];
    }

    // ---- Absurd weather, stable per day ----
    public static function weather(): array
    {
        $w = self::load('weather')['weather'] ?? [];
        if (!$w) {
            return ['en' => 'Yes', 'ja' => 'はい'];
        }
        return $w[((int) date('z') + (int) date('Y')) % count($w)];
    }
}
