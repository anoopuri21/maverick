# CSS Equivalence Harness — MBM Landing

Automated **zero-visual-change gate** for the MBM landing CSS work
(2-file merge → shared classes → optimization → dead CSS removal).

The acceptance criterion is: *no CSS change may alter the rendered page,
even by 1%*. This harness proves it by diffing, for every page element,
the **cascade winner per CSS property** before and after a change — across
5 DOM states × 11 viewports × 2 motion settings, including hover /
focus / focus-visible / focus-within contexts.

No browser exists in this sandbox, so pixel diffing is impossible; the
cascade-winner table is the strongest guarantee available (same winner +
same resolved value ⇒ same computed style ⇒ same paint).

## Quick start

All commands run from `scripts/css-equivalence/`.

```bash
# 1. Rebuild the DOM fixtures (only when blades / data.js / providers change)
CSS="assets/css/pages/mba-masters-landing.css,assets/css/pages/mba-masters-polish.css"
for s in s0 s1 s2 s3 s4; do
  node src/build-fixture.js $s "$CSS" fixture/mbm-$s.html
done

# 2. Freeze the baseline (only when fixtures or global CSS change — NOT after CSS edits)
node src/gate.mjs --capture "$CSS"

# 3. The gate: edit target CSS, then
node src/gate.mjs "$CSS"        # exit 0 = PASS, 1 = FAIL, 2 = error

# 4. Selector coverage audit (dead/shadowed/live) — feeds the dead-CSS phase
node src/audit.mjs "$CSS"

# 5. Diff any two table directories (dev tool)
node src/compare.mjs baseline cand-tables [maxReport]
```

`$CSS` paths are relative to `public/`. The gate always feeds
`[main.css, our-story.css, responsive.css, ...targets]` — the three global
files are treated as fixed input; editing them diffs against the frozen
baseline (conservative: a global edit that produces identical computed
styles still passes, anything else fails).

## What the gate compares

`baseline/` holds one gzipped JSON table per
`<state>-<viewport>-<motion>` (5 × 11 × 2 = **110 tables**) plus
`meta.json` with `@keyframes` signatures. Each table:

```jsonc
{
  "n": 1139,                                  // element count
  "elements": [                               // document order
    ["body>header>…:nth", { "color": "#111", "padding-top": "40px" }],
    ...
  ],
  "ctx": {                                    // interactive contexts
    "hover":           { "42": { "transform": "translateY(-2px)" } },
    "focus-visible":   { "10": { "outline": "2px solid #0a84ff" } },
    ...
  }
}
```

- `elements[i]` = `[path, {prop: effectiveValue}]` — the path is a
  stable structural key (`tag#id.class:ordinal`).
- Base values are **var()-resolved and inheritance-resolved** effective
  values; properties with no winner are omitted; an invalid `var()`
  resolves to `__initial__`.
- `ctx` records properties whose winner comes from an interactive rule
  (hover/focus/focus-visible/focus-within), resolved in that context.
- `@keyframes` bodies are compared by signature hash (they only affect
  animated states, which the static tables cannot capture).

A PASS means: identical element count, identical paths, identical
per-property winners, identical `ctx` deltas, identical keyframe
signatures — in all 110 tables.

## Fixtures and states

`src/build-fixture.js` renders the real Blade partials
(`resources/views/pages/mba-masters-landing/*`, shared sections) through
`src/blade-lite.js` (a minimal Blade compiler: `@foreach` / `@if` /
`@php` / `@include` / `{{ }}` / `@push`) with fixture data from
`src/data.js` and per-partial computed variables from `src/providers.js`
(mirrors the `@php` blocks in the blades, e.g. the `$flip = $ui % 2 === 1`
showcase alternation).

| state | DOM |
|-------|-----|
| `s0` | no JS — `<html>` without `.js`; hero not assembled; no JS-toggled classes |
| `s1` | JS final — `class="js"`; hero `.is-hero-assembled`; blueprint `.is-inview`; accreditation slider `.is-landing-slider` |
| `s2` | JS pre-assembly — `class="js"`; hero **not** assembled (baseline hero rules active) |
| `s3` | JS + one prose block offscreen — s1 + `.is-offscreen` on first `.mlp-prose` |
| `s4` | JS + form result state — s1 + success/error/validation banners before each `<form class="mlp-form__fields">` (session('success')/session('error')/$errors) |

