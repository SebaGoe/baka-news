# 13 · Parallel Worklog — run 2–3 Claude Code sessions at once

The repo is split so three sessions can work with **near-zero file collisions**. The only
shared surface is **doc 02 (data contracts)** and `data/content/*.json` — freeze the shapes
before anyone starts, and coordinate JSON edits (see "Content merges" below).

## Step 0 — freeze (do together, 5 min)
1. All sessions read **doc 02**. Agree on the field names as-is (they're already implemented).
2. Agree that **additive** changes are free; **renames** need a ping.
3. Each session works on its own git branch: `track-a`, `track-b`, `track-c`. Merge to
   `main` often (the tracks touch different folders, so merges are clean).

---

## Track A — Content & Data 📚
**Owns:** `data/content/*.json`, `src/Content.php`, docs 02 & 11.
**Goal:** a lively, funny, trilingual paper.

- [ ] Grow `articles.json` from 20 → ~50, balanced across categories and `size`s (doc 11).
- [ ] Keep every article's `native/en/ja` filled; verify JSON stays valid after each add
      (`php -r 'json_decode(file_get_contents("data/content/articles.json"),true)?:print(json_last_error_msg());'`).
- [ ] Expand `coupons.json` (idea bank in doc 06); ensure a good spread of `rarity`/`unlock`.
- [ ] Expand `ads.json` (idea bank in doc 07) across `feed`/`sidebar`/`banner`.
- [ ] Tune category `color`/`blurb`; make sure each category has a `lead` or `feature`.
- [ ] (Optional) extend `Content.php` queries (smarter `related()`, a "trending" pick) —
      **keep method signatures** so Tracks B/C don't break.

## Track B — Reading Experience & Design 🎨
**Owns:** `assets/css/*`, `assets/js/{swipe,mascot}.js`, `Views/layout.php`,
`Views/partials/{masthead,nav,marquee,footer,mascot,ad,sidebar}.php`,
`Views/pages/{landing,article,about,404}.php`, `assets/img/*`. Docs 03, 04, 05, 09, 10.
**Goal:** the broadsheet-meets-90s look sings; the ghost delights; it's flawless on mobile.

- [ ] Polish the newspaper grid + masthead; verify all four `size`s tile well at 320 / 640 /
      1000 / 1200px.
- [ ] Elevate the masthead clash and one restrained load animation (respect reduced-motion).
- [ ] Mascot: contextual speech per page, optional rare pose, keyboard/drag polish (doc 09).
- [ ] Swipe: optional AJAX slide with `history.pushState`, no-JS fallback intact (doc 05).
- [ ] Add the **500 page** + exception handler with `BAKA_DEBUG` switch (doc 10).
- [ ] Cross-browser + a11y pass: focus rings, contrast, skip link, `prefers-reduced-motion`.

## Track C — Fun & Community ⚙️
**Owns:** `src/Controllers/{Coupon,Ads,Community}.php`, `assets/js/{main,modal,counter}.js`,
`Views/pages/{coupons,ads,ads-submit,guestbook,webring,user-pages}.php`,
`Views/partials/webring-badge.php`, `src/Db.php`. Docs 06, 07, 08.
**Goal:** every interactive toy feels good and can't be abused.

- [ ] Coupons: collection progress meter, redeem sound w/ mute, legendary sparkle (doc 06).
- [ ] Easter eggs: confirm all five unlocks fire; add one more mechanic if inspired.
- [ ] Ads: reader-submit flow polish, optional banner strip, "your ad here" filler.
- [ ] Community: light moderation option (`approved=0` + `/admin` + `BAKA_ADMIN_TOKEN`),
      honeypot + basic spam/profanity guard on all forms (doc 08).
- [ ] Counter roll-up + webring hops verified; add "featured page of the day" in rail.
- [ ] If persistence is wanted, coordinate the Render disk with whoever deploys (doc 12).

---

## Interfaces the tracks rely on (don't break these)
- `t($bag)`, `current_lang()`, `e()`, `url()`, `csrf_field()/csrf_check()`, `json_out()` — helpers.
- `Content::{articles, article, articlesByCategory, related, coupons, coupon, ads, feedAd, categories, category}`.
- `Db::{pdo, init, bumpVisits, visits}` and the table columns in doc 02 §5.
- `View::render('pages/x', $data)` / `View::partial('partials/x', $data)`.
- JS globals: `window.BakaModal.open({bar, html})`, `window.BakaEggs.unlock(kind, message)`.
- Routes in doc 02 §6. Adding a route is safe; changing a path needs a ping.

## Content merges (the one real coordination point)
`data/content/*.json` is the shared file. To avoid merge pain:
- Track A owns it. B/C request content via a quick note rather than editing JSON directly.
- Or: append-only during parallel work (add objects at the end of the array), then let A
  do a final ordering/dedupe pass.

## Definition of done
- [ ] `php -S localhost:8000 server.php` runs with **zero** warnings in the log.
- [ ] All routes in doc 02 §6 return 200 (or 302 for hops, 404 for the ghost page).
- [ ] All JSON validates; every article has native/en/ja.
- [ ] Looks right at 320 / 768 / 1200px; keyboard-navigable; reduced-motion respected.
- [ ] Deploys clean on Render from the blueprint (doc 12).

## Suggested prompt to open each session
> "Read `/docs/13-parallel-worklog.md` and `/docs/02-data-contracts.md`, then work **Track
> B** only. The app already runs (`php -S localhost:8000 server.php`) — extend and polish the
> files Track B owns, keep every interface in doc 02 stable, and don't touch Track A/C
> files. Check your work against the Definition of Done."
