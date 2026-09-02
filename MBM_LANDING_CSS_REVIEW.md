# MBA & Master's Landing Page — Overview + CSS Review

Review of `resources/views/pages/mba-masters-landing.blade.php` and its includes, with a
detailed review of the two page stylesheets:

- `public/assets/css/pages/mba-masters-landing.css` — 4,681 lines, ~119.6 KB
- `public/assets/css/pages/mba-masters-polish.css` — 2,721 lines, ~74.5 KB

Reviewed on branch `arena/01a06289-maverick` (commit `5a70c6a`).

---

## 1. Page overview

### Routing & data

| Item | Detail |
|---|---|
| Route | `GET /online-mba-masters-uae` → `PageController@mbaMastersLanding` (`routes/web.php:24`) |
| Enquire route | nested `.../enquire` (POST target of all forms, `#mlp-enquire` anchor) |
| Admin | Filament page `app/Filament/Pages/ManageMbaMastersLanding.php` — every section's copy/images is CMS-driven |
| Content source | ~25 settings tables under `database/settings/2026_08_2*_create_mba_masters_*.php` + 2026_08_2x sync/seeding scripts |
| Asset caching | `cached_asset()` (`app/helpers.php:99`) appends `?v=filemtime` so far-future immutable `Cache-Control` stays safe across edits |

### Main blade (91 lines) — what it does

1. `@extends('layouts.app')`; pushes `partials.seo-meta` with `$seo`.
2. Builds **FAQPage JSON-LD** from `$faq->items` (only when `$seo->schema_json` is empty) and injects it in `<head>`.
3. Renders `$seo->custom_body_scripts` if present.
4. Pushes the two page CSS files (order matters — polish loads last and overrides).
5. Renders `.mlp-page.mlp-page--polished` containing 18 section partials (see map below).
6. Renders the fixed mobile action bar `.mlp-sticky` (WhatsApp `wa.me/<site->whatsapp_number>` + "Apply Now" → `#mlp-enquire`).
7. Defers 11 page JS files (1,652 lines total).

### Section → partial → CSS root map

| Order | Partial | Root class | id | Notes |
|---|---|---|---|---|
| 1 | `hero` | `.mlp-hero .prospectus-cover` | `#mlp-hero` | `data-hero-assembly`; enquiry desk inline (`partials/enquire-form` pattern) |
| 2 | `trust` | `.signal-atlas` | `#mlp-trust` | count-up stats (`data-mlp-count`), `--signal-index` inline |
| 3 | `overview` | `.blueprint-overview` | `#mlp-overview` | SVG diagram + 5 "foundations" |
| 4 | `why` | `.mlp-why` | `#mlp-why` | sticky pin header + chapters |
| ~~5~~ | `journey` | `.mlp-journey` | `#mlp-journey` | **commented out** in main blade — CSS still shipped |
| 5 | `mba` | `.mlp-mba` | `#mlp-mba` | tabs + plate/catalog showcase; inline `--mlp-specialization-rows` |
| 6 | `masters` | `.mlp-masters .mlp-masters--prospectus` | `#mlp-masters` | "Prospectus Ledger": 21:9 banner + hairline grid of all programmes |
| 7 | `class-2025` | `.mlp-class-2025` → `.blueprint-overview__class-2025` | `#mlp-class-2025` | radial stat diagram (5 clip-path cards + center disc) |
| 8 | `sections.accreditations` (shared homepage partial) | `.accreditations` | `#accreditations` | styled by homepage CSS; polish layer patches it (see §3.4) |
| 9 | `class-snapshot` | `.mlp-class-snapshot` | `#mlp-class-snapshot` | cohort metrics + 20-country index |
| 10 | `fees` | `.pricing-cards` | `#mlp-fees` | **uses `pricing-cards`, not `archive-investment`** |
| 11 | `career` | `.archive-career` | `#mlp-career` | sticky image stack + dossiers + `.mlp-uae-badge` (pure-CSS UAE flag) |
| 12 | `alumni` | `.archive-alumni` | `#mlp-alumni` | CSS marquee; DOM list duplicated + `aria-hidden` clone (seamless loop ✓) |
| 13 | `learning` | `.archive-learning` | `#mlp-learning` | "study desk" image stack + points |
| 14 | `partners` | `.archive-partners` | `#mlp-partners` | drag-to-scroll logo wall, scroll-snap, grayscale→color hover |
| 15 | `video-proof` | `.archive-video-proof` | `#mlp-video-proof` | YouTube film plate (custom poster + play button, no auto-load) |
| 16 | `testimonials` | `.archive-voices` + `.luxury-testimonials` | `#mlp-testimonials` | single-voice carousel (JS in `mba-masters-testimonials.js`) |
| ~~17~~ | `compare` | `.archive-parallel` | `#mlp-compare` | **commented out** — CSS still shipped |
| 17 | `faq` | `.archive-fieldnotes` | `#mlp-faq` | accordion (question rotates icon 45°) |
| 18 | `final` | `.archive-closing` | `#mlp-final` | closing CTA + enquiry form |

