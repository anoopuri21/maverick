# Faculty Insights — Redesign Plan (Professional Single-Card Slider)

> Section: `#faculty-insights` — Home Page
> Goal: Design system ke according, single card per slide, left 1/3 image + right 2/3 content, fancy LinkedIn CTA, graduation-cap watermark background

## 1. Current Audit (Existing Implementation)

**File:** `resources/views/sections/faculty-insights.blade.php`
- Horizontal scroll-row with multiple 280px cards (`flex: 0 0 280px`)
- Card: image top (3/3.3 ratio) + body bottom
- Simple border, no shadow, minimal styling
- LinkedIn link: small icon + "LinkedIn" text, low emphasis
- Toggle: Read more / Read less with clamp 4 lines
- No background decoration, empty feel on right side
- Scroll amount fixed 420px in `scroll-controls.js`

**CSS:** `public/assets/css/main.css` Section 12
- Light theme, white background, #e5e5e5 border
- No design system depth (shadows, radius, accent gradients)
- No responsive grid for single card

**JS:**
- `faculty-insights-toggle.js` — works on `[data-fi-card]`, clamp logic ok
- `scroll-controls.js` — drag + momentum + arrow buttons, fixed scroll amount

---

## 2. Design Vision — As Experienced Graphic + Web Designer

### Design Principles (Maverick Design System)
- **Colors:**
  - Primary: `--color-mba-blue: #0f2983` (trust, academic)
  - Dark: `--color-mba-dark-blue: #071444` (depth, premium)
  - Accent: `--color-mba-red: #b20202` (CTA, highlight)
  - Base: `--color-white`, `--color-warm-white: #f5f0eb`
  - Text: `rgba(10,10,10,0.68)` for body, `#071444` for headings
- **Typography:**
  - Display: `PP Neue Montreal` for titles (`--font-display`), `clamp(26px,3vw,36px)`, `fw-semibold`, `-0.02em` tracking
  - Body: `Poppins` for content (`--font-body`), 16px/1.8, 400/300
  - Captions: 13px uppercase, 0.12em letter-spacing for role/country
- **Shape & Depth:**
  - Card radius: 24px (premium, modern SaaS/edu)
  - Border: 1px solid rgba(15,41,131,0.08)
  - Shadow: `0 24px 64px -24px rgba(7,20,68,0.18)` + `0 8px 24px -12px rgba(7,20,68,0.12)`
  - Left image 38% / Right content 62% on desktop (1/3 ≈ 33%, we use 38% for better visual balance + breathing room)
- **Professional Touches:**
  - Top accent bar: 4px gradient blue → red
  - Quote icon circle (48px, rgba(15,41,131,0.06) bg) + kicker "Faculty Insight"
  - Role as pill: red tint bg `rgba(178,2,2,0.08)`, red text, 999px radius
  - Country with map-pin icon, blue
  - Divider: 56px gradient line blue→red
  - Excerpt: generous line-height, max-width 60ch
  - Footer: top border `rgba(0,0,0,0.06)`, space-between toggle + LinkedIn CTA

### Layout: Single Card Slider
- **Track:** `display:flex; width:100%; gap:32px;` Each card `flex:0 0 100%` → one card visible
- **Container:** `overflow-x:auto; scroll-snap-type:x mandatory; scrollbar hidden`
- **Controls (New):**
  - Below card: flex row justify-between
  - Left: pagination dots (8px → 24px active, blue/red)
  - Center: counter `01 / 05` (PP Neue Montreal, tabular nums)
  - Right: nav group with prev/next circular buttons (48px, white bg, border, shadow, hover dark-blue bg + white icon)
- **Drag:** Keep existing drag-momentum from `scroll-controls.js` but scroll amount = container.clientWidth (full card)
- **Responsive:**
  - >1024px: 38/62 grid, min-height 480px, image min-height 480px
  - 768-1024: 42/58, padding 36px
  - <768: stacked: image top 320px height, content below, controls centered, watermark smaller (200px)

