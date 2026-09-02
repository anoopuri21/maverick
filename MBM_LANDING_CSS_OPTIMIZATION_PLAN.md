# MBM Landing — CSS Merge + Optimization Plan

Goal (user requirement, non-negotiable):

1. **2 CSS files → 1 CSS file** (`mba-masters-landing.css` + `mba-masters-polish.css` → single `mba-masters-landing.css`)
2. **Dead CSS removal** (verified, not guessed)
3. **Generic shared classes** for classes that carry identical properties
4. **100% design freeze** — no 1% visual change. Every phase is gated by an automated
   equivalence check; a non-zero diff reverts the phase.

Scope: **CSS only** + minimal class *additions* in blade partials (Phase 5). No JS changes, no
rename of existing BEM classes, no structural HTML changes, no `main.css`/global changes.
(`mba-masters-polish.js` dead classes, `class-topics.js` dead script = follow-up, not this plan.)

---

## 0. Why "zero change" is provable here

The only thing that can change the design is a change in the **cascade** (which declaration
wins for which element+property). So the whole plan is built on two rules:

- **R1 — Order preservation.** The merged file is the original rules in the original relative
  order (landing rules first, polish rules after — exactly as the two `<link>`s loaded today).
  No rule is ever moved before/after another rule that could compete with it. Media blocks keep
  their original relative position.
- **R2 — Same-winner transformations only.** We may (a) delete a rule only when nothing else
  provides the same property for the same elements (or when the rule is fully shadowed),
  (b) merge selectors only when they have the **same specificity class** (or identical
  computed effect, verified) and **identical declarations**, (c) move declarations into a
  shared class only when that shared class is added to exactly the same elements and the
  effective winner for every property stays the same.
  `!important` declarations are never merged with non-`!important` ones, and "lock" blocks
  are only removed when a per-declaration diff proves the identical value already wins.

Everything else is caught by the verification gates (below).

---

## 1. Verification system (built in Phase 0 — before touching any CSS)

The sandbox has no PHP/vendor, so the Laravel app can't run here. Instead we build a
**static equivalence harness** that is sufficient for CSS-only changes:

**A. Fixture DOM** (`scripts/css-equivalence/fixture/mbm.html`)
- One static HTML page containing **all 18 sections** of the landing page with the exact
  markup/classes/ids from the blade partials (data-rich variant: 5 trust stats, 4 why
  chapters, 3 MBA tabs w/ universities, full masters ledger, 5 class-2025 stats, 4 snapshot
  metrics + country list, 4 fee rows, 3 career dossiers, 6 alumni logos ×2 clone, 4 learning
  points, 8 partner logos, video plate, 3 testimonials, 5 FAQ, final form, sticky bar).
- Includes the `html.js` class and the JS-toggled states **both on and off** where relevant
  (we test the static state — the state without JS toggles — which is what CSS-only changes
  can affect; JS toggled states are re-verified with the class forced on in one extra pass).
- Loads the page CSS file(s) under test.
- Uses the same global tokens: we also load `assets/css/main.css` so `--fs-*`, `--fw-*`,
  `--color-mba-*` resolve exactly as in production.

**B. Style-snapshot script** (Playwright, node — both already available)
- For **every element** in the fixture: dump tag, id, classes, `getBoundingClientRect()`
  (x/y/w/h), and `getComputedStyle` for a fixed list of ~70 design-relevant properties
  (font-family/size/weight/style/line-height/letter-spacing/color, background-color/image,
  all borders, padding, margin, width/height/min/max, display, grid/flex props, position,
  top/right/bottom/left, z-index, transform, opacity, filter, clip-path, box-shadow,
  overflow, text-decoration, text-transform, text-align, cursor, border-radius…).
- Output: `snapshot-{label}.json`.

**C. Diff script**
- `diff baseline.json candidate.json` → asserts **zero differences** (element count, rect,
  every property). Any mismatch = phase fails, revert.

**D. Pixel diff**
- Full-page + per-viewport screenshots at **1440, 1024, 768, 390** (mobile) in the same
  Chromium build/flags → `pixelmatch`, tolerance 0.
- Pixel diff is the final visual proof; style-snapshot catches sub-pixel/computed-level
  differences that screenshots can hide.

**E. Size report** — lines, bytes, gzip bytes per phase (before/after table for the final
report).

Baseline (B+C+D) is captured against the **current two files** and frozen as the reference.
Each subsequent phase must diff zero against that same baseline.

> If/when the app is run locally or on staging, the same B/C/D scripts can be pointed at the
> live `/online-mba-masters-uae` URL for a final live confirmation (same scripts, one URL swap).

---

## 2. Phase plan

