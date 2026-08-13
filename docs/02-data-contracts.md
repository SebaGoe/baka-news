# 02 · Data Contracts  ⚠️ FREEZE THIS FIRST

Every parallel session reads and writes against these shapes. **Do not change field names
without updating this file and pinging the other sessions.** Add fields freely (additive is
safe); rename/remove only by consensus.

Content lives in version-controlled JSON (`/data/content/*.json`). Dynamic/user data lives
in SQLite (`/data/db/baka.sqlite`, schema in `src/Db.php`).

---

## 1. Article (`data/content/articles.json` → `articles[]`)

Every translatable field is a **bag**: `{ "native": "...", "en": "...", "ja": "..." }`.
The `t($bag)` helper picks the reader's language and falls back `native → en`.

```jsonc
{
  "id": "cat-mayor-jp",           // unique slug, [a-z0-9-]; used in /article/{id}
  "country": "Japan",             // display name
  "flag": "🇯🇵",                   // emoji flag
  "lang": "ja",                   // ISO code of the ORIGINAL language
  "categories": ["politics", "animals"],  // >=1 category slug (see categories.json)
  "size": "lead",                 // lead | feature | standard | brief  (layout weight)
  "date": "2026-08-12",           // YYYY-MM-DD, controls sort (newest first)
  "author": "田中・ぶんきち記者",     // byline (can be a joke name in any language)
  "headline": { "native": "...", "en": "...", "ja": "..." },
  "dek":      { "native": "...", "en": "...", "ja": "..." },  // subhead / one-liner
  "body": {                       // each language = ARRAY of paragraph strings
    "native": ["¶1", "¶2", "¶3"],
    "en":     ["¶1", "¶2", "¶3"],
    "ja":     ["¶1", "¶2", "¶3"]
  }
}
```

**Rules**
- `size` distribution keeps the front page interesting: aim ~1 `lead`, a few `feature`,
  many `standard`/`brief` per category.
- First paragraph of `body` doubles as the card lead + gets a drop cap — make it land.
- Keep all three languages present. If you only have the original, still fill `en`+`ja`.

Query API (already implemented in `src/Content.php`): `articles()`, `article($id)`,
`articlesByCategory($slug)`, `related($article, $limit)`.

**Importing more articles = drop in a file.** `Content::articles()` globs and merges
**every** `data/content/articles*.json` file (each with the same `{ "articles": [...] }`
shape), de-duplicating by `id` and sorting by `date` desc. So `articles.json` (the current
set) and `articles-backlog.json` (the 30-year archive, 1996–2025) both load automatically.
To add your own batch, save it as e.g. `articles-2026-q3.json` and it appears instantly — no
code change, no migration. Keep `id`s unique across all files.

---

## 2. Category (`data/content/categories.json` → `categories[]`)

```jsonc
{
  "slug": "animals",              // unique, used in /category/{slug} and article.categories
  "name_en": "Animal Affairs",
  "name_ja": "動物のお仕事",
  "emoji": "🐾",
  "color": "#27ae60",             // accent used in nav, section head, card cat chip
  "blurb_en": "The beasts are running things now.",
  "blurb_ja": "もう動物が仕切ってます。"
}
```

Order in the file = order in the nav = swipe order.

---

## 3. Coupon (`data/content/coupons.json` → `coupons[]`)

```jsonc
{
  "id": "free-article",           // unique
  "rarity": "common",             // common | rare | legendary  (visual treatment)
  "unlock": "open",               // open | konami | mascot-clicks | hidden-spot | secret-url
  "emoji": "📰",
  "title_en": "One (1) Free News Article",  "title_ja": "無料ニュース記事1本",
  "desc_en": "...",  "desc_ja": "...",
  "fine_print_en": "...",  "fine_print_ja": "..."   // shown small in the modal
}
```

**`unlock` semantics** (engine in `public/assets/js/main.js`, see doc 06):
- `open` — always visible, tap to redeem.
- `konami` — revealed by the Konami code.
- `mascot-clicks` — revealed after clicking Bakabake 7×.
- `hidden-spot` — revealed by double-clicking the visitor counter.
- `secret-url` — revealed by visiting any page with `?ghost=1`.

