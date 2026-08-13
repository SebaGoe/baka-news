# 08 · Community (guest book · counter · webring · homepages)

The whole "old web" social layer. Files: `src/Controllers/CommunityController.php`,
`Views/pages/{guestbook,webring,user-pages}.php`, `Views/partials/{sidebar,webring-badge}.php`,
`assets/js/counter.js`, tables in `src/Db.php`.

## Guest book (`/guestbook`)
Sign with a **mood emoji**, name, optional homepage link, and message. Newest 100 shown as
retro cards. CSRF-protected, escaped, length-capped, homepage URL-validated. Seeded empty
with a friendly "be the first" ghost prompt.

## Visitor counter
LCD-style digits in the rail + a live number in the marquee. `counter.js` calls
`GET /counter.json` once per session, which bumps `counters.visits` (seeded at **1337**) and
returns the value; the digits **roll up** to it. Double-clicking the counter unlocks a
secret coupon (see doc 06).

> On Render's free plan the SQLite file is ephemeral, so the counter resets on redeploy —
> which is honestly on-brand ("visitors since forever ago"). Attach a disk to persist
> (doc 12).

## Webring (`/webring`)
A ring of pointless sites. Controls: **◀ prev / 🎲 random / next ▶** (server-side 302 hops
through the member list via `?from=`). List of members + a **join** form + a paste-in badge
snippet. Seeded with a few joke members so the ring is never empty. New joins auto-publish.

The rail also carries a compact **webring badge** with the same prev/random/next controls.

## User homepages (`/submit-page`)
A link wall where visitors pin their personal page: badge emoji, title, URL, blurb.
Auto-published, escaped, URL-validated. This is the "add your page" directory.

## Moderation note
Everything auto-publishes (`approved = 1`) for a frictionless demo. To add light moderation:
default `approved` to 0 in `Db.php`, and add an `/admin` view gated by a
`BAKA_ADMIN_TOKEN` env var that lists unapproved rows with approve/delete. Consider a simple
profanity/URL-spam filter and a honeypot field on the forms.

## Polish (session C)
- Guest book "mood weather" summary ("today's vibe: mostly 👻 with a chance of 🦖").
- Webring: verify a joining site actually links back (fetch + check) before approving.
- Homepages: random "featured page of the day" in the rail.