Every phase = one commit on `arena/01a06289-maverick`, gated by C (style diff) + D (pixel
diff) = zero. A failing gate → `git revert` that phase.

### Phase 0 — Baseline + audit (no code change)

1. Build harness (A–E), install chromium, capture **frozen baseline**.
2. Selector audit script: extract every selector from both CSS files; classify each class/id:
   referenced in `resources/views/**` (blade) ✅ / referenced in `public/assets/js/pages/mba-masters-*.js` ✅ /
   pseudo-element, attribute or media-only / **orphan** ⚠.
3. Output: (a) exact **dead list** (supersedes the review-doc estimates), (b) **protected list**
   (all `data-*` hooks, JS-toggled classes like `is-active/is-open/is-hero-assembled/
   is-landing-slider/is-dragging`, anchor ids `#mlp-*`, `#accreditations`, content-visibility
   ids), (c) **pattern map** — every pair/group of selectors with identical declarations,
   annotated with their *effective* value after polish overrides.
4. Commit harness + audit report (tools stay in `scripts/css-equivalence/` for reuse).

### Phase 1 — Dead CSS removal from landing.css

Deletions (each verified by the Phase-0 audit; nothing else):
- `.mlp-journey*` block + its media queries (~330 lines; section commented out in blade)
- `.archive-parallel*` block + media (~120 lines; compare commented out)
- `#mlp-class-snapshot .mlp-class-snapshot__global::after` media rules (2 blocks — pseudo-
  element never defined anywhere; no-ops)
- Any other selector the audit proves orphan (script decides — e.g. leftover
  `.archive-voices__voice` sub-rules superseded by `.luxury-testimonials`, unused
  `.mlp-section--void/--paper` if unconfirmed)
- **NOT** the first `#mlp-class-snapshot` block (partially live — merged in Phase 4).

Gate: zero style diff + zero pixel diff. Commit.

### Phase 2 — Dead CSS removal from polish.css

- All `.archive-investment*` rules (fees renders `pricing-cards`; zero references in views)
- `.topic-desk*` + `#mlp-class .archive-class__industries` restyle (target view is orphan)
- `.mlp-journey*` and `.archive-parallel*` override rules
- Unused tokens: `--mlp-polish-section-gap`, `--mlp-polish-ink-soft`, `--mlp-polish-red-soft`
- "29-point audit batch" — remove only the declarations that are **byte-identical** to the
  earlier Issue-1/2 blocks (diff proves it); anything that differs stays.
- "Final accreditation reference lock" (~120 lines) — remove only after proving, declaration
  by declaration, that the earlier "Canonical typography lock" already wins with the same
  value for every selector in scope (id-scoping raises specificity but values are identical).
- `@keyframes mlpTrustRecordBounce` redefinition inside `@media (max-width: 700px)` —
  **keep** (works, and changing it risks a 1px motion diff). Flag as "known quirk".

Gate: zero diff. Commit.

### Phase 3 — Merge the two files into one (the headline requirement)

- Concatenate cleaned landing.css + cleaned polish.css, **same rule order**, into
  `public/assets/css/pages/mba-masters-landing.css`, with a clear section banner comment
  (`/* ===== BASE LAYER (was mba-masters-landing.css) ===== */` /
  `/* ===== POLISH LAYER (was mba-masters-polish.css) ===== */`).
- Blade: remove the polish `<link>` line (only reference site — verified).
- Delete `public/assets/css/pages/mba-masters-polish.css` (mtime `?v=` bump happens
  automatically for the surviving file).
- This phase alone delivers "2 files → 1 file" with **provably** zero change: concatenation
  preserves the exact cascade of the two `<link>`s.

Gate: zero diff. Commit.

### Phase 4 — Dedup within the single file (CSS-only, no HTML edits)

Mechanical merges, each validated by the harness:
1. **Sibling-selector merges** where declarations + specificity class match:
   - `.mlp-mba__*` ↔ `.mlp-masters__*` twins (plate, plate-bar, uni-logo/initials, catalog,
     program rows, mark, media queries) → one rule with a grouped selector list
   - label/heading/eyebrow groups already partially grouped → complete them where identical
   - `@media` blocks: merge only where two adjacent blocks cover the same media query and the
     harness confirms no interleaved rule competes
2. **`#mlp-class-snapshot` merge**: fold the two blocks into one canonical block per property
   (effective value = whichever won today), keep both `__metric` display:grid (block 1) and
   card look (block 2) exactly as computed today.
3. **Duplicate declaration removal** inside the polish layer (the 3 typography lock blocks →
   collapse to one where the audit proves same winners; the 2 `--mlp-color-*` definitions → 1).
   Conservative rule: if any doubt, keep both.

Gate: zero diff. Commit.

