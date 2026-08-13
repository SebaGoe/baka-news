# 01 · Architecture

## Folder map
```
public/                 web root (Render serves through server.php router)
  index.php             front controller: defines routes, dispatches
  assets/{css,js,img}   static assets (no build step)
src/
  bootstrap.php         autoloader + config + session + Db::init()
  Router.php            {param} routing, 404 fallback
  View.php              render('pages/x') wraps in layout; partial() doesn't
  Content.php           loads/queries JSON content (articles/categories/coupons/ads)
  Db.php                SQLite bootstrap, migrations, seeds, counter helpers
  helpers.php           e(), t(), current_lang(), url(), csrf_*, json_out()...
  Controllers/          one class per area (News, Coupon, Ads, Community, Page)
  Views/
    layout.php          <html> shell: masthead, nav, main+rail, footer, mascot
    partials/           masthead, nav, sidebar, ad, marquee, footer, mascot, webring-badge
    pages/              landing, article, coupons, ads, ads-submit, guestbook,
                        webring, user-pages, about, 404
data/
  content/*.json        fake content (version controlled)
  db/baka.sqlite        dynamic data (gitignored; mount a disk on Render to persist)
storage/                writable scratch (gitignored)
docs/                   this plan
server.php              php -S router (static passthrough → index.php)
Dockerfile, render.yaml deploy
```

## Request lifecycle
`server.php` → serves the file if it exists under `public/`, else → `public/index.php`
→ `bootstrap.php` (autoload, session, `Db::init()`) → `Router::dispatch()` matches method +
path → controller returns a string from `View::render(...)` → echoed.

## Conventions
- **PSR-4** namespace `Baka\` → `src/`. Controllers in `Baka\Controllers\`.
- **Escape on output** with `e()`. Never echo user data raw.
- **Translations** via `t($bag)` / `current_lang()`. Never hardcode a language in a view.
- **No framework, no Composer required** at runtime (a fallback autoloader lives in
  `bootstrap.php`). `composer.json` exists only for optional PSR-4/optimized autoload.
- **Views are plain PHP templates.** Logic stays thin; heavy lifting in Content/Db.
- **Sharp corners** (`--radius: 0`) — it's a newspaper.
- Add a route in `index.php`, a method in a controller, a template in `Views/pages/`.

## What each session touches (see doc 13 for the full split)
- **A (content+data):** `data/content/*`, `Content.php`, content-authoring.
- **B (reading+design):** `assets/css/*`, `Views/{layout,partials,pages/{landing,article}}`, mascot, 404.
- **C (community+fun):** coupons, ads, guestbook, webring, user pages, counter + their controllers/views/js.

Contract between them = **doc 02**. Freeze it before starting.
