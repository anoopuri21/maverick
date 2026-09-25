# MBA/Master's Landing (`/online-mba-masters-uae`) — Single-Page Header & Footer Plan

**Route:** `mba-masters-landing` · **View:** `resources/views/pages/mba-masters-landing.blade.php`
**Scope:** Landing page ONLY. Common `partials.navbar` / `partials.footer` stay untouched for every other page.

---

## 1. Problem

The landing page currently inherits the site-wide chrome from `layouts/app.blade.php`:

- **Header** = `partials.navbar` → Programs mega-menu, About/Pathways/Insights dropdowns, Contact, Student Login.
  Every link leaves the landing page. A single-page funnel should navigate **within** the page.
- **Footer** = `partials.footer` → 3 columns: (1) logo + about + **newsletter form**, (2) **Programmes list**, (3) Contact + social.
  Newsletter + Programmes column are dead weight on a conversion-focused landing page.

## 2. Solution overview

Dedicated, landing-only chrome partials that reuse the **existing design system** (`.navbar` / `.footer` look & feel, same tokens), with:

- Header links = anchors to the landing page's own important sections.
- Footer = brand (logo + description) + contact only. No newsletter element. No Programmes column. Bottom legal row kept.

```
layouts/app.blade.php
   ├── routeIs('mba-masters-landing') ? partials.mlp-navbar : partials.navbar      (NEW switch)
   └── routeIs('mba-masters-landing') ? partials.mlp-footer : partials.footer      (NEW switch)
```

## 3. New header — `resources/views/partials/mlp-navbar.blade.php`

Same DOM contract as the common navbar (`<header id="navbar" class="navbar mlp-navbar">`,
`.navbar__container`, `.navbar__logo`, `.navbar__menu`, `.navbar__link`, `.navbar__hamburger`,
`.navbar__mobile` …) so the existing `navigation.js` keeps working untouched:
scroll state (`is-scrolled`), hide-on-scroll-down, mobile overlay open/close, close-menu-on-link-click.

**Behaviour**

| State | Look |
|---|---|
| Top of page (over cinematic hero) | **Solid white bar** (`inner-navbar` look), dark logo, black links — *approved decision* |
| After 80px scroll | Same white bar + blur + hairline (`.is-scrolled`), hide-on-scroll-down kept |
| Active section in view | Link gets `.is-current` underline (scroll-spy, new tiny JS) |
| < 1024px | Hamburger + full-screen black overlay, same anchor links + Apply Now |

**Links (anchors to master page sections)**

| Label | Target |
|---|---|
| Overview | `#mlp-overview` |
| Why Maverick | `#mlp-why` |
| MBA | `#mlp-mba` |
| Master's | `#mlp-masters` |
| Fees | `#mlp-fees` |
| FAQ | `#mlp-faq` |
| **Apply Now** (red `.btn .btn--primary .navbar__cta-btn`) | `#mlp-enquire` |

Anchor smooth-scroll + 90px offset already comes from `main.js` (`maverickScrollTo` / Lenis).

## 4. New footer — `resources/views/partials/mlp-footer.blade.php`

`<footer id="footer" class="footer footer--mlp">` — same dark-navy surface, white top border, container.

| Block | Status |
|---|---|
| Logo (white) + description (`.footer__logo`, `.footer__about`) | **kept** |
| Newsletter (`Stay Updated` + form) | **removed entirely** |
| Programmes column | **removed entirely** |
| Contact column (UAE / UK locales, WhatsApp, social icons) | **kept** |
| Bottom row (© + Privacy Policy / Terms of Use) | **kept** |

Grid becomes 2 columns via modifier only:
`.footer--mlp .footer__main { grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); }`
(responsive fallbacks: 1 column < 768px, same as today).

## 5. Assets

