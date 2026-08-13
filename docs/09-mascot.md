# 09 · Bakabake the Mascot 👻

Files: `public/assets/img/bakabake.svg`, `Views/partials/mascot.php`, `assets/js/mascot.js`,
`.mascot*` in `components.css`.

## Who
**Bakabake (バカバケ)** — a small glow-green **sheet ghost** in the style of the classic
Lego glow ghost: a rounded cloth dome with a little Lego-stud on top, a wavy hem, two big
dark eyes with shine, a tiny worried mouth, and faint blush. Friendly, shy, a bit dim,
deeply supportive.

## Behaviors (in `mascot.js`)
- **Idle float** in the bottom-right (CSS `float` keyframes; paused on hover).
- **Random peeks** — occasionally slides half off the right edge and back (`is-peeking`).
- **Speech bubbles** — greets on first load, and says a random cute line when clicked.
- **Click easter egg** — 7 clicks calls `BakaEggs.unlock('mascot-clicks', ...)` → reveals a
  secret coupon (doc 06).
- **Keyboard accessible** — focusable, Enter/Space activates.

## Where it appears
Rendered globally in `layout.php`, so it's on every page, including the 404. On the 404 and
in coupon celebrations it's front and center.

## To extend (session B)
- **Contextual lines per page** (pass a page key to `mascot.js`): on `/coupons` it hypes
  coupons, on `/guestbook` it asks you to sign, on `/webring` it waves at other sites.
- **Rare "spooky" pose** at a low random chance (swap to a `bakabake-spooky.svg`).
- **Drag me** — let users fling the ghost around (respect reduced-motion; snap back).
- Keep it tasteful: one ghost, one personality. Don't spawn a swarm (except maybe as a
  legendary easter egg).

## Asset notes
The SVG is self-contained (gradient + drop-shadow filter), scales cleanly, and is reused at
84px (corner) and 140px (404). A red-tile `favicon.svg` matches. If you add poses, keep the
same viewBox (`0 0 160 200`) so sizing stays consistent.
