# 03 — Design System

Baka News is a **refined editorial newspaper** with a light retro undercurrent —
not an emoji-driven novelty site. The humour lives in the *writing*; the design
stays quietly well-made so the jokes land.

## Principles
- **No emoji anywhere.** Meaning is carried by type, colour, rule-work, and small
  CSS/SVG marks — never pictographs. Country origin shows as a 3-letter code chip
  (`JPN`, `DEU`); categories as a coloured dot + name; reactions as words
  (*Ha! · Whoa · Hmm · Oof · Boo*); coupons as a monogram; the coupon barcode is
  drawn in CSS.
- **Colour is intentional and accessible.** One warm newsprint paper, one rich ink,
  a brick-red accent, a classic ink-blue link, and seven muted category hues.
- **WCAG AA is the floor, AAA the target.** Every text and UI pair is contrast-checked.

## Palette (verified)
| Token | Hex | Use | Contrast |
|---|---|---|---|
| `--paper` | `#f4efe4` | page | — |
| `--ink` | `#1c1b17` | body text | 15:1 on paper (AAA) |
| `--ink-soft` | `#565043` | secondary | 7:1 (AAA) |
| `--red` | `#9e2b25` | accent, breaking, focus | 6.5:1 text; 7.4:1 white-on-red |
| `--link` | `#1b4b8f` | links | 7.5:1 |
| `--link-visited` | `#5c3a86` | visited | 7.6:1 |

**Category hues** (white text ≥6.2:1; as label text on paper ≥5.4:1):
world `#2b5f6e` · politics `#8f2f2c` · animals `#3e6b3a` · science `#34566e` ·
food `#8a531b` · tech `#414277` · weird `#7a3468`.

**Night Edition** re-maps the tokens to a dark sheet (`--paper #1e1c18`,
`--ink #ece5d5`, `--link #8fb6e8`, `--red #e58d84`, amber focus) — all pairs
re-verified ≥6.9:1. Category text falls back to `--ink-soft` on the dark sheet so
nothing drops below AA.

## Type
UnifrakturCook (masthead) · Playfair Display (headlines) · **Spectral** (body) ·
IBM Plex Mono (labels/counters) · Special Elite (typewriter accents). All degrade
to system serifs/monospace if web fonts are blocked.

## Accessibility notes
`<html lang>` is set; native-language article text carries its own `lang`
attribute; there's a skip-link, visible `:focus-visible` rings, `.sr-only`
labels on icon-free controls, `aria-current`/`aria-pressed` on nav and toggles,
`prefers-reduced-motion` handling, and a no-flash Night-Edition script in `<head>`.
Information is never conveyed by colour alone (the ticker uses `+`/`−` signs; the
poll shows its own vote outline; reactions are words).

To re-verify contrast after any colour change, run the check in
`docs/00-overview.md` or the inline script used during the refactor.


## The Netscape browser frame
The whole site is wrapped in a period-accurate Netscape Navigator window —
navy title bar, silver menu/tool bars, a working **Location** (address) bar, a
throbber, and a status bar that previews link targets on hover. The chrome is a
neutral silver (ink 11.6:1) with a navy title bar (white 13.5:1); the *page*
inside keeps the warm newsprint and still switches to Night Edition independently,
exactly as a real browser would. Back/Forward/Reload/Home/Print/Search all work;
the menus (File/Edit/View/Go/Bookmarks/Options/Help) drop down with real links and
in-character jokes.

## Easter eggs & the Arcade
Type a meme number in the Location bar — or anywhere on the page — to trigger it:
**42, 67, 1337, 9001, 8675309, 404, 777, 007, 2038, 314, 1234, 1996, 0451**. Three
of them unlock hidden coupons in the Vault. There's also **Whack-a-Ghost** at
`/arcade` (30s, keyboard-playable via keys 1–9); score 15+ and the ghost rewards you.
Run `php tools/check.php` to guarantee no emoji ever creep back in.