| File | Type | Purpose |
|---|---|---|
| `public/assets/css/pages/mlp-chrome.css` | new | Minimum modifiers only: narrow-desktop (≤1200px) anchor tightening so 6 links + CTA never overflow, mobile overlay CTA row, 2-col footer grid + ≤1024 stack + ≤900 sticky-bar clearance. Everything else inherits `.navbar` / `.footer` from `main.css` unchanged. Loaded via `@push('styles')` on the landing page (same pattern as `mba-masters-landing.css`). |
| `public/assets/js/pages/mlp-chrome.js` | new | Scroll-spy: marks `.is-current` on the header anchor of the section in view (rAF-throttled scroll, ~70 lines). Loaded via `@push('scripts')` on the landing page. |

### Design-system compliance (verified)

- Header markup uses only existing `.navbar*` classes (`inner-navbar` solid bar, `.navbar__menu/__link/__cta/__hamburger/__mobile*`); underline + hover + mobile overlay behaviour come from `main.css`/`responsive.css` untouched.
- Footer markup uses only existing `.footer*` classes; the single modifier is the 2-column `.footer__main` grid (typography, hairlines, icons, social boxes, bottom row all base).
- No changes to `partials/navbar.blade.php`, `partials/footer.blade.php`, `main.css`, `responsive.css`, `navigation.js` or any other page (see §6 diff list).

### Responsive behaviour (verified via mockups, shots 5–8)

| Breakpoint | Header | Footer |
|---|---|---|
| >1200px | base `.navbar` spacing | 2 columns (brand / contact) |
| 1025–1200px | tightened anchor spacing (`mlp-chrome.css`) | 2 columns |
| ≤1024px | hamburger + overlay (base) | 1 column stacked |
| ≤768px | logo + hamburger bar (base) | contact locales stacked, centred bottom row (base) |
| ≤900px | — | extra bottom padding clears `.mlp-sticky` bar |

## 6. Files touched (surgical)

1. `resources/views/partials/mlp-navbar.blade.php` — new
2. `resources/views/partials/mlp-footer.blade.php` — new
3. `resources/views/layouts/app.blade.php` — two `routeIs('mba-masters-landing')` conditionals
4. `resources/views/pages/mba-masters-landing.blade.php` — push `mlp-chrome.css` + `mlp-chrome.js`
5. `public/assets/css/pages/mlp-chrome.css` — new
6. `public/assets/js/pages/mlp-chrome.js` — new

No changes to `partials/navbar.blade.php`, `partials/footer.blade.php`, `main.css`, `navigation.js`.
Zero impact on other pages.

## 7. Edge cases handled

- `navigation.js` early-returns only when `#navbar` is missing → new header keeps `id="navbar"`, all controllers null-safe.
- `ActiveLinkController` path matching ignores `#` anchors → scroll-spy owns `.is-current` on this page.
- Mobile `.mlp-sticky` bar (WhatsApp + Apply) overlays footer bottom on <900px → `mlp-chrome.css` adds bottom padding to `.footer--mlp` on mobile.
- WhatsApp float already excluded on this route in `layouts/app.blade.php` (unchanged).
- Footer composer `partials.footer` (program categories query) no longer runs for the landing page → one less DB/cache hit.

## 8. Visual previews

`mlp-preview/` (repo-local, gitignored — generated artifacts) contains pixel mockups rendered
from the exact tokens in `main.css` (colors, paddings, type scale, hairlines) with the real
logo assets (`render.cjs` + `build-board.sh` reproduce them):

1. `shot-01-header-over-hero.png` — solid white header bar on the cinematic hero
2. `shot-02-header-scrolled.png` — scrolled bar with active-section underline
3. `shot-03-mobile-menu.png` — mobile overlay with section links
4. `shot-04-footer-new.png` — new slim footer (no newsletter, no programmes)
5. `shot-05-header-1100.png` — narrow-desktop header (tightened spacing, no overflow)
6. `shot-06-header-mobile-bar.png` — 390px top bar (logo + hamburger)
7. `shot-07-footer-tablet.png` — 768px stacked footer
8. `shot-08-footer-mobile.png` — 390px stacked footer
9. `preview-board.png` — labelled composite of all of the above

**Approved in review:** 6-link anchor set (Overview · Why Maverick · MBA · Master's · Fees · FAQ)
+ Apply Now CTA, and the always-solid white header bar.
