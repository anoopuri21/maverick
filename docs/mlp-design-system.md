# MBA Masters Landing — Design System (Phase 0)

**Status:** Phase 0–18 complete (Partners → Final CTA + QA). Ready for design review / Phase 19+.

**Brand (never override):** MBA blue `#0f2983`, navy `#071444`, red `#b20202`, warm white `#f5f0eb`, PP Neue Montreal + Poppins. Prefix: `mlp-`.

---

## 1. Ban list (Batch 01 anti-patterns — never ship again)

| Banned | Why it felt amateur |
|--------|---------------------|
| White form “card” with soft box-shadow on hero | Generic SaaS lead magnet |
| Equal white stat tiles / fan clip-path cards | Template “metric row” |
| Pill / chip eyebrows | Startup landing cliché |
| Soft gray-blue gradients as “premium” | Muddy, not cinematic |
| Centered uppercase section labels as sole hierarchy | Weak, brochure-like |
| Uniform card grids for every section | No rhythm, no drama |
| Clip-path gimmicks without meaning | Looks like Canva |
| Playful / rounded UI language | Wrong for executive MBA |
| Emoji icons | Banned site-wide |

---

## 2. Approved visual language (cinematic editorial)

**Mood:** Light premium education × editorial storytelling. Warm paper / white chapters; navy + red accents. Hero may keep cinematic photo plate for contrast — all other sections stay light.

**Surfaces**
| Token | Use |
|-------|-----|
| `--mlp-paper` / `--mlp-paper-pure` | Default page + chapters |
| `--mlp-surface-soft` | Soft warm alternate bands |
| `--mlp-void` | Hero photo veil / rare accent only |
| `--mlp-glass` | Light frosted panels (enquiry) |
| `--mlp-line-dark` | Hairlines on light |

**Type scale (CSS utilities)**
| Class | Size | Role |
|-------|------|------|
| `.mlp-display` | clamp(2.75rem → 5.5rem) | Section / hero titles |
| `.mlp-headline` | clamp(1.75rem → 2.75rem) | Sub-chapter titles |
| `.mlp-lede` | clamp(1.05rem → 1.25rem) | Supporting sentence |
| `.mlp-meta` | 0.7–0.8rem, tracking 0.14em, uppercase | Eyebrow / index (not a pill) |
| `.mlp-stat` | clamp(2.5rem → 4.5rem) | Oversized metrics |

Display: PP Neue Montreal. Body: Poppins. Titles: tight tracking (−0.02em to −0.03em), line-height ~1.05–1.12.

**Buttons**
- Primary: solid red, sharp (radius 0–2px), min-height 48px
- Ghost: hairline border on dark, no fill
- Text: underline offset, no box
- Never: rounded-full pills, multi-layer glow shadows

**Forms (foundation)**
- No floating white card
- On dark: glass panel OR open fields on veil; underline / hairline inputs
- Labels: `.mlp-meta` weight, not bold navy chips
- Focus: red or blue hairline + soft outer ring (accessible)

**Trust / metrics (foundation)**
- Typographic strip: huge numerals + hairline dividers
- Optional single hub rating as oversized type, not a white circle card
- No fan / petal clip-paths

---

## 3. Motif map (moodboard → section)

| Source | Motif (elevated) | Section |
|--------|------------------|---------|
| p1 fan | Radial / arc **as motion + type**, not white tiles | §2 Trust |
| p1 FAQ | Full-bleed dark accordion rows | §16 FAQ |
| p2 split | Monumental H1 + editorial quote rail | §1 / §14 |
| p2 CTAs | Solid red + outline | Global |
| p2 green split | Diagonal media plane (navy/red veil + clipped photo plate + numbered study points) | §12 Learning |
| p3 yellow bars | Numbered horizontal benefit rails | §3–4 |
| p3 class cards | Cinematic chapter: split header + ghost "09" + stat count-ups + typographic region strip + scene-row industries; **fixed 72×72** frames | §9 |
| p4 charts | Typographic / bar data | §9 |
| p4 career | Cinematic chapter: ghost "10" + drawn spine w/ pulsing nodes + 3-beat story scenes (portrait clip → name/rule → Previous→Now cascade); **fixed 112×140** portraits | §10 |
| p5 alumni logos | Ghost "11" + light chapter + transparent logo marquee; **fixed 180×72** clear logos; PartnerLogo type=alumni | §11 |
| p5–6 | Partners marquee, comparison table, final void CTA | §13–17 ✅ |
| — | Big campus plate + numbered program catalog (light) | §6 MBA / §7 Master's |

---

## 4. Motion primitives (`mba-masters-landing.js`)

| Primitive | Behavior |
|-----------|----------|
| `mlpReveal` | Opacity + y with stagger; once on enter |
| `mlpCount` | Stat count-up on scroll |
| `mlpParallax` | Subtle bg shift (hero stage) |
| Reduced motion | Skip all; content visible immediately |

Timing: 0.6–1.0s, `power3.out` / `expo.out`. No bounce. No cheap fade spam.

---

## 5. Section rhythm (phases)

1 section = 1 phase. Alternate **void** ↔ **paper** unless narrative needs back-to-back immersives (FAQ → Final CTA).

Sticky mobile: WhatsApp + Apply — glass navy bar, not chunky blocks.

---

## Asset / content checklist (SOP §20)

| Asset | Status |
|-------|--------|
| Fees | Placeholder until client approves |
| Accreditation | Short verified only |
| Stats + 4.8 / 400+ | SOP + client |
| Testimonials | Placeholders until permission |
| Partners / media | Existing approved assets |
| **Images from Phase 6+** | Required — logos, portraits, campus/learning plates via MediaPicker + fallbacks |

## 7. Gate

**Phase 5 approved → plan image rule locked → Phase 6 MBA (with logos) → approve → …**
