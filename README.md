# 🗞️ Baka News

**The world's least reliable newspaper.** 100% fake, 100% cute. Absurd fake news from
around the world (shown in the original language + English + Japanese), weird coupons,
fake ads, horoscopes, classifieds, a fake stock ticker, reader polls, emoji reactions,
search, an RSS feed, a Night Edition dark mode, a guest book, a visitor counter, a webring,
and a small sheet ghost named **Bakabake** who haunts the pages and hands out coupons.

Aesthetic thesis: **an elegant broadsheet newspaper colliding with a gaudy 1998 GeoCities
homepage.** The newsprint is the paper; the 90s web junk (marquee, beveled buttons, LCD
visitor counter, blinking "under construction", webring badge) is the frame. The clash +
the ghost is the signature.

---

## This repo is BOTH a plan and a working app

Everything in `/docs` is the implementation plan for Claude Code. But the repo is **already
a runnable skeleton** — the landing page, category/swipe, article detail, coupons +
easter eggs, ads, guest book, counter, webring, user pages, mascot, and 404 all work
today. Claude Code's job is to **polish, expand content, and add flourish**, not to build
from zero. Every doc says clearly what already exists vs. what to add.

**Now with a 30-year archive:** 50 stories in total — the current desk plus a decade-spanning
backlog (`articles-backlog.json`, one absurd headline per year 1996–2025) that the app
auto-loads. Drop in any `articles-*.json` and it imports instantly (see docs 02 & 11).

**Wrapped in a Netscape browser** (working address bar + menus + throbber), with
meme-number Easter eggs (type `42`, `67`, `1337`, …) and a **Whack-a-Ghost** arcade
at `/arcade`. `php tools/check.php` guards against emoji regressions.

## Run it locally (30 seconds)

```bash
# needs PHP 8.2+ with pdo_sqlite + mbstring
php -S localhost:8000 server.php
# open http://localhost:8000
```

No database server, no build step, no `composer install` required (there's a tiny built-in
autoloader). SQLite creates itself on first run.

## Deploy today

See **[docs/12-deploy-render.md](docs/12-deploy-render.md)** — push to GitHub, connect the
`render.yaml` blueprint, done in ~5 minutes.

## Read the plan in this order

| # | Doc | What it covers |
|---|-----|----------------|
| 00 | [overview](docs/00-overview.md) | Vision, tone, feature list, glossary |
| 01 | [architecture](docs/01-architecture.md) | Folders, routing, request lifecycle, conventions |
| 02 | [**data-contracts**](docs/02-data-contracts.md) | ⚠️ **Freeze this first.** JSON + SQLite schemas every session shares |
| 03 | [design-system](docs/03-design-system.md) | Palette, type, tokens, responsive rules |
| 04 | [layout-newspaper](docs/04-layout-newspaper.md) | Masthead, column grid, cards, drop caps |
| 05 | [pages](docs/05-pages.md) | Landing, category, swipe, article detail, related |
| 06 | [coupons-easter-eggs](docs/06-coupons-easter-eggs.md) | Coupon vault, modal, unlock mechanics, idea bank |
| 07 | [ads](docs/07-ads.md) | Ad slots, gallery, reader-submitted fake ads |
| 08 | [community](docs/08-community.md) | Guest book, visitor counter, webring, homepages |
| 09 | [mascot](docs/09-mascot.md) | Bakabake: SVG, behaviors, speech, coupon-giving |
| 10 | [error-404](docs/10-error-404.md) | 404 + error handling with the ghost |
| 11 | [content-pack](docs/11-content-pack.md) | How to write the ridiculous news + translations |
| 12 | [deploy-render](docs/12-deploy-render.md) | Step-by-step Render deploy |
| 13 | [**parallel-worklog**](docs/13-parallel-worklog.md) | 🧑‍🤝‍🧑 3-session split, task checklists, integration |
| 14 | [more-features](docs/14-more-features.md) | Horoscopes, classifieds, search, ticker, polls, reactions, RSS, Night Edition, sound, print |

## Tech stack

Vanilla **PHP 8.2** (no framework, tiny hand-rolled router + autoloader) · **SQLite** for
dynamic data · **JSON** files for fake content · **vanilla CSS + JS** (no build) · Docker
on **Render**.

> ⚠️ Everything here is satire. Stories are deliberately, obviously fictional (talking
> cats, litigious chilis). Keep new content clearly absurd so it can never be mistaken for
> real disinformation, and keep it kind — silly, never mean.