### Orphan views (not rendered by this page)

- `pages/mba-masters-landing/class.blade.php` — **not referenced anywhere** (verified by grep). Its CSS targets (`.topic-desk`, `#mlp-class`) are shipped in polish.css for nothing.
- `journey.blade.php`, `compare.blade.php` — included lines are commented out.

### JS (all `defer`, no build step)

| File | Responsibility |
|---|---|
| `mba-masters-landing.js` (1007 ln) | page core: reveal-on-view (`data-mlp-reveal`), sticky bar logic, count-ups, etc. |
| `mba-masters-hero-assembly.js` (62 ln) | GSAP hero timeline; if `reduced` or no GSAP → immediately adds `.is-hero-assembled` (CSS shows final state) |
| `mba-masters-polish.js` (39 ln) | adds `.is-polished-ready` to page and `.is-polished-inview` to sections via IntersectionObserver — **neither class is used by any CSS** (dead state) |
| `mba-masters-{accreditations,overview,archive,video-proof,class-topics,testimonials,closing,trust}.js` | per-section behaviours |

The layout (`layouts/app.blade.php:9`) adds `html.js` to `<html>` — this is the gate for all
"hidden until JS" CSS, so no-JS users always see the final layout.

---

## 2. `mba-masters-landing.css` — detailed review

### 2.1 Architecture

- Everything scoped under `.mlp-page` with a private token block: brand (`--mlp-blue #0f2983`,
  `--mlp-navy #071444`, `--mlp-red #b20202`, `--mlp-warm`, `--mlp-ink #121212`), surfaces
  (`--mlp-void`, `--mlp-paper #f7f5f1`, `--mlp-surface-soft`, glass), lines, type
  (`--mlp-font-display` = PP Neue Montreal, `--mlp-font-body` = Poppins), motion
  (`--mlp-ease cubic-bezier(.16,1,.3,1)`, `--mlp-duration .55s`).
- Brand tokens fall back to globals: `var(--color-mba-blue, #0f2983)` etc. — so a global theme
  change propagates.
- Header comment states the design "Ban": *white form cards, equal stat tiles, pill eyebrows,
  soft gray-blue premium*. The file genuinely obeys it: `border-radius: 0` everywhere (only
  exception: video player 1.25rem), hairline 1px borders, flat paper/navy surfaces, no card grids.

### 2.2 Section-by-section

1. **Foundation** — type utilities (`.mlp-display/.mlp-headline/.mlp-lede/.mlp-meta/.mlp-stat`,
   all `clamp()`-based), section rhythm, `.mlp-rule` hairlines, button system (primary red /
   ghost / text / block; hover = 2px lift), `.mlp-sticky` mobile bar (safe-area inset,
   `backdrop-filter: blur(16px)`), form system (underline-only inputs, red focus underline,
   honeypot field, `mlp-form--panel` variant).
2. **`content-visibility: auto`** on all below-fold sections with
   `contain-intrinsic-size: auto 720px` — good LCP/scroll perf; the 720px estimate is generic
   (section heights vary a lot: e.g. class-2025 is much taller, FAQ much shorter) so the scroll
   bar can jump slightly when sections cross the viewport. Acceptable trade-off.
3. **Hero `.prospectus-cover`** — 100svh; photo `filter: saturate(.68) contrast(1.08) brightness(.64)`,
   `scale(1.035)`; layered navy wash (2 gradients); SVG `feTurbulence` grain data-URI at
   `mix-blend-mode: overlay`, opacity .13; registration frame + masthead/folio editorial chrome;
   title `clamp(3.4rem→8.6rem)` lh 0.9; right-side `.prospectus-cover__enquiry` desk
   (paper panel, red top border, shadow). Strong, coherent "living prospectus" concept.