Fixture data mirrors production content: 3 MBA tabs (Rushford 10
specializations; GAU tab with **two** universities — GAU + Rushford — so
the `--flip` showcase variant renders; GAU EMBA 1), masters ledger,
pricing, testimonial carousel, etc.

## Engine

`src/engine.mjs` (jsdom + postcss):

1. Loads the fixture into jsdom per state.
2. Parses all 5 CSS files in load order; records rules with source order,
   media context (`max-width`/`min-width`/`prefers-reduced-motion` only —
   the only media features used by these files), and per-comma-part
   selectors with computed specificity (`:not`/`:is`/`:has` = max of args,
   `:where` = 0, pseudo-elements stripped before the calc).
3. For every element × property, walks the candidate rules (matches
   precomputed once — the DOM is identical across viewports/motions) and
   picks the winner by **(important, inline, specificity, source order)**;
   inline styles are pass `1<<30`.
4. Resolves `var()` chains (cycle-guarded) and inheritance
   (`color`, `text-align`, `opacity`, `visibility`, `font-*`, etc.).
5. Repeats per viewport (media evaluation) and per motion setting.

## Known limitations (documented, consistent both sides)

- `@keyframes` bodies: signature-hash compared, not cascade-diffed.
- Custom properties (`--*`): skipped in the per-property diff. A removed or
  changed definition only matters through `var()` consumers; any such change
  surfaces on the *consuming* real property (color/gap/…), which is compared.
  An orphaned definition (no consumer) is visually inert by definition.
- Global CSS edits: diff against the frozen baseline (safe, conservative).
- Unknown/unsupported pseudo-elements (e.g. `::-moz-selection`): both
  sides log a match-warning and skip them identically.
- Lucide icon swap (`<i data-lucide>` → `<svg>` at runtime) is not in the
  static fixture, so descendant-`svg` icon selectors are uncovered — on
  both sides, identically.
- The trust section's `signal-atlas` SVG is built at runtime by
  `mba-masters-trust.js`; the blade only ships `signal-atlas__graph` +
  records, so its internals are uncovered — identically both sides.
- Transient states beyond s0–s4 (e.g. mid-animation, modal open) are not
  covered.

## Baseline discipline

- **Re-capture** (`--capture`) only when: fixtures change (blades /
  `data.js` / `providers.js` / `blade-lite.js`), the global CSS changes,
  or the target file *set* changes.
- **Never** re-capture after an ordinary CSS edit to the targets — that
  would launder the edit into the baseline.
- The gate is deterministic: same inputs ⇒ byte-identical tables.

## Verified sensitivity (diff matrix)

Against the same frozen baseline, state/viewpoint/motion swaps produce
real diffs (proof the gate is not vacuous):

| comparison | result |
|------------|--------|
| s1 @1440 vs s1 @390 | 749 element-prop diffs |
| s1 @1440 vs s1 @950 | 61 |
| s1 no-preference vs reduce | 421 |
| s1 vs s2 (hero pre-assembly) | 25 |
| s1 vs s0 (no JS) | 7 |
| s1 vs s4 (form banners) | element count +8, downstream paths shift |

## Files

| path | purpose |
|------|---------|
| `src/blade-lite.js` | minimal Blade compiler (tokenize + compile + evaluator with scope layers) |
| `src/data.js` | fixture content data (tabs, universities, programs, copy) |
| `src/providers.js` | per-partial variable providers (mirrors blade `@php` blocks) |
| `src/build-fixture.js` | renders `fixture/mbm-s{0..4}.html` for a state |
| `src/engine.mjs` | cascade-winner table builder (jsdom + postcss) |
| `src/gate.mjs` | baseline capture + candidate comparison (the gate) |
| `src/audit.mjs` | per-selector LIVE / SHADOWED / DEAD coverage report |
| `src/compare.mjs` | standalone directory-vs-directory table diff (dev) |
| `src/check-tables.mjs`, `src/selector-probe.mjs` | dev tools (table sanity, single-selector match probe) |
| `fixture/mbm-s{0..4}.html` | rendered DOM fixtures |
| `baseline/` | frozen reference (110 tables + `meta.json`) |
| `cand-tables/` | rebuilt on every gate run (ephemeral) |
