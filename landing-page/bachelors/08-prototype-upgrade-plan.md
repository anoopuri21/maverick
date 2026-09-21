# 08 · Bachelor's LP Prototype Upgrade Plan

**Date:** 2026-09-21 · **Target file:** `07-lp-prototype.html`
**Goal:** testimonials structure visible (placeholder content), cinematic multi-directional scroll reveals, graphics and graphical stats on a text-heavy page, and a colour plan that complements the design system.

---

## 1. Current state

The prototype already has: design system tokens (MBA blue `#0f2983`, navy `#071444`, red `#b20202`, warm white `#f5f0eb`), one reveal direction only (bottom to top), two photo plates (hero, office), one animated pie (KHDA 35%), one bar chart (fees), one drawn spine (steps), ghost numerals and two marquees. Missing: testimonials block, varied scroll choreography, graphics in text-heavy sections, complementary accent colours.

## 2. Change plan

### A. Testimonials block (placeholder content, structure visible)
- New section between "Market proof" and "Meet us", title "Student stories, told the way we will publish them."
- Three cards: portrait placeholder frame (SVG silhouette, no stock photo), placeholder name and country, programme tag, quote, five gold stars.
- Every card carries a visible `SAMPLE` corner tag and the section footer says: "Placeholder layout. Real, consented student stories replace these before go-live."
- Content is generic, makes no unverifiable claim, and is clearly marked as sample.

### B. Cinematic scroll choreography (four directions)
- Extend the reveal system from one effect to six: `up` (default), `down`, `left`, `right`, `zoom`, `fade`, via `data-rv` attribute values; one IntersectionObserver, unchanged reduced-motion fallback.
- Direction map (varied so the page reads like chapters, not one repeated effect):

| Section | Effect |
|---|---|
| Routes families | alternate left / right |
| Catalogue | table from right, header from left |
| Family questions rails | staggered from left, pie from left + text from right |
| Benefits | left column from left, right column from right, tiles stagger from bottom |
| Audience rows | staggered from left |
| Steps | spine draw (kept) + cards stagger from bottom |
| Fees | bars grow (kept) + notes from right |
| Comparison table | zoom |
| Recognition | sticky column from left, answers from right |
| Recognition timeline (new) | line draws left to right, nodes pop |
| Testimonials | stagger from bottom |
| Proof stats | bottom + count-up (kept) |
| Office | text from left, plate from right |
| FAQ items | staggered fade |
| Final CTA | zoom |

### C. Graphics and graphical stats (text-only sections fixed)
1. **Recognition timeline (new graphic):** animated horizontal spine with three pulsing nodes: 2023 (Ministry of Education begins recognising online degrees), 10 March 2025 (MoHESR policy published), today (apply online, around 30 working days, Dh100). Replaces a wall of text with a scannable graphic; full text stays alongside.
2. **Degree premium bars (new graphic in Benefits):** twin animated bars, degree AED 12,000 vs diploma AED 7,000, turning the MoHRE policy fact into a visual stat.
3. **Fee bars:** axis caption and value labels refined; growth arrows on proof stats in gold.
4. **SVG line icons:** three audience personas, event calendar, checklist ticks. Inline SVG only, navy/red strokes; no emoji anywhere (design system ban list).
5. **Arc motif:** subtle radial arc ornaments behind section titles (design system motif map: "radial/arc as motion + type"), low-opacity hairline strokes.
6. Pie chart, drawn spine, ghost numerals and marquees kept and reused.

### D. Colour plan (design system first, complements second)
Core palette is never overridden: MBA blue `#0f2983`, navy `#071444`, red `#b20202`, warm white `#f5f0eb`.

| New token | Value | Role | Why it complements |
|---|---|---|---|
| `--gold` | `#c8a24b` | Stars, quote marks, trend deltas, timeline node rings | Warm prestige accent that sits with warm white and navy; education/achievement register |
| `--gold-soft` | `#f3ead6` | Sample tag background, soft gold hairlines | Same hue family as the paper, no new surface feel |
| `--sky` | `#e9eef8` | Audience section band (cool alternate band) | Tint of MBA blue; breaks the warm-paper rhythm once without fighting it |
| `--paper-deep` | `#ece3d7` | Tile and gives-grid backing | Deeper warm surface, keeps editorial warmth |

Rules: red stays the only action colour; gold is decorative only (large numerals, ornaments, tags), never buttons or links; sky band used once for contrast; all body text keeps existing navy/ink contrast.

## 3. Out of scope

No content changes to the sixteen approved sections; no copy rewrites; the placeholder testimonials carry SAMPLE tags so they cannot be mistaken for consented stories at go-live.

## 4. Verification

- Preview served locally; every section scrolls into view with its assigned direction.
- Reduced-motion mode renders everything static and visible.
- Zero em/en dashes in new copy; no emoji; no banned jargon; placeholders clearly tagged.