4. **`.blueprint-overview`** — rotated grayscale plate (12%, multiply), contour rules, SVG
   connectors drawn via `stroke-dashoffset` on `.is-inview`, center disc + 5 foundations placed
   with `nth-child` grid placement; 700px collapse re-arranges to a single-column list with a
   gradient spine (`::before`) — the mobile restyle is careful (resets every nth-child rule).
5. **`.mlp-why`** — sticky pinned header (gradient fade), chapters `min-height: min(72vh, 640px)`
   with oversized red numerals. (Polish later nulls the numerals — see §3.3.)
6. **`.mlp-journey`** — DEAD (section not rendered). ~330 lines.
7. **`.mlp-mba` / `.mlp-masters`** — same component twice: tab list (active = inset red
   underline), plate + catalog showcase with `--flip` order swap, program rows with rotating
   diamond mark. The two blocks are ~300 lines of near-identical CSS that could be shared via a
   common base class.
8. **Light Archive** (`.archive-class/career/alumni/learning/partners/voices/parallel/fieldnotes/closing`)
   — the page's core visual language: sticky navy image "stacks" with 3 rotated
   `mix-blend-mode: screen` photo layers + gradient veil + display-type caption; hairline
   dossier/point/voice rows; alumni marquee (`32s linear infinite`, pause on hover/focus-within);
   partner wall (native scroll + `is-dragging` grab state, scroll-snap, grayscale→color);
   FAQ accordion; closing form plate.
