# 05 · Reading Pages (landing / category / article)

## Landing & category — `Views/pages/landing.php`
Shared template. Landing shows all articles (newest first); category shows a filtered set
plus a colored section header. Ads are injected into the feed every 4 articles via
`Content::feedAd()`. Empty state has a ghost joke.

## Swipe — `assets/js/swipe.js`
Touch swipe on the content area moves to the prev/next category by following the nav order.
Left swipe = next, right = prev; wraps around; Front Page is index 0. Threshold 60px and a
horizontal-dominance check so vertical scrolls don't trigger it. A brief opacity fade eases
the navigation. Desktop users use the nav; the swipe is a mobile nicety.

**To improve (session B, optional):** make it an in-place AJAX swap with a slide animation
instead of a full navigation, keeping the URL in sync via `history.pushState`. Keep the
current full-nav behavior as the no-JS fallback.

## Article detail — `Views/pages/article.php`
Kicker (flag · country · clickable category chips) → big display headline → italic dek →
byline with a rotated **"NOT FACT-CHECKED"** stamp → a "translated from X" note when not
viewing the original → drop-capped body → a fabrication disclaimer.

### Related articles
`Content::related($article)` returns up to 4 articles sharing ≥1 category (excluding self).
Rendered as a 2-column link grid under a double rule. If you want smarter relatedness
(same country, or same size), extend `Content::related()` — signature stays the same.

### Translations
Global toggle in the masthead sets `?lang=native|en|ja` (cookie-remembered). The article
body is an array of paragraphs per language; `t()` handles headline/dek. Always keep all
three languages filled (doc 11).

## Accessibility
Cards are a single `<a>` wrapping the headline (whole card clickable). Keep the visible
focus ring. Don't nest interactive elements inside the card link.
