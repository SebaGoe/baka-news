# 07 · Fake Ads

Files: `Views/partials/ad.php`, `Views/pages/ads.php`, `Views/pages/ads-submit.php`,
`src/Controllers/AdsController.php`, `data/content/ads.json`.

## Where ads appear
- **feed** — injected into the news grid every 4 articles (`Content::feedAd()`).
- **sidebar** — in the right rail (`partials/sidebar.php`).
- **banner** — reserved for a top/bottom strip (data exists; add the strip if you want one).

Every ad card is labeled "ADVERTISEMENT" and its CTA links to `/ads` (all fake).

## The Advertising Emporium (`/ads`)
Gallery of the built-in fake ads **advertising the fake ad business itself** ("Premium ad
space for your imaginary business, as seen between our fake articles"). Below it, a wall of
**reader-submitted** ads from the `submitted_ads` table.

## Submit a fake ad (`/ads/submit`)
CSRF-protected form: emoji logo, business name, pitch. Length-capped, escaped, auto-published
(`approved = 1`). Redirects to the gallery.

## Idea bank (add to `ads.json`)
Shipped: Y2K Bug Spray, Ghost Insurance, Baka Deli, Dial-Up Nostalgia Hotline, Rent-a-Cloud,
Invisible Umbrella Co., Under Construction Signs Inc., Pixel Polish.

More: "Tamagotchi Retirement Home", "Premium Silence (per minute)", "Left Socks — we only
sell lefts", "The Thirsty Ghost (Happy Hour: whenever)", "Cloud Storage (actual clouds)",
"Bottled Dial-Up Sound", "Rent-a-Crowd for your fake launch", "Mystery Box (contains a
smaller mystery box)".

## Polish (session C)
- Rotate feed ads randomly per request (currently deterministic by position — fine, but a
  light shuffle adds life).
- Optional banner strip component under the nav.
- Tiny "your ad here →" filler ad linking to `/ads/submit`.
