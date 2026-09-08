# Faculty Insights — Fix Plan (Visibility + CLS + Toggle Removal)

## Issues Reported
1. **Faculty Insight visible nahi ho raha due to fade-up class** — slider wrapper has `fade-up` but no GSAP trigger, so stays `opacity:0`
2. **Animate requirement:** Screen pe 40% aate hi down-to-top slide animate hote hue visible hona chahiye
3. **Remove Read more / Read less toggle** — show full content, avoid CLS
4. **Core Web Vitals (CLS) ka dhyaan** — no layout shift

---

## Root Cause Analysis

### A. Visibility Bug
- **CSS:** `html.js .fade-up { opacity:0; transform: translateY(40px); }` hides element until GSAP animates
- **Blade:** `<div class="insights__slider fade-up">` has fade-up but **animations.js** has no `fadeUp` call for `.insights__slider`
- **Animations defined:** Only subtitle + card slideIn (x:0,y:0) — card animation uses `slideIn` with x:0,y:0 which is effectively just opacity, not y movement, and trigger is `.insights__scroll` not section
- **Result:** Slider stays invisible
- **Extra:** `.insights__card { transform: translateY(0) !important; }` blocks GSAP y animation even if triggered

### B. Toggle + CLS
- **Current:** Excerpt clamped to 4 lines via `.insights__card-excerpt--clamp` + JS measures `scrollHeight` vs `clientHeight` to show/hide button
- **JS evaluation** happens after fonts load + resize — causes layout shift (CLS) because:
  - Button appears/disappears after paint
  - Expanding from 4 lines to full height shifts content below slider (counter, nav) and next section
- **Image:** Has width/height attributes but container height is `min-height:520px` + `height:100%` — if image loads late, height may shift
- **Pagination dots:** Generated via JS after DOMContentLoaded — if controls container has no min-height, dots appearing causes shift

### C. Core Web Vitals Impact
- **CLS:** Toggle, dots, image, font → cumulative shift
- **LCP:** Faculty image could be LCP if large — need `fetchpriority`? But single card, first image should have `loading="eager"` maybe? However spec says `loading="lazy"` currently — okay but we can keep lazy for non-first? For CLS, need fixed dimensions
- **FID/INP:** Toggle JS adds listeners — removing reduces JS

---

## Fix Strategy (Professional)

### 1. Visibility Fix — 40% Viewport Trigger + Down-to-Top Slide

**Goal:** Section ke top jab viewport ke 60% pe aaye (matlab 40% section visible), tab card bottom se top slide hoke visible ho

**Implementation:**
- **Blade:** Remove `fade-up` class from `.insights__slider` and cards. Replace with `insights__reveal` class that has initial state set via GSAP, not CSS hidden
- **CSS:** 
  - Remove `transform: translateY(0) !important` from `.insights__card` — let GSAP animate transform
  - Add fallback: `.insights__slider, .insights__card { opacity:1; transform:none; }` for no-JS (progressive enhancement) — hide only when `html.js`? Actually we will set initial via GSAP `gsap.set` not CSS, so no FOUC
  - Ensure `.insights__card` has `will-change: transform, opacity` only during animation, not always
- **animations.js — initInsightsAnimations():**
  ```js
  function initInsightsAnimations() {
    if (!elementExists("#faculty-insights")) return;
    if (AnimationUtils.prefersReducedMotion) { /* clearProps */ return; }

    // Header animations — trigger when 40% visible (top 60%)
    AnimationUtils.sectionLabel("#faculty-insights"); // will update its start to top 60%?
    // Update textReveal and fadeUp to start: "top 60%"
    AnimationUtils.textReveal("#faculty-insights .insights__heading-line .text-reveal-inner", { 
      trigger: "#faculty-insights", 
      start: "top 60%" 
    });
    AnimationUtils.fadeUp("#faculty-insights .insights__subtitle", { 
      trigger: "#faculty-insights", 
      start: "top 60%", 
      y: 30 
    });

    // Slider — down to top slide
    // Initial set via GSAP: opacity 0, y 40
    gsap.set("#faculty-insights .insights__slider", { opacity:0, y:40 });
    gsap.to("#faculty-insights .insights__slider", {
      opacity:1, y:0, duration:0.8, ease:"power3.out",
      scrollTrigger: {
        trigger: "#faculty-insights",
        start: "top 60%", // 40% visible
        toggleActions: "play none none none",
        once: true
      }
    });

    // Card — also down to top, but slightly delayed for polish
    gsap.set("#faculty-insights .insights__card", { opacity:0, y:40 });
    gsap.to("#faculty-insights .insights__card", {
      opacity:1, y:0, duration:0.7, stagger:0.12, ease:"power3.out",
      scrollTrigger: {
        trigger: "#faculty-insights .insights__scroll",
        start: "top 65%",
        toggleActions: "play none none none",
        once: true
      }
    });
  }
  ```
  - Use `once:true` so animation happens only once, no re-trigger CLS
  - `start: "top 60%"` = when section top hits 60% viewport height → 40% visible
  - Down-to-top = y:40 → y:0

- **Fallback for no-JS:** In CSS, if `html.no-js` or `html:not(.js)`, cards visible. Since we add `js` class via `<script>document.documentElement.classList.add('js')</script>` in layout, we can have:
  ```css
  html.js .insights__slider,
  html.js .insights__card { opacity:0; transform: translateY(40px); }
  ```
  But GSAP will set initial and animate. If GSAP fails, we need to ensure after 1s they become visible via fallback timeout

