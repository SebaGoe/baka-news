# 00 · Overview

## What Baka News is
A lovingly fake newspaper. Absurd, obviously-fictional news from around the world, each
story in its **original language + English + Japanese**, wrapped in a design that collides
an elegant broadsheet with a gaudy 1998 personal homepage. It is cute, silly, and never
mean. The mascot is a small sheet ghost, **Bakabake (バカバケ)** — a portmanteau of *baka*
(silly) + *obake* (ghost).

## Feature checklist (all scaffolded, ready to polish/expand)
- [x] Landing page: newspaper column grid, varied box sizes, drop caps, headlines
- [x] Categories, each article ≥1 category; category pages
- [x] Swipe left/right changes category (mobile)
- [x] Translations: original / EN / JA toggle, remembered in a cookie
- [x] Article detail + related articles
- [x] Coupon vault with weird coupons + modal + redeem + fake barcode + confetti
- [x] Easter eggs: Konami code, click-the-ghost, double-click counter, secret URL
- [x] Fake ads: in-feed, sidebar, banner + reader-submitted ads
- [x] Guest book (with moods + homepage links)
- [x] Visitor counter (LCD, retro, server-backed)
- [x] Webring: prev / next / random / join + paste-in badge
- [x] User homepages directory
- [x] Bakabake mascot: floats, peeks, talks, gives coupons
- [x] 404 page with ghost + retro design; error handling
- [x] Responsive mobile → tablet → desktop, max-width 1200px
- [x] Render deploy (Docker + blueprint)

## Tone rules (important)
- **Absurd, not plausible.** Talking cats, litigious chilis, tilting pyramids. Nothing that
  could be mistaken for real news. No real named public figures. No real orgs as targets.
- **Kind.** Punch at nothing. The humor is whimsy, not cruelty; avoid stereotypes of any
  country or group — the joke is the *absurd premise*, not the people.
- **Cute.** The ghost is friendly. Copy is playful, warm, a little dumb on purpose.

## Glossary
- **Bag** — a `{native, en, ja}` object for a translatable string.
- **Slot** — where an ad appears: `feed`, `sidebar`, `banner`.
- **Rarity / Unlock** — coupon visual weight and how it's revealed.
- **Rail** — the right-hand sidebar (counter, ads, webring, under-construction).
