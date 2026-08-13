# 06 · Coupons & Easter Eggs

Files: `Views/pages/coupons.php`, `assets/js/main.js` (redeem + egg engine),
`assets/js/modal.js`, `assets/css/components.css` (`.coupon*`, `.modal*`, confetti),
`src/Controllers/CouponController.php`, `data/content/coupons.json`.

## The vault
A perforated-ticket grid. Each coupon shows rarity, emoji, title, and a hint. Locked
coupons render as `🔒 ??? SECRET ???` until unlocked. Tapping an open coupon fires
`POST /coupons/redeem`, opens a retro modal with a **fake barcode + serial**, and rains
**confetti**. Redeemed coupons get a rotated "USED" stamp and are remembered in the session.

## Unlock mechanics (engine in `main.js`)
| unlock | trigger |
|--------|---------|
| `open` | always available |
| `konami` | ↑↑↓↓←→←→ B A |
| `mascot-clicks` | click Bakabake 7× (mascot.js calls `BakaEggs.unlock`) |
| `hidden-spot` | double-click the visitor counter |
| `secret-url` | visit any page with `?ghost=1` |

`BakaEggs.unlock(kind, message)` reveals the matching locked coupon in place, shows a
celebration modal, and confetti. Adding a new mechanic = add an `unlock` value in doc 02,
one coupon in JSON, and a trigger that calls `BakaEggs.unlock`.

## Idea bank (add these to `coupons.json`)
Already shipped: free article, imaginary sandwich (Baka Deli), free drink (The Thirsty
Ghost), 50% off your next existential crisis, buy 0 get 0 free, a compliment from the ghost,
free WiFi password ("password"), a coupon for a coupon, virtual high-five, guaranteed ghost
sighting, 10% off the moon, imaginary-car parking, one free "I told you so", self-service
pat on the back, the legendary dial-up sound.

More to write (keep the cute, absurd voice + always add `_ja`):
- "One free undo button for real life (single use)"
- "Redeem for a slightly better Tuesday"
- "Free trial of being a cat (14 naps)"
- "This coupon entitles the bearer to nothing, elegantly"
- "50% off gravity (results may vary, see article)"
- "One complimentary ghost to haunt a mildly annoying coworker"
- "Free refill on enthusiasm"
- "Coupon valid only in the past"
- "One (1) small victory, unspecified"
- "Free download of this coupon (right-click → save)"
- Legendary: "The Golden Sandwich — 100% imaginary, 100% delicious" (konami)
- Legendary: "Lifetime supply of one (1) high-five" (mascot-clicks)

## Polish (session C)
- Rarity flair: legendary coupons already glow; consider a subtle sparkle on hover.
- "Collected N / total" progress meter at the top of the vault (session-based).
- A tiny sound on redeem (respect a mute toggle + reduced-motion/quiet preferences).
