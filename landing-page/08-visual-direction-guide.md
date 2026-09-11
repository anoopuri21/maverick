# 08 — Visual Direction Guide
**Agent 9 — Visual & Creative Direction Agent**
**Project:** Maverick Business Academy London — Conversion Landing Page
**Date:** 2026-09-09
**Constraint anchor:** the existing **MLP design system** (`docs/mlp-design-system.md`) — "cinematic editorial" language, fixed ban list. This guide extends it to the new LP; where the two conflict, the design system wins.

---

## 1. Mood & Inspiration

**Mood in one line:** *Financial Times meets a London night train — light editorial paper chapters, two cinematic dark plates, red used like a signature, never like a fire alarm.*

**References (direction, not to copy):**
- FT/Bloomberg editorial type treatment (huge numerals, hairlines, left-aligned)
- The production MLP "prospectus cover" hero (masthead, edition line, registration number motif) — the site's own best work
- Penguin Classics chapter pages (paper warmth, quiet authority)
- London architecture photography: brutalist + glass, dusk light, depth of field

**Do NOT reach for:** SaaS gradient meshes, stock "people pointing at screens", rounded SaaS cards, glassmorphism beyond the enquiry panel, emoji/duotone-icon kits.

## 2. Color (locked tokens)

| Token | Hex | Role | Usage ratio |
|---|---|---|---|
| `--navy` (void) | `#071444` | Hero / dark chapters / footer / sticky bar | ~25% of page area |
| `--mba-blue` | `#0f2983` | Secondary darks, links on light, chart/data | ~10% |
| `--red` | `#b20202` | Primary CTA, accent line in H1s, focus rings | ≤ 5% — the signature |
| `--paper` | `#f5f0eb` | Default light chapters | ~55% |
| `--paper-pure` | `#ffffff` | Surfaces on paper where contrast needs it | ~10% |
| `--line-dark` | rgba(7,20,68,0.14) | Hairlines on light | everywhere |
| `--line-light` | rgba(245,240,235,0.22) | Hairlines on void | everywhere |
| Text on void | `#f5f0eb` / 70% | — | — |
| Text on paper | `#071444` / 75% | — | — |