9. **`.pricing-cards`** — 2-col card grid, 3px navy top rule flipping red on hover, big red
   tabular price, `7.5rem` dt rail in details. Uses `var(--mlp-type-*, fallback)` — safe because
   polish defines the tokens (they're always co-loaded).
10. **`.signal-atlas`** — 5 records with per-`nth-child` `translateY` stagger, SVG signal line
    (`vector-effect: non-scaling-stroke`), quote with 3.5rem quote-mark.
11. **`.mlp-prose`** — the red-disc bullet with pulsing ring (`mlpProseRing`,
    `box-shadow` 0→10px fade, paused via `.is-offscreen`), on-dark variant `#e85a5a`. Distinctive
    micro-motion; correctly disabled under reduced motion.
12. **`.blueprint-overview__class-2025`** — the most fiddly component: absolutely positioned
    stat cards (clip-path notched corners) + floating `stat-copy` callouts at `calc(100% + …)`
    offsets; SVG arc/connector plate sized `calc(100% + 100px)` with `top: -100px` to avoid
    clipping strokes. 900px → 2-col grid; 560px → 1-col; every absolutely-positioned variant is
    explicitly reset in the media queries (long, but correct).
13. **`#mlp-class-snapshot`** — defined **twice**: first as a 2-col `overview + global` layout
    (~150 lines), then re-declared full-width single column ("full-width overview cards" block).
    **Partially** shadowed, not fully: the second block overrides the grid layout, metric
    card-ification, countries flex, and global padding — but the first block still supplies the
    container (padding/border-top/background), `__frame`, `__intro`, `__label`, `__heading`,
    `__intro-copy`, the `h3` rules, `__metric dt { display: contents }`, the `__metric` grid
    template + `__metric-icon` grid placement, `__metric-label`, `__country img`/flag-fallback,
    and `isolation: isolate`. Both blocks must be merged into one canonical block per property
    (see optimization plan, Phase 4) — do not delete either wholesale.
    Also: two media blocks reference `#mlp-class-snapshot .mlp-class-snapshot__global::after`
    (height/background-size) but `__global::after` is **never defined anywhere** (no
    `content`, no background) — leftover from an earlier world-map treatment; both rules are no-ops.
14. **Accreditation fallback** — `@keyframes mlpAccreditationLoop` + `.is-landing-slider`
    36s marquee, because the homepage slider script isn't loaded on this page. Correct
    integration pattern, and polish CSS forces the shared section's `.text-reveal-inner/.fade-up`
    visible for the same reason.
15. **`.mlp-uae-badge`** — pure-CSS UAE flag (red vertical band + green/white/black stripes),
    placed in the career grid via `grid-row: 2`; mobile reorders stack → badge → dossiers with
    `order`.
16. **`.mlp-masters--prospectus`** — the newest design (matches current `masters.blade.php`):
    split editorial header, 21:9 banner with offset 1px frame + red corner rule, 3-col hairline
    ledger grid (1px-gap background trick), diamond marks, CTA row. Clean and self-contained.

### 2.3 Quality notes (landing.css)

**Good**
- Consistent BEM-ish naming, one block per section, comments per section.
- Accessibility is unusually thorough: `:focus-visible` on every interactive element,
  `.mlp-vh` utility, semantic h1→h3, `aria-hidden` on decorative layers, `aria-labelledby` on
  sections, marquee clone `aria-hidden`, reduced-motion blocks for *every* animated component.
- `100svh` (mobile URL-bar safe), `safe-area-inset-bottom` on sticky bar,
  `text-underline-offset`/`thickness`, `accent-color` on radios, option color fixes for dark
  select popups.

**Issues**
1. **Dead CSS** (verified against the blade): `.mlp-journey*` (~330 ln),
   `.archive-parallel*` (~120 ln), **the entire `.archive-class*` block** (~400 ln — only
   referenced from the orphaned `class.blade.php`; the live class sections are `class-2025`
   and `class-snapshot`, which use different classes), the partially-shadowed first
   `#mlp-class-snapshot` block (merged, not deleted), and the
   `__global::after` media rules (2 blocks), plus `.archive-voices__voice` row styles largely
   superseded by `.luxury-testimonials`.
2. **Duplication**: `.mlp-mba` and `.mlp-masters` are copy-paste twins (plate, plate-bar,
   uni-logo/initials, catalog, programs, media queries) — ~300 lines could be one shared base.
3. **Maintenance smell**: `.blueprint-overview__class-2025` lives in the *Overview* block but is
   rendered in the standalone `class-2025` chapter; the "standalone chapter" patch
   (`.mlp-class-2025 .blueprint-overview__class-2025 { margin-top: 0 }`) confirms it was moved
   without relocating the CSS.
4. `backdrop-filter` without `-webkit-` prefix (`.mlp-sticky`, `.mlp-form`) — fine on Safari 18+,
   just no blur on older Safari (graceful).
5. `#mlp-hero .prospectus-cover__body { padding: 2rem 15px }` appears in polish (mixed units,
   see §3.3).
6. Perf: `mix-blend-mode: screen` ×4 sections + hero grain overlay + `filter: blur(2px)` on the
   trust SVG (polish) = several composited layers; fine on modern hardware, worth a glance on low
   end. Raw CSS total ~194 KB (~45 KB gzip) for one page.

---

## 3. `mba-masters-polish.css` — detailed review

### 3.1 What this file is

A **post-audit override layer** ("Quietly Cinematic"), loaded after landing.css. Comments are
organized as "Issue 1…28", "29-point audit batch", "Canonical project typography lock",
"Final accreditation reference lock". It is where most of the page's *effective* look is decided —
including several overrides that null out landing.css design decisions.

Key facts:
- **97 `!important`s here vs 4 in landing.css** — the layer won every specificity war.
- Defines the `--mlp-type-*` scale (`micro/caption/body/lede/title-sm/title/title-lg/display/stat`)
  aliased to global `--fs-*` tokens from `main.css` (`--fs-section-title: clamp(36px,5vw,72px)` etc.).
- Defines (twice, identically) `--mlp-color-heading/body/muted/accent/inverse/line`.
- Defines `--mlp-polish-section-gap`, `--mlp-polish-ink-soft`, `--mlp-polish-red-soft` — **all three
  unused** (only `--mlp-polish-line` is used, 5×).

### 3.2 The global overrides that matter most

1. **Section compression** (Issue 3):
   `.mlp-page--polished > section:not(.prospectus-cover) { margin-block: 0; padding-block: 2rem; }`
   Specificity (0,2,1) **beats every per-section `padding: clamp(4.5rem, 9vw, 7.5rem)` in
   landing.css** — i.e. the entire "section rhythm" system is dead on this page; all sections are
   2rem top/bottom. Intentional per the comment ("compact section boundaries"), but it means
   editing per-section padding in landing.css has **no visual effect** — a trap for the next
   person.
2. **Font reset (Issue 2)**:
   `.mlp-page--polished * { font-family: var(--mlp-font-body) !important; }` then
   `:is(h1..h6), :is(h1..h6) * { font-family: var(--mlp-font-display) !important; }`.
   The descendant form forces display font on *every inline child inside headings* (e.g. the
   `<strong>`/`<span>` in the class-2025 heading, snapshot heading). Mostly desired, but it's a
   sledgehammer that also covers SVG `<text>` and any future markup.
3. **Typography lock** — three overlapping `!important` blocks (the "Issue 1" role map, the
   "Canonical project typography lock", and the "Final accreditation reference lock" scoped by
   section id) all pin the same components to `--fs-*` tokens. The final block changes nothing
   visually (same values, just id-scoped + higher specificity) — pure duplication for "guarantee".
4. **Palette re-map** — the last two lock blocks set *all* section headings to
   `color: var(--color-mba-blue) !important` (#0f2983) instead of landing's `--mlp-navy`
   (#071444), labels to mba-blue, and body copy to `var(--color-black)` (#000) instead of
   `--mlp-ink` (#121212). The polish layer effectively re-branded the whole page to match the
   shared accreditation section. If that wasn't an explicit design decision, it's the biggest
   silent design drift in the file.
5. **Hero assembly baseline** (the file's best part):
   `html.js .prospectus-cover[data-hero-assembly]:not(.is-hero-assembled) [data-hero-image|wash|registration|enquiry|…]`
   hides/blurs/clips the hero parts *only when JS is present and hasn't assembled yet*, with a
   `prefers-reduced-motion` override to the final state. Pairs with `mba-masters-hero-assembly.js`
   (GSAP timeline; no-GSAP → instant `.is-hero-assembled`). Progressive enhancement done right —
   no-JS and reduced-motion users never see a hidden hero.

### 3.3 Per-section refinements (summary)

- **Trust**: re-staggered records (`nth-child` translateY re-defined), `--signal-index`-delayed
  infinite `mlpTrustRecordBounce` (4.8s, `translate` — compositor-friendly), SVG backdrop
  `blur(2px) opacity(.5)`, record-index hidden, value/label stacked single column.
- **Why**: `min-height: 0` chapters, compact padding, numerals replaced by 3.25rem red icon
  boxes; the two blocks ("Issue 18/19" vs "#mlp-why targeted") set slightly different grid
  columns — the later `4rem minmax(0,1fr)` wins.
- **Overview**: intro forced single column/left aligned, heading accent red, core border faded
  to 5%, foundations get `background: paper; box-shadow: 0 0 0 .75rem paper` — a halo that hides
  the connector lines behind each card (nice trick).
- **Accreditations (shared section)**: forces `.text-reveal-inner, .fade-up` to
  `opacity: 1; transform: none` (homepage-only reveal JS absent here) and re-types
  `.programs__heading/.section-label span/.accreditations__subheading/.accreditations__trust-text`
  to the page scale. Verified those class names exist in `sections/accreditations.blade.php`.
- **MBA**: `--flip` order re-swapped (plate back to left — landing's flip is undone),
  `programs--columns` 2-col grid driven by inline `--mlp-specialization-rows` (verified in
  `mba.blade.php:136`).
- **Masters**: catalog forced single column.
- **Video proof** (~180 ln, new component): 16:9 plate, double 1px frame, red circular play
  button with hover ring, custom poster (YouTube iframe injected only on click — good LCP).
- **Luxury testimonials** (~140 ln): single-voice carousel, 22rem min-height slides, quote
  re-typed with `!important` (Poppins body), prev/next/autoplay controls + progress.
- **Catalog specification** (~90 ln): `mlp-mba__specification` / `mlp-masters__specification`
  dl with left navy rule — used by current `mba.blade.php`.
- **Class snapshot / pricing cards**: role re-typing (title-sm, stat, caption) to keep the
  "Fees-only redesign" above the global resets.
- **Topic Desk** (~150 ln): interactive topic switcher for `#mlp-class` industries — **dead**,
  see below.

### 3.4 Dead code in polish.css (verified)

| Block | Why dead |
|---|---|
| All `.archive-investment*` (~200+ ln across 4 regions) | fees blade renders `pricing-cards`; `grep archive-investment` in `resources/views/` → **zero hits** |
| All `.topic-desk*` + `#mlp-class .archive-class__industries` restyle | targets `class.blade.php`, which is referenced nowhere |
| `.mlp-journey*` rules | journey section commented out |
| `.archive-parallel*` rules | compare section commented out |
| "29-point audit batch" | repeats the "Issue 1/2" colour + type blocks almost verbatim (second definition, same values) |
| "Final accreditation reference lock" (~120 ln) | repeats the canonical lock, id-scoped; same declarations |
| Tokens `--mlp-polish-section-gap/-ink-soft/-red-soft` | never consumed |
| `@media (max-width: 700px) { @keyframes mlpTrustRecordBounce {…} }` | redefining keyframes inside a media query (works, but non-idiomatic — a second keyframes name or two classes would be clearer) |

Also: `mba-masters-polish.js` adds `.is-polished-ready` / `.is-polished-inview` classes that **no
CSS references** — the "polish coordinator" state is currently inert.

### 3.5 Conflicts / risks

1. **Three typography authorities** (role map → canonical lock → final lock) + the `*` reset:
   any future font/size change needs edits in 3–4 places or gets silently overruled.
2. **`!important` cascade is one-way**: landing.css values for headings/body/buttons are
   unreachable without `!important` of their own. This is why landing.css looks so different
   from the live page.
3. `#mlp-hero .prospectus-cover__body { padding: 2rem 15px; }` — px inside a rem system, and it
   adds horizontal inset inside an already `container`-wrapped frame.
4. Heading color `--color-mba-blue` + body `--color-black` (see §3.2.4) — verify with the design
   owner that pure-black body text on warm paper is intended (it's noticeably harder than the
   original `#121212`).
5. The `> section { padding-block: 2rem }` compression plus per-section `border-top` hairlines
   means sections now read as one continuous ledger — fine, but landing.css's
   `clamp(4.5rem…7.5rem)` rhythm and its `--void/--paper` alternation no longer apply; only the
   backgrounds survived.

### 3.6 What's genuinely good here

- JS-gated hero baseline + reduced-motion fallback (industry-correct pattern).
- `:where()`/`:is()` used correctly to keep specificity low in the shared-role blocks.
- `text-wrap: balance/pretty`, `tabular-nums` on fee values, `backface-visibility` on imgs,
  universal `:focus-visible` ring.
- Shared-section integration patches (accreditations) instead of duplicating the component.
- New components (video proof, luxury testimonials, ledger, specification) are complete
  self-contained blocks with their own media + reduced-motion handling.

---

## 4. Recommended cleanup (if/when you touch it)

Ordered by safety/impact:

1. **Delete dead blocks** (no visual change): `.mlp-journey*`, `.archive-parallel*` (both
   files), `.topic-desk*`, `.archive-investment*`, `__global::after` media rules, unused polish
   tokens, duplicated lock blocks ("29-point batch" + "Final accreditation reference lock" —
   only after per-declaration equivalence is verified). ≈ 800–900 lines, ~12–15% of the shipped
   CSS. (Note: `#mlp-class-snapshot`'s first block is NOT dead — it is only partially shadowed;
   it gets merged, not deleted.)
2. **Decide the palette drift**: heading `--color-mba-blue` + body `#000` — keep (and document
   in the landing.css tokens) or revert to `--mlp-navy`/`--mlp-ink`.
3. **Merge `.mlp-mba`/`.mlp-masters`** into one shared base class (+ flip variant).
4. **Re-home class-2025 CSS** next to the standalone chapter and drop the margin patch.
5. **Either wire or delete** the `is-polished-ready/inview` classes (or the polish.js observer).
6. Replace `padding: 2rem 15px` with `padding: 2rem 0.9375rem` (or 0, if the container inset is
   enough) and add a comment in landing.css marking its section paddings as overridden.
7. Optional: move the `--mlp-type-*` scale into landing.css (it's referenced there with
   fallbacks) so the pair is less order-coupled.

---

## 5. TL;DR

- **Page**: 91-line blade + 18 partials, fully CMS-driven, solid SEO (FAQPage JSON-LD),
  mtime-versioned assets, correct no-JS/reduced-motion fallbacks, strong a11y.
- **landing.css**: a coherent, disciplined design system ("Light Archive / Living Prospectus"):
  hairlines, sharp corners, navy/red/warm paper, editorial chrome. Main problems are *dead and
  duplicated blocks* (journey, compare, snapshot-first-pass, mba/masters twins), not design.
- **polish.css**: an audit-driven override layer that quietly becomes the real stylesheet:
  it compresses all sections to 2rem padding, resets fonts globally, re-types everything with
  97 `!important`s, re-styles the shared accreditations section, and adds the new components
  (video proof, luxury testimonials, ledger, spec dl). Its problems are *layered duplication*
  (3 typography locks, 2 colour vocabularies, 29-point batch repeat), a **palette shift to
  #0f2983 headings / #000 body**, and ~400 lines of dead CSS (`archive-investment`,
  `topic-desk`).
- The two files together ship ~7,400 lines where roughly 25–30% is dead; the live page is
  well-engineered underneath the cruft.