### Phase 5 — Generic shared classes (CSS + class *additions* in blade)

Introduce a small, documented utility layer (values = **effective** values after polish
locks, so the page renders identically). Existing BEM classes stay untouched (JS + scoped
rules keep working); utilities only *add* to the class list, and the now-redundant per-section
declarations they fully cover are removed (harness-verified per property).

Planned utilities (final candidates from the pattern map):

| Utility | Replaces (×count) |
|---|---|
| `.mlp-eyebrow` — section label (0.875rem/500/0.15em/1.6/uppercase/`--color-mba-blue` + red-margin variants) | ~12 label classes |
| `.mlp-h2` — section heading (display, `--fs-section-title`, 500, 1.1, −0.02em, mba-blue) | ~15 heading classes |
| `.mlp-intro-grid` — `minmax(0,1.1fr) minmax(17rem,0.9fr)`, clamp gap, `align-items:end` + 1-col @900 | 4–6 section intros (class/career/alumni/learning/partners/video-proof) |
| `.mlp-icon-box` — 2rem square, 1px `rgba(178,2,2,.5)` border, red 1rem icon | 7 icon boxes |
| `.mlp-stack-plate` (+`--1/--2/--3` image layers, inset/rotate/opacity) | 4 image stacks (class/career/voices/closing) + topic-desk stack rules removed with it |
| `.mlp-wash` / `.mlp-contour` — decorative radial circle + rotated 1px line (position via modifiers/inline) | ~17 wash/contour spans |
| `.mlp-cta--primary` / `.mlp-cta--ghost` — red solid / navy outline roles (final locked values, 3rem min-height, 0 radius) | ~10 CTA pairs |
| `.mlp-hairline` — `border-bottom: 1px solid var(--mlp-line-dark)` row pattern (only where identical) | selected list rows |

Method: for each utility — add to CSS, add class to the matching blade elements, delete the
redundant per-section declarations, run the gate **per section group**. If any utility can't be
proven equivalent, drop that utility (the dedup in Phase 4 already captured the CSS-only wins).

Gate (per group): zero diff. Commit per group.

### Phase 6 — Final report + housekeeping

- Full harness re-run (all viewports, both JS-state passes) → expected: zero.
- Size report table: lines / bytes / gzip before → after (estimate: ~7,400 → ~4,300–4,800
  lines; gzip ~45 KB → ~32–36 KB).
- Update `MBM_LANDING_CSS_REVIEW.md` with the post-optimization state.
- Optional (only on request): minified build — default **off** (keep the file readable and
  diff-able; the page already loads it via `cached_asset`).

---

## 3. Risk register & mitigations

| Risk | Mitigation |
|---|---|
| Cascade reorder during merge | R1: strict original order; no cross-block moves; harness catches any slip |
| `!important` interactions | Never merge `!important` with normal; lock blocks removed only via per-declaration proof |
| Media-query order changes | Blocks keep original relative order inside the single file |
| JS-toggled state breaks | Protected list (all `data-*`, toggled classes, ids) is excluded from every transform |
| Shared `#accreditations` section breaks | All its patch rules carried over verbatim in Phase 3; kept in Phase 4 |
| Anchor jumps / content-visibility | ids + `content-visibility` rules untouched |
| Fixture DOM ≠ production DOM | Snapshot diff covers every element in the fixture; live-URL pass available as final check; JS-toggled states tested via forced-class pass |
| Sub-pixel font rendering noise | Same Chromium binary + flags for baseline and candidate; tolerance 0, investigate-not-ignore |
| Phase 5 utility mismatch | Per-section-group gates; drop utility on any diff |
| Accidental commit of a failing phase | Each phase is one commit; revert = one command |

## 4. Explicit non-goals

- No value changes of any kind (no "fixing" a color/size/spacing while at it)
- No renaming of existing classes (utilities are additive only)
- No JS edits (dead `polish.js` state classes + dead `class-topics.js` = separate follow-up)
- No blade structure changes (class attribute additions only, Phase 5)
- No changes to `main.css` or any other page's CSS

## 5. Rollout & status

| Phase | What | Gate | Status |
|---|---|---|---|
| 0 | Harness + baseline + audit | baseline captured | ⬜ |
| 1 | Dead CSS: landing | 0 diff | ⬜ |
| 2 | Dead CSS: polish | 0 diff | ⬜ |
| 3 | 2 files → 1 file + blade | 0 diff | ⬜ |
| 4 | CSS-only dedup / selector merges | 0 diff | ⬜ |
| 5 | Generic utilities (CSS + blade) | 0 diff per group | ⬜ |
| 6 | Report + housekeeping | full re-run 0 diff | ⬜ |