### Fancy LinkedIn CTA
- **Spec:** `Connect on LinkedIn` text + icon
- **Style:**
  - Pill button: `padding:12px 20px; border-radius:999px; border:1px solid rgba(15,41,131,0.14); background:white; box-shadow:0 4px 14px -6px rgba(15,41,131,0.15)`
  - Icon: 18px LinkedIn SVG, fill `#0A66C2`, inside circle? Use inline SVG path, currentColor technique
  - Hover: bg `#0A66C2`, border `#0A66C2`, color white, translateY(-1px), shadow `0 12px 24px -8px rgba(10,102,194,0.35)`
  - Transition: `cubic-bezier(0.23,1,0.32,1) 0.35s`
  - Accessibility: `aria-label`, `rel noopener`, `target blank`

### Graduation-Cap Watermark
- **Placement:** absolute top-right, `top:-10px; right:-10px; width:320px; height:320px; opacity:0.06; rotate:-12deg; pointer-events:none; z-index:1`
- **Icon:** Lucide `graduation-cap` style SVG (big, stroke 1.2, color `var(--color-mba-blue)`)
- **Purpose:** Fill empty background, academic cue, very subtle
- **Alternative:** Could use custom SVG with cap + tassel, 2-tone? Keep single color for minimalism

### Additional Professional Polish
- **Card hover:** subtle lift `transform:translateY(-2px)` + shadow increase, image scale 1.03
- **Image overlay:** bottom gradient `linear-gradient(to top, rgba(7,20,68,0.45), transparent 60%)` for depth
- **Accent corner:** optional top-left small red dot? Skip to keep clean
- **Animation:** fade-up still works, plus quote icon scale-in on scroll

---

## 3. Technical Implementation Plan (Laravel + Blade + CSS + JS)

### A. Blade: `resources/views/sections/faculty-insights.blade.php`
- Keep `$facultyInsights` collection, `$homepageChrome` header
- Wrap slider in `<div class="insights__slider" data-fi-slider>`
- Inside: 
  - `<div class="insights__scroll" data-scroll-container data-fi-container>`
  - `<div class="insights__track" data-fi-track>`
- Card markup rewrite:
  ```blade
  <article class="insights__card">
    <div class="insights__card-watermark">SVG graduation-cap 320px</div>
    <div class="insights__card-media">
      <div class="insights__card-image"><img></div>
      <div class="insights__card-media-overlay"></div>
    </div>
    <div class="insights__card-body">
      <div class="insights__card-top">
        <div class="insights__card-quote"><svg quote></div>
        <span class="insights__card-kicker">Faculty Insight</span>
      </div>
      <h3 class="insights__card-title">...</h3>
      <div class="insights__card-meta">
        <span class="insights__card-role">...</span>
        <span class="insights__card-country"><svg pin> + country</span>
      </div>
      <div class="insights__card-divider"></div>
      <p class="insights__card-excerpt" data-fi-excerpt>...</p>
      <div class="insights__card-footer">
        <button class="insights__card-toggle" data-fi-toggle>Read more <svg arrow></button>
        <a class="insights__card-linkedin" href="..."><svg linkedin><span>Connect on LinkedIn</span></a>
      </div>
    </div>
  </article>
  ```
- After track: controls
  ```blade
  <div class="insights__controls">
    <div class="insights__pagination" data-fi-pagination></div>
    <div class="insights__counter"><span data-fi-current>01</span><span class="insights__counter-sep">/</span><span data-fi-total>05</span></div>
    <div class="insights__nav">
      <button class="insights__nav-btn insights__nav-btn--prev" data-fi-prev aria-label="Previous"> <svg chevron> </button>
      <button class="insights__nav-btn insights__nav-btn--next" data-fi-next aria-label="Next"> <svg chevron> </button>
    </div>
  </div>
  ```
- Keep `fade-up` classes for GSAP
- Add `@push('scripts')` for new slider JS if needed