**Color psychology notes (audience-matched):**
- **Navy** = institutional trust + London night; the premium signal that costs nothing to claim.
- **Red (#b20202)** = action + Maverick defiance; reserved strictly for *the* CTA and one accent line per dark section, so it keeps meaning.
- **Warm paper** = editorial, human, anti-corporate-white; differentiates from every blue/white university site in the SERP.
- **Contrast check (WCAG AA minimum):** red on navy passes for large text & buttons with white text; red on paper is CTA-only (never body text).

## 3. Typography

| Role | Face | Fallback stack | Spec |
|---|---|---|---|
| Display (H1/H2, numerals) | **PP Neue Montreal** (licensed, in use) | `Inter, -apple-system, "Segoe UI", sans-serif` | clamp 2.75rem→5.5rem; tracking −0.02 to −0.03em; line-height 1.05–1.12 |
| Body / UI | **Poppins** (in use) | `system-ui, sans-serif` | 16–18px body; 1.55 line-height; never below 14px |
| Meta/eyebrow | Poppins | — | 0.7–0.8rem, tracking 0.14em, uppercase, hairline rule — **not a pill** (ban list) |

Rules:
- Two-line H1s: line 2 carries the red accent treatment on dark chapters.
- Numerals (stats, fee table) in display face, oversized, with hairline dividers — **typographic data, not chart graphics**.
- No text on photo without a navy veil ≥ 55% or a solid paper panel.

## 4. Imagery Direction (per section)

| Section | Subject | Mood / treatment | Specs |
|---|---|---|---|
| §2 Hero | Professional (30s–40s) working at dusk — laptop on a London window ledge / train home, OR a confident mid-shot in a real office (no whiteboard pointing). London depth (skyline bokeh or brick+glass). | Cinematic, low-key navy grade, red available only as UI accent. **Veil:** navy 55–70%. | 1600×900 WebP/AVIF, `fetchpriority=high`, preloaded; grain overlay (existing `__grain` motif) |
| §3 Trust strip | None (pure typography) | Hairlines + big numerals on paper | — |
| §4 Problem | Optional single editorial photo: an empty conference room at dusk / a calendar page | Quiet, slightly underexposed; navy wash | 1200×800 |
| §5 About | Academy in motion: real campus/Dubai office photo OR a strong architectural plate (Ruislip/Sharjah) if client assets exist; fallback = abstract navy texture + type | Chapter-plate feel, 60% veil | 1600×900 |
| §6 Ladder | Numbered rungs as **typography** (ghost numerals 01–04 per design-system motif); small programme images only if real assets exist | Light paper, editorial | — |
| §7 Why | Iconography: **hairline-stroke line icons (1.5px) in navy on paper** — no filled icon sets, no emoji (banned site-wide) | Consistent 24px grid | — |
| §8 Founder/faculty | Real portraits: founder (chest-up, neutral warm background), faculty strip (fixed 112×140 per design system) | Natural light, unretouched feel | 800×1000 founder; 448×560 portraits |
| §9 Stories | Portrait + one contextual image per story (e.g., the actual role environment); same 112×140 frame language | Documentary, not glossy | — |
| §10 Testimonials | Type-first; optional small circular avatars only if real photos (consistent crop, no filters) | Paper | — |
| §11 How it works | **Drawn spine with numbered nodes** (existing motif from MLP career chapter), pulsing on scroll; no clip-art | Navy on paper, 2px stroke | SVG |
| §12 Fees | Pure typography table; one red underline under "payment plans" column | Editorial table, hairline rows | — |
| §13 FAQ | Full-bleed dark accordion rows (MLP pattern) | Void | — |
| §14 Enquiry | Glass panel on soft paper band (`--mlp-glass`); underline inputs, red focus ring | Light, calm, premium | — |
| §15 Final | Void, single strong image (night London skyline, long exposure) or pure void + type; red CTA | Closing darkness | 1600×700 |
| Footer | Darkest navy, logo in paper-white | — | — |

**Sourcing rule:** real student/staff/office photography from the media library first (production uses Cloudinary + MediaPicker with fallbacks). Stock only for hero/§15 if client cannot supply; if stock, choose *working* scenes over *celebration* scenes, and avoid the "diverse team high-fiving" cliché that undercuts the executive register.

## 5. Motion (design-system primitives only)

| Primitive | Use | Timing |
|---|---|---|
| `mlpReveal` (opacity + y, staggered, once) | Section entries, statement lines (§5), pain rails (§4) | 0.6–1.0s, power3.out / expo.out, no bounce |
| `mlpCount` | Stat strip (§5), fee numerals where real | On scroll-in |
| `mlpParallax` | Hero plate only, subtle | ≤ 40px shift |
| Accordion | FAQ rows, 0.3s ease | — |
| **Reduced motion** | `prefers-reduced-motion`: skip all, content visible immediately (site-wide rule) | — |

No fade spam, no infinite marquees, no 3D cards.

## 6. Component Spec (handoff to dev)

- **Buttons:** primary = solid red, radius 0–2px, min-height 48px, white text, hover: darken 8% + underline-offset arrow; secondary = hairline border (paper-on-void / navy-on-paper); text CTA = underline offset, no box.
- **Inputs:** underline/hairline on glass (hero) and paper (§14); label in meta style; focus = red hairline + 4px soft ring (AA).
- **Cards (rare):** flat paper or flat void, 1px hairline, **no soft box-shadow stacks** (ban list), radius 0–4px.
- **Tables:** hairline rows, meta-style header row, first column left-aligned display-adjacent; no zebra beyond alternating 3% navy tint.
- **Sticky mobile bar:** glass navy, two buttons, 56px tall, safe-area padded.
- **Spacing rhythm:** section padding 96–140px desktop / 64px mobile; container max 1200px; one narrative column, max measure 68ch.

## 7. Accessibility & Performance Budget

- WCAG 2.1 AA: contrast (checked pairs above), focus visibility, `aria-label` per section, keyboard-operable accordion & form, alt text per §4, reduced-motion path.
- LCP < 2.5s mobile 4G · CLS < 0.1 · JS ≤ 60KB gzipped (one LP file, `defer`) · hero image WebP/AVIF ≤ 180KB.
