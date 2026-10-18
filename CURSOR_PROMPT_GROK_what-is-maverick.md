# Cursor Prompt — What Is Maverick Section Fix (Grok AI Optimized)

> **Model:** Grok (xAI) in Cursor Agent Mode
> **Copy karo:** Neeche wala `--- PROMPT START ---` se `--- PROMPT END ---` tak ka block Cursor Chat me paste karo

---

### --- PROMPT START ---

You are an expert Frontend Animation Engineer specializing in GSAP 3 + ScrollTrigger + Laravel Blade.

#### CONTEXT
Project: Maverick Business Academy — Laravel + Vite + Tailwind v4 + GSAP + ScrollTrigger
You must fix content reveal timing/behaviour for ONE section only: **`#what-is-maverick`**

**Target Files:**
1. `resources/views/sections/what-is-maverick.blade.php` — HTML structure (DO NOT change structure unless absolutely needed)
2. `public/assets/js/animations.js` — function `initWIMAnimations()` — **MAIN FILE TO EDIT**
3. `public/assets/js/animations-utils.js` — shared utils (reference only, don't modify unless needed)
4. `public/assets/css/main.css` — styles for `.wim__*` + `.text-reveal-inner` (inspect for initial states)

#### CURRENT IMPLEMENTATION (Analyze before editing)
Open `public/assets/js/animations.js` lines ~522-700:

- `initWIMAnimations()` uses `gsap.matchMedia()` with two branches:
  - `mm.add("(max-width: 768px)", ...)` — **MOBILE**
  - `mm.add("(min-width: 769px)", ...)` — **DESKTOP**
- Each branch creates ONE `gsap.timeline` with `scrub` that animates `label`, `heading`, `statements`, `finalEl` together via `addWimStatementsToTimeline()` helper.
- Statements animate simultaneously via calculated `stmtGap` positions (0.22 to 0.78 zone).
- Initial state set via `setWimInitialState(cfg)` — sets `y` + `opacity: 0` for all elements.

CSS initial state in `public/assets/css/main.css`:
```css
.wim__statement { overflow: hidden; opacity: 0; }
.wim__statement-text { transform: translateY(110%); }
```

Blade structure:
```blade
<section id="what-is-maverick">
  <div class="wim__heading-wrapper">
    <div class="wim__label"> -> section label
    <h2 class="wim__heading">
  <div class="wim__statements">
    <div class="wim__statement" data-statement="1"> <span class="text-reveal-wrapper"><span class="wim__statement-text">
    ... x5 statements
```

#### OBJECTIVE (Strict Requirements)

Fix reveal timing & behaviour **ONLY FOR DESKTOP** (`min-width: 769px`). **DO NOT TOUCH MOBILE** (`max-width: 768px`) — leave it as-is.

**1. Section Label (`.wim__label`):**
   - Must reveal ONLY AFTER the section `#what-is-maverick` enters viewport.
   - Trigger: `start: "top 85%"` or similar — when section top hits 85% of viewport.
   - Animation: `y: 0, opacity: 1` with `power2.out`.
   - Must NOT animate on page load or before section is visible.

**2. Heading (`.wim__heading`):**
   - Must reveal AFTER section enters viewport, and AFTER label.
   - Should have slight delay after label (e.g., 0.1-0.15s in timeline position).
   - Trigger tied to same ScrollTrigger as section entry.
   - Animation: `y: 0, opacity: 1`.

**3. Statements (`.wim__statement` → `.wim__statement-text`):**
   - **CRITICAL:** Each `.wim__statement` must reveal **1-by-1 INDIVIDUALLY** as it comes into viewport — NOT all at once.
   - Current code batches them in one zone (0.22 to 0.78) — **THIS IS WRONG FOR DESKTOP, FIX IT.**
   - For desktop, each statement should have its **OWN ScrollTrigger** or **staggered trigger based on its own viewport entry**.
   - Suggested approach (pick best):
     - Option A: Loop statements and create individual `gsap.fromTo` or `gsap.to` with `scrollTrigger: { trigger: stmt, start: "top 85%" }` and `toggleActions: "play none none none"` (no scrub, clean reveal).
     - Option B: Keep single scrub timeline BUT ensure each statement animates at its own scroll position — use `ScrollTrigger.create` per statement that drives its opacity/y.
   - Inner `.wim__statement-text` should animate `y: "100%" → "0%"` + parent opacity `0 → 1`.
   - Statements must NOT reveal together — verify by scrolling slowly.

#### CRITICAL CONSTRAINTS

- ✅ **DESKTOP ONLY:** Only modify `mm.add("(min-width: 769px)", ...)` block. Copy-paste mobile block as-is, zero changes.
- ✅ Keep `mm.add("(max-width: 768px)", ...)` EXACTLY as it is now — do not refactor, do not extract.
- ✅ Preserve `prefersReducedMotion` check at top — keep reduced-motion fallback.
- ✅ Preserve `initWIMAnimations._mm` revert logic for HMR.
- ✅ Do NOT change Blade file structure unless required for reveal (prefer JS-only fix).
- ✅ Do NOT add new dependencies. Use existing GSAP + ScrollTrigger (already registered via `gsap.registerPlugin(ScrollTrigger)`).
- ✅ Keep `invalidateOnRefresh: true` for responsive safety.
- ✅ Follow `.cursorrules`: no duplicate sections, clean imports, execute all steps.

#### WHAT TO CHANGE vs WHAT TO KEEP

| Area | Action |
|------|--------|
| `mm.add("(max-width: 768px)")` | **DO NOT TOUCH** — leave 100% intact |
| `mm.add("(min-width: 769px)")` | **REWRITE** — implement sequential reveal logic |
| `setWimInitialState()` | Keep for desktop, adapt if needed for individual triggers |
| `addWimStatementsToTimeline()` | **DEPRECATE for desktop** — replace with per-statement ScrollTriggers |
| `prefersReducedMotion` block | Keep as-is |
| Desktop `scrub: 0.55` timeline | Remove or replace — per-statement triggers should use `toggleActions` not scrub for clean 1-by-1 reveal |

#### EXPECTED DESKTOP ANIMATION SEQUENCE

1. User scrolls → `#what-is-maverick` enters viewport (top 85%)
2. → Label fades/up reveals (0ms)
3. → Heading fades/up reveals (+100-150ms delay)
4. → Statement 1 reveals when IT enters viewport (top 80-85%)
5. → Statement 2 reveals when IT enters viewport (separately)
6. → Statement 3, 4, 5 — each independently on scroll
7. → Final text reveals at end

Each step must be visually distinct — no overlap of all statements animating at same scroll position.

#### ACCEPTANCE CRITERIA

- [ ] On desktop (1366px, 1440px, 1920px): label reveals only after section enters viewport
- [ ] Heading reveals after label, tied to scroll
- [ ] Statements reveal 1-by-1 on scroll — verified by slow scrolling, each statement animates separately
- [ ] Mobile (375px, 768px): behaviour 100% unchanged — test and confirm no regression
- [ ] No console errors, no ScrollTrigger warnings
- [ ] `prefers-reduced-motion: reduce` still shows all content statically
- [ ] Resize from mobile → desktop and back works (matchMedia revert tested)

#### DELIVERABLE

1. Edit ONLY `public/assets/js/animations.js` — `initWIMAnimations()` desktop branch.
2. If CSS initial state needs tweak for desktop reveal, edit `public/assets/css/main.css` under appropriate media query (`@media (min-width: 769px)`).
3. Run `npm run build` or `vite build` verification (if needed, just ensure no JS syntax errors).
4. Provide before/after explanation in chat.

Start by reading `public/assets/js/animations.js` (lines 510-710) and `resources/views/sections/what-is-maverick.blade.php`, then implement.

--- PROMPT END ---

---

## 📋 Prompt Usage Guide (Aapke liye)

### Cursor me kaise use karein:
1. **Cursor open karo** → `Ctrl+K` / `Ctrl+L` (Chat panel)
2. Model selector me **Grok** select karo (xAI)
3. Upar wala **PROMPT START se PROMPT END** tak ka block copy-paste karo
4. Agent Mode ON rakho

### Ye prompt Grok ke liye optimized kyun hai:
| Grok Preference | Is prompt me kaise handle kiya |
|----------------|-------------------------------|
| Direct + structured | Clear CONTEXT → OBJECTIVE → CONSTRAINTS → ACCEPTANCE flow |
| Code-first | Exact file paths + line numbers + current code snippets |
| No fluff | Table format me WHAT TO CHANGE vs KEEP |
| Actionable | Suggested Option A/B diye — Grok khud best pick kar sakta hai |
| Desktop-only guardrail | 3 jagah explicitly bola: MOBILE MAT CHHEDNA |

### Original Objective (Reference):
> `@resources/views/sections/what-is-maverick.blade.php` Is section ke content reveal ka time and behaviour fix karna padega:
> 1. (section label) section ke screen pe aane ke baad reveal hoga
> 2. heading screen pe aane ke baad reveal hoga
> 3. uske baad jaise jaise (wim__statement) screen par aayenge wese wese 1-by-1 text-reveal honge, ek sath nahi hone chahiye.
> ye changes only for desktop ke liye hona chahiye, mobile pe as it is hi rehne dena.

---

### 💡 Pro Tip for Grok in Cursor:
Agar Grok pehle attempt me mobile bhi change kar de, toh follow-up me ye bhejo:
```
Mobile ka code touch mat karo. Sirf mm.add("(min-width: 769px)") wala block change karo. Mobile wala mm.add("(max-width: 768px)") exact same rehne do.
```

**File location:** `/CURSOR_PROMPT_GROK_what-is-maverick.md` (ye file)
**Branch:** `arena/208b0dc4-maverick`