### B. CSS: `public/assets/css/main.css` Section 12 Replace
- Remove old `.insights__card { flex:0 0 280px }` etc
- Write new block (see final CSS file) with:
  - `.insights` base + subtle radial gradient bg `radial-gradient(circle at 85% 15%, rgba(15,41,131,0.04), transparent 40%)`
  - `.insights__slider`, `__scroll`, `__track` for single-card
  - `.insights__card` grid 38/62, radius 24, shadow, border, relative
  - `__card-watermark` big cap icon, opacity 0.05-0.07
  - `__card-media`, `__card-image` 100% height, min-height 480, overlay
  - `__card-body` padding 44/48, flex column, gap
  - `__card-top`, `__card-quote` (48px circle), `__card-kicker` (13px uppercase, blue, 0.12em)
  - `__card-title` display font, 26-36px, dark-blue
  - `__card-meta` flex wrap gap 12, role pill red tint, country with icon blue
  - `__card-divider` 56px gradient
  - `__card-excerpt` Poppins 16/1.8, color rgba(10,10,10,0.68)
  - `__card-excerpt--clamp` -webkit-line-clamp 4
  - `__card-footer` border-top, padding-top 24, flex space-between
  - `__card-toggle` blue text, hover red, with arrow
  - `__card-linkedin` fancy pill (spec above)
  - `__controls`, `__pagination`, `__pagination-dot`, `__counter`, `__nav`, `__nav-btn`
  - Responsive breakpoints 1024, 768, 480
  - Hover effects, focus-visible outlines

### C. JS: `scroll-controls.js` Update
- In `smoothScrollBy`, check if container closest `.insights__scroll` → amount = container.clientWidth + 32
- Or in initScrollRow, if row has `.insights__scroll`, set SCROLL_AMOUNT = container.clientWidth
- Keep drag logic same

### D. JS: New `faculty-insights-slider.js`
- Select `[data-fi-slider]`
- Generate dots: for each card, create button `.insights__pagination-dot` with aria-label
- Update active dot + counter on scroll (throttled RAF)
- Click dot → scrollTo index * container.clientWidth
- Prev/Next → scroll to index +/-1 with smooth behavior
- Sync with `scroll-controls.js` scroll event (listen to scroll)
- Handle resize: recalc
- Accessibility: keyboard arrow left/right

### E. JS: `faculty-insights-toggle.js` Keep but ensure class names match
- Still uses `[data-fi-excerpt]` and `[data-fi-toggle]`, so no change needed
- Maybe add icon rotation for toggle

### F. Layout: `layouts/app.blade.php`
- Add `<script src="assets/js/faculty-insights-slider.js" defer>` after toggle

---

## 4. Expected Outcome
- Single professional card per slide, 1/3 image left, 2/3 content right
- Design system colors: blue headings, red accents, warm-white section bg
- Typography: PP Neue Montreal for titles, Poppins for body
- Fancy LinkedIn CTA: pill, icon + "Connect on LinkedIn", hover blue
- Graduation cap watermark top-right big, opacity ~0.06
- Controls: dots + counter + circular nav, drag/swipe support, responsive
- No breaking of existing data flow (Laravel blade still uses same variables)

---

## 5. QA Checklist
- [ ] Desktop: card 38/62 grid, image covers left, watermark visible but subtle
- [ ] Mobile: stacked, image 320px, content padding 24-28, controls centered
- [ ] Slider: one card per view, snap, drag works, prev/next scrolls full width
- [ ] Pagination dots active state, counter updates
- [ ] LinkedIn button: icon + text, hover fancy, opens new tab
- [ ] Read more toggle: clamp 4 lines, expands, accordion behavior
- [ ] Colors: blue #0f2983, dark-blue #071444, red #b20202, warm-white #f5f0eb used
- [ ] Typography: display font for title, body font for excerpt
- [ ] No console errors, Lenis + GSAP still work
- [ ] Accessibility: aria-labels, keyboard nav, focus states

---

Ready to implement.
