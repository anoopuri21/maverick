# Program Page — Blade Implementation Plan (Phase-wise)

**Goal:** Port the approved prototype (`program-page-prototype.html`) into the Laravel blade template — **content-agnostic, admin-driven** (sab content model accessors/JSON se, koi hardcode nahi).
**Branch:** `feature/program-pages`
**Blade:** `resources/views/pages/programs/detail.blade.php`
**CSS:** `public/css/pages/program-detail.css` (full rewrite to new design system)

---

## New Design System Tokens (replacing old `--pd-*`)
```css
--color-mba-blue:#0f2983; --color-mba-dark-blue:#071444;
--color-mba-red:#b20202;  --color-light-bg:#f9faff;
--color-gold:#f7b500;     --border-radius-bento:24px;
--font-display:"PP Neue Montreal"...; --font-body:"Poppins"...;
```

---

## Data Model Mapping (admin-driven → section)
| # | Section | Blade variable |
|---|---------|----------------|
| — | Sticky bar + WhatsApp | static (persistent) |
| 1 | Hero | `$program->title`, `short_description`, `highlights_list`, `duration`, `reviews`, scholarship flag |
| 2 | Recognition strip | `$recognition` (logo+note) + `$accreditationGroups` |
| 3 | Snapshot (bento) | `$snapshot` (8 metrics) |
| 4 | Overview | `$program->description`, `$highlights` |
| 5 | Why Choose | `$benefits` (title, desc, icon) |
| 6 | What You'll Learn | `$learning` |
| 7 | Careers | `$careers` |
| 8 | Structure | `$structure` (years → modules accordion) |
| 9 | About GAU | `$university` |
| 10 | Accreditation | `$accreditationGroups` |
| 11 | Why Maverick | `$support` (content-agnostic; seeder 8 items) |
| 12 | Success Stories | `$testimonials` (video) |
| 13 | Fees | `$fees` |
| 14 | FAQ | `$faqs` |
| 15 | Enquiry | static form → `route('contact')` |
| 16 | Reviews | `$reviews` |

**Rules:** Every section `@if(content)`. Never empty cards/placeholders. Grids adaptive (works 0/1/3/8/12/20 items). `VERIFY` flags retained (unresolved credits/duration/study-mode/uni-metrics).

---

## Phases (har phase = implement + verify + commit + push)

### Phase 0 — Foundation
- Env setup ✅, seed ✅, baseline test.
- New design tokens + base layout + **sticky bar** + **WhatsApp float** in `program-detail.css`.
- Rewrite CSS foundation (responsive 320–1440, `prefers-reduced-motion`, reveal-on-scroll utility).

### Phase 1 — Hero + Recognition strip (sections 1–2)
- Cinematic dark hero (backdrop, scholarship ribbon, Quick Highlights, 3 CTAs, Programme-at-a-Glance card).
- Recognition slider (logos + notes, seamless marquee).

### Phase 2 — Snapshot + Overview (sections 3–4)
- Bento snapshot grid (8 metrics, icon tiles, `VERIFY` flags).
- Editorial overview (2-col, framed figure).

### Phase 3 — Why Choose + What You'll Learn (sections 5–6)
- 5 pillar cards (blue 2D illustration icons).
- 8 learning outcomes (organic geometry).

### Phase 4 — Careers + Structure (sections 7–8)
- Bento talent-cloud careers.
- Year 1–4 accordions + modules.

### Phase 5 — About GAU + Accreditation (sections 9–10)
- Cinematic dark university panel (image mask, metrics, `VERIFY`).
- Grouped accreditation logo grids.

### Phase 6 — Why Maverick + Success Stories (sections 11–12)
- 3×3 support grid (9 perks, active red accent).
- Testimonial slider (video thumbnails + country + role).

### Phase 7 — Fees + FAQ (sections 13–14)
- Cinematic dark glassmorphism fees (all → enquiry).
- FAQ accordion + organic dividers.

### Phase 8 — Enquiry + Reviews + Final CTA (sections 15–16 + final)
- Enquiry form (2px blue stroke, trust badges).
- Google rating badge + review cards.
- Final CTA.

### Phase 9 — QA & Polish
- Full responsive sweep (320–1440), no overflow, slider/accordion/reveal tests.
- Full test suite (baseline 19 pass / 3 pre-existing).
- Final commit + push.

---
**Status:** Phase 0 in progress.
