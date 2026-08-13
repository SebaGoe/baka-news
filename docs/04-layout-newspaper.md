# 04 · Newspaper Layout

Files: `assets/css/newspaper.css`, `Views/partials/masthead.php`, `Views/partials/nav.php`,
`Views/pages/landing.php`.

## Masthead
Three-column grid: left meta (volume/date), center nameplate (`Baka News` + `バカ・ニュース`),
right meta (motto + language toggle). A 4px + 1px double rule closes it. The nameplate is
the one place blackletter appears.

## Category nav
Horizontal, wraps on mobile. Active item filled with the category `color`. Order follows
`categories.json`. A "swipe to change section" hint shows on touch widths.

## The column grid (the important part)
`.grid` uses CSS Grid with `grid-auto-flow: row dense` so varied box sizes tile like a real
front page. Article `size` maps to column spans:

| size | mobile | 640px (4-col) | 1000px (6-col) | notes |
|------|--------|---------------|----------------|-------|
| `lead` | full | span 4 | span 6 | biggest headline; lead text flows in **2 columns** on desktop |
| `feature` | full | span 2 | span 3 | large headline + drop-cap lead |
| `standard` | full | span 2 | span 2 | headline + dek |
| `brief` | full | span 2 | span 2 | headline + dek, short |
| ad | full | span 2 | span 2 | injected every 4 articles |

Vertical **column rules** (hairlines) appear via `border-right` on cards at ≥640px; the
paper shows through as thin gutters. Cards carry a kicker (flag · country · category chip),
a display headline, an italic dek, and — for lead/feature — a drop-capped lead paragraph.

## Drop caps
`.dropcap` floats the first glyph at ~3em. Applied on lead/feature cards and the first
article paragraph. Uses `mb_substr` so it's safe for Japanese/Arabic first characters.

## To extend (session B)
- Add a `size: "spotlight"` variant if you want a boxed 2×2 feature (add span rules + a
  border treatment; update doc 02).
- Optional: a thin "continued on page 2" flourish on the lead (purely decorative).
- Keep headline line-heights tight (1.05) and never center body text — newspapers are
  left-aligned and dense.
