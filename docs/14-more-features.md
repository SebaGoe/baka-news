# 14 · More Features (v2 additions)

The app grew a set of newspaper staples and retro toys. All are wired, tested, and live in
the skeleton. This doc is the map + polish backlog for each.

## Newspaper sections
- **Baka-scopes** (`/horoscope`, `/horoscope/{sign}`) — 12 zodiac signs with absurd
  predictions (EN/JA). A "sign of the day" also shows in the rail. Data:
  `data/content/horoscopes.json`. *Polish:* per-sign lucky ticker symbol tie-in with the
  Exchange; a "generate my (fake) reading" input.
- **Classifieds** (`/classifieds`) — four sections (Wanted / For Sale / Lost & Found /
  Services), reader-submittable (`classifieds` table, CSRF, capped, auto-published). Seeded
  with jokes. *Polish:* per-section RSS; "featured classified"; light spam guard/honeypot.
- **Search** (`/search?q=`) — searches headline/dek/body across all three languages
  (`Content::search`). Ghost empty-state. *Polish:* highlight matches; filter by category.
- **RSS** (`/feed.xml`) — valid RSS 2.0 of the latest 20 stories; linked in `<head>` and the
  footer. *Polish:* per-category feeds.

## Retro toys
- **Baka Exchange ticker** — scrolling fake stock bar under the nav
  (`partials/ticker.php`, `data/content/exchange.json`). Pauses on hover. *Polish:* jitter
  prices per request; click a symbol → a joke "company page".
- **Reader Poll** — rail widget backed by `polls`/`poll_options`; AJAX vote reveals animated
  result bars; one vote per session + per browser (localStorage). *Polish:* rotate polls;
  admin to add polls.
- **Reactions** — emoji reaction bar on articles (`reactions` table, `POST /article/react`),
  optimistic bump animation, remembered per browser. *Polish:* show a "top reaction" chip on
  cards.
- **Absurd weather** — a forecast box in the masthead, stable per day (`weather.json`).
- **Surprise me** (`/random`) — masthead + article button jumps to a random story.

## Chrome & UX refinements
- **Night Edition** — dark theme via `data-theme="night"` on `<html>`, toggled in the
  masthead, persisted in localStorage, set pre-paint (no flash). Full token override +
  component fixups in `features.css`.
- **Retro sound** — off by default; a corner chip toggles it. WebAudio blips on
  clicks/votes, a coin on egg unlocks, a dial-up screech on the secret URL. Respects
  `prefers-reduced-motion` and the mute setting. `window.BakaSound`.
- **Achievements / egg hunt** — the footer shows `N/5 Eggs`; `features.js` listens for the
  `baka:egg` event (dispatched by `BakaEggs.unlock` in `main.js`) and tracks unique finds in
  localStorage, with an "Egg Master" modal near completion.
- **Print edition** — `@media print` strips the chrome and prints a clean single-column
  story; "🖨️ Print this edition" button on articles.
- **Printed-paper polish** — centre-fold shadow on wide screens, paper vignette, a rotated
  "LATE EDITION" ribbon, balanced headline wrapping.

## Where things live
```
data/content/{horoscopes,exchange,weather}.json
src/Content.php     search(), randomArticle(), horoscopes(), horoscope(), horoscopeOfTheDay(), exchange(), weather()
src/Db.php          reactions/polls/poll_options/classifieds + react(), reactionsFor(), activePoll(), votePoll(), classifieds()
src/Controllers/NewsController.php   search, random, react
src/Controllers/PageController.php   horoscope, horoscopeSign, classifieds, storeClassified, votePoll, rss
src/Views/pages/{horoscope,classifieds,search}.php
src/Views/partials/{ticker,poll}.php  (+ refined masthead, nav, marquee, sidebar, footer)
public/assets/css/features.css
public/assets/js/features.js
```

## Contracts touched
See doc 02 §4b (horoscope/exchange/weather JSON), §5 (new tables), §6 (new routes). All
additive — nothing in v1 changed shape, so v1 work and these coexist cleanly.

## Ownership for parallel work (extends doc 13)
- **Track A (content):** grow `horoscopes.json`, `exchange.json`, `weather.json`, classifieds
  seeds; write more polls.
- **Track B (design/reading):** Night Edition polish, print stylesheet, ticker/marquee feel,
  search UX, sound design.
- **Track C (fun/community):** reactions, poll rotation + admin, classifieds moderation,
  RSS per-category. Owns the new tables.