Redeem endpoint: `POST /coupons/redeem {id}` → JSON `{ok, barcode, serial, message}`.
Session remembers redeemed ids (`$_SESSION['redeemed']`).

---

## 4. Ad (`data/content/ads.json` → `ads[]`)

```jsonc
{
  "id": "y2k-spray",
  "slot": "feed",                 // feed (between articles) | sidebar (rail) | banner
  "emoji": "🪳",
  "bg": "#ffef9f",                // card background (loud is good)
  "title_en": "...", "title_ja": "...",
  "body_en": "...",  "body_ja": "...",
  "cta_en": "SPRAY NOW", "cta_ja": "今すぐ噴射"
}
```

`Content::ads($slot)` filters by slot. `Content::feedAd($i)` cycles feed ads for injection
(landing injects one every 4 articles).

---

## 4b. Horoscopes / Exchange / Weather (static JSON)

`data/content/horoscopes.json → horoscopes[]`
```jsonc
{ "sign": "aries", "symbol": "♈", "name_en": "Aries", "name_ja": "おひつじ座",
  "en": "…absurd prediction…", "ja": "…" }
```
`Content::horoscopes()`, `horoscope($sign)`, `horoscopeOfTheDay()` (stable per calendar day).

`data/content/exchange.json → exchange[]` — the fake ticker.
```jsonc
{ "symbol": "GHST", "name_en": "Ghost Coin", "name_ja": "…", "price": 66.6, "change": 6.66 }
```
`Content::exchange()`. Positive `change` renders green ▲, negative red ▼.

`data/content/weather.json → weather[]` — absurd forecasts, one shown per day.
```jsonc
{ "icon": "☀️", "en": "Aggressively sunny…", "ja": "…" }
```
`Content::weather()` returns the day's pick for the masthead box.

## 5. SQLite (dynamic data) — schema in `src/Db.php::init()`

Tables (all auto-created on boot; seeded so nothing is ever empty):

| table | columns |
|-------|---------|
| `guestbook` | id, name, message, mood, homepage, created_at |
| `counters` | key (PK), value — row `visits` seeded at 1337 |
| `webring` | id, site_name, url, description, approved, created_at |
| `user_pages` | id, title, url, blurb, badge, approved, created_at |
| `submitted_ads` | id, title, body, emoji, approved, created_at |
| `reactions` | article_id, emoji, count — PK(article_id, emoji); `Db::react()` / `reactionsFor()` |
| `polls` | id, question_en, question_ja, active |
| `poll_options` | id, poll_id, label_en, label_ja, votes — `Db::activePoll()` / `votePoll()` |
| `classifieds` | id, section, title, body, contact, approved, created_at — `Db::classifieds()` |

**`approved` defaults to 1** (auto-publish) for a friction-free demo. If you add moderation
later, flip the default to 0 and add an admin view — that's the only change needed.

All user input is length-capped and escaped with `e()` on output. URLs validated with
`filter_var(..., FILTER_VALIDATE_URL)`. CSRF via `csrf_field()` / `csrf_check()`.

---

## 6. Routes (registered in `public/index.php`)

```
GET  /                         landing (all categories)
GET  /category/{slug}          category view
GET  /article/{id}             article detail + related
GET  /coupons                  coupon vault
POST /coupons/redeem           → JSON
GET  /ads                      ad gallery + reader ads
GET  /ads/submit  POST /ads/submit
GET  /guestbook   POST /guestbook
GET  /webring     POST /webring/join
GET  /webring/next|prev|random (302 redirects; next/prev take ?from=)
GET  /submit-page POST /submit-page
GET  /counter.json             → JSON (bumps once per session)
GET  /search?q=                full-text-ish article search
GET  /random                   302 → a random article
POST /article/react            → JSON {ok, counts}  (emoji reaction)
GET  /horoscope  /horoscope/{sign}
GET  /classifieds  POST /classifieds
POST /poll/vote                → JSON {ok, poll}
GET  /feed.xml                 RSS 2.0
GET  /about
*                              → 404 (ghost page)
```

Translation language is global via `?lang=native|en|ja`, remembered in a cookie
(`current_lang()` helper).
