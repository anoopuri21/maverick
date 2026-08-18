# Cursor AI — Program Detail Page UX Phase 2

> Paste this whole file into Cursor as your instruction. Work **PLAN FIRST, THEN BUILD**.
> Do NOT touch anything until you have produced a written plan and I have approved it.

---

## ROLE
You are a **senior UX/UI designer and Laravel + Blade + CSS engineer**. You have full creative ownership over the visual design of these changes. I am giving you the **requirements and outcomes** — the actual layout, composition, spacing, and interaction design is **YOUR job**. Use your own judgement and taste. Match the existing design system, but design freely within it.

## BRANCH
`dev/overall-development`

## FILES YOU MAY EDIT (only these unless approved)
- `resources/views/pages/programs/detail.blade.php`
- `public/css/pages/program-detail.css`
- Inline `<script>` inside the blade (already the pattern)
- Do NOT touch model/controller/migrations unless a field is genuinely missing (flag it first — don't invent).

---

## CONTEXT — the page
This is a programme detail page with 16 sections (hero, recognition strip, snapshot, overview, why-choose, learn, careers, structure, about-university, accreditation, why-maverick, success-stories, fees, faq, enquiry, reviews, final CTA). Design system is locked (tokens below). It is fully content-agnostic — every section renders only if content exists, works for 0–20 items, never empty placeholders, no fixed counts, never hides content without JS, reduced-motion safe.

## DESIGN SYSTEM (LOCKED — tokens you must work within)
```css
--color-mba-blue:#0f2983; --color-mba-dark-blue:#071444; --color-mba-red:#b20202;
--color-light-bg:#f9faff; --color-gold:#f7b500; --border-radius-bento:24px;
--font-display:Neue Montreal...; --font-body:Poppins...;
--ink:#0b1330; --muted:rgba(11,19,48,.66); --line:rgba(15,41,131,.14);
--shadow-sm/md/lg; --container:1200px;
```
Use these. Don't introduce new brand colors/fonts. (Gradients/shades within these families are fine.)

---

## THE 4 REQUIREMENTS (outcomes — YOU decide the design)

### 1. Accreditation & Recognition section
**Current:** 3-column grid, one card per group (Institutional / International / Professional), each with logos.
**Requirement:** I want a **single, unified, premium composition** for this whole section instead of three separate cards. It must elegantly present all accreditation logos together. The logos should be the visual focus. When the logos collectively **overflow the available width**, they should **slider/drag** naturally instead of breaking the layout. Logo titles are shown with the logos.
**Outcome:** One cohesive, elegant section. Logos prominent. Overflow → slider. Content-agnostic.
*Design decision is yours: composition, layout, grouping, how the group labels surface, how the slider behaves.*

### 2. Student Success Stories — auto thumbnail
**Current:** `.story-media` shows a static gradient `.art`; no real thumbnail.
**Requirement:** Each story's media should show a **thumbnail image** — and it should **auto-resolve**:
- If the testimonial has an **uploaded `thumb`** → show that.
- Else if it has a **YouTube `video`** URL → **auto-derive** `https://img.youtube.com/vi/{id}/maxresdefault.jpg` (same logic as the existing `Testimonial::auto_thumbnail` accessor in `app/Models/Testimonial.php`).
- Else → a clean branded fallback (no broken `<img>`).
Keep the play-button overlay when a video exists. The section must **slider-behave** (handle any item count gracefully).
**Outcome:** Real thumbnails where possible, clean fallback otherwise, playable videos, robust slider.

### 3. Student Reviews — 10-line clamp + expand/collapse
**Current:** review text `.q` shows the **full** content always.
**Requirement:** Review content shows **max 10 lines** by default. If content is longer, an **expand control** appears that reveals the full content **smoothly**, and collapses back. **Only one review may be expanded at a time** — expanding another auto-collapses the previous. Keep it accessible (aria-expanded, keyboard) and reduced-motion safe.
**Outcome:** Clean, elegant expand/collapse interaction with one-open-at-a-time.

### 4. Final CTA — contained, warm/red (not navy full-bleed)
**Current:** full-width navy band.
**Requirement:** I want the final call-to-action as a **contained card/container** (not full-bleed) with a **warm reddish tone** (from the red family — definitely NOT blue/navy). Keep the heading, sub-text, and Apply/Enquire actions inside it. Responsive.
**Outcome:** A premium contained CTA card with a red/warm feel that fits the page rhythm.

---

## IMPORTANT: YOUR DESIGN, MY APPROVAL
I am **not** going to dictate the exact markup, layout, or pixel values. You are the designer. When you produce the **plan**, show me the composition you intend (brief written description + how each requirement is met). I will review it and approve. Then you build it.

## HARD RULES
- **Content-agnostic & optional:** every section renders only if content exists; never empty `<img>`, never broken, no empty placeholders, no fixed item counts; works for 0/1/3/8/12/20 items.
- **Never invent content.** Use real seeded `$t['thumb']`, `$t['video']`, `$item['logo']`, `$r['review']`. For missing ones use existing fallback patterns (initials chip, auto YouTube thumb, branded placeholder).
- **Stay inside the design system** (tokens). Preserve reveal-on-scroll (`.rv`), reduced-motion support, no horizontal page overflow (320–1440), accessibility.
- `media_url()` for image fields. No console errors.

## WORKFLOW (MANDATORY)
1. **PLAN FIRST.** Read the current blade + CSS. Produce a written design plan for each of the 4 requirements: your proposed composition/layout, the interaction behavior, which existing patterns you'll reuse (e.g. the marquee slider, the `auto_thumbnail` logic), and any fallbacks. Explain your design decisions briefly. Do NOT write code yet.
2. **STOP and present the plan.** Wait for my approval.
3. **BUILD** only after approval.
4. After building, verify: page renders at 320/768/1320, no overflow, no broken images, all 4 behaviors work, tests still green (19 pass / 3 pre-existing).