### 2. Remove Read More / Read Less + Show Full Content (CLS Safe)

**Goal:** Full content visible, no toggle, no CLS

**Implementation:**
- **Blade:**
  - Remove button `.insights__card-toggle` completely
  - Remove `data-fi-excerpt` and `data-fi-toggle` attributes (or keep excerpt but without clamp)
  - Remove `id="fi-excerpt-{{id}}"`
  - Show full description: `<p class="insights__card-excerpt">{{ $desc }}</p>` without clamp class
  - Remove `hidden` logic
  - Keep `media_url` image with `width`/`height` + `aspect-ratio` container to prevent CLS
- **CSS:**
  - Remove `.insights__card-excerpt--clamp` rule (or keep but not used)
  - `.insights__card-excerpt` → no line-clamp, `display:block`, `overflow:visible`
  - `.insights__card-footer` → simplify: only LinkedIn CTA, justify-content flex-end
  - Remove toggle styles (or keep but unused)
  - Ensure card has `min-height` but `height:auto` — content growth is natural, not JS-driven, so no CLS after load
  - Reserve space for image: `.insights__card-media { aspect-ratio: 4/5; min-height:520px; }` + `img { width:100%; height:100%; }` with fixed dimensions prevents shift
- **JS:**
  - Delete or empty `faculty-insights-toggle.js` — or make it no-op with check
  - Since toggle removed, no JS measures scrollHeight → no layout shift
  - Update `faculty-insights-slider.js` to not call `__fiUpdate` after toggle (since toggle removed)

### 3. CLS Core Web Vitals Hardening

**A. Images:**
- Keep `width="480" height="600"` attributes
- Add `style="aspect-ratio:480/600"` inline? Or CSS `aspect-ratio`
- Add `decoding="async"` already, keep `loading="lazy"` but first card `loading="eager"` + `fetchpriority="high"` to improve LCP if faculty is near viewport? However faculty is below fold (after many sections), so lazy is okay for CLS
- Container: `.insights__card-image { aspect-ratio: 4/5; }` + `overflow:hidden` reserves space before image loads

**B. Pagination Dots:**
- Generate dots **statically in Blade** as fallback (server-side) to reserve space, then JS enhances active state
- Or set `min-height: 24px` for `.insights__pagination` and `min-height:48px` for `.insights__nav` so even before JS, space reserved
- Implement both: Blade outputs dots with first active, JS just updates

**C. Counter:**
- Reserve space with `min-width` for numbers (tabular-nums already) — no shift when 01→02

**D. Font:**
- Poppins and PP Neue Montreal already with `font-display:swap` via Google Fonts — ensure fallback font metrics similar? Not in scope, but we avoid layout shift by not changing font size after load

**E. Slider Track:**
- Track `width:100%` not `max-content` anymore? For single card, `max-content` not needed, but we keep flex 0 0 100% — width stable
- Container `min-height:520px` reserved

**F. Animation:**
- Use `transform` and `opacity` only (GPU accelerated) — no height/width animation that causes CLS
- Use `will-change` only during animation, then clear

### 4. Code Clean Up

- Remove `data-fi-excerpt`, `data-fi-toggle` if not needed
- Remove toggle JS file from layout or make empty
- Keep `faculty-insights-slider.js` lean
- Ensure no unused CSS (remove old toggle styles if desired, but keep for backward compat)
- Verify no `!important` except where needed (remove `transform: translateY(0) !important` from card)

---

## Execution Steps

1. **Edit Blade** (`resources/views/sections/faculty-insights.blade.php`):
   - Remove `fade-up` from slider
   - Remove toggle button block
   - Remove clamp logic, show full content
   - Add static pagination dots in Blade (loop count)
   - Ensure image has dimensions + eager for first card

2. **Edit CSS** (`public/assets/css/main.css`):
   - Remove `transform: translateY(0) !important` from `.insights__card`
   - Remove or neutralize `.insights__card-excerpt--clamp`
   - Add `html.js .insights__slider { opacity:0; transform: translateY(40px); }` fallback + `html:not(.js)` visible
   - Add min-height reserves for pagination, counter, nav
   - Simplify footer to flex-end when only LinkedIn
   - Ensure image container aspect-ratio to prevent CLS

3. **Edit animations.js** (`public/assets/js/animations.js`):
   - Update `initInsightsAnimations` to trigger at `top 60%` (40% visible)
   - Animate slider + card from y:40 to y:0, opacity 0→1
   - Use `once:true`

4. **Edit / Remove toggle JS** (`public/assets/js/faculty-insights-toggle.js`):
   - Make no-op or delete file content, keep file to avoid 404 but empty
   - Or remove script tag from layout

5. **Update slider JS** (`public/assets/js/faculty-insights-slider.js`):
   - Remove dependency on toggle update
   - Ensure dots already exist from Blade, just sync active

6. **Test CLS:**
   - Check no layout shift on load, scroll, resize
   - Check card visible at 40% viewport

7. **Commit & Push Clean**

---

## Expected Outcome
- Faculty card visible when 40% section enters viewport, with smooth bottom-to-top slide (y 40→0)
- No fade-up stuck invisible bug
- Full content visible, no Read more/less
- CLS <0.1, no shifts from images, dots, toggle
- Core Web Vitals safe
