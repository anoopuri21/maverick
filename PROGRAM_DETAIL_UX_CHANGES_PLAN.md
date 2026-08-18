# Program Detail — UX Changes Plan (Enterprise-Grade)

Branch: `dev/overall-development`
Files to edit:
- `resources/views/pages/programs/detail.blade.php` (markup)
- `public/css/pages/program-detail.css` (styles)

---

## Change 1 — Hero CTA buttons

**Current state (lines 86–90):**
```html
<div class="hero-ctas rv rv-d2">
  <a href="#enquire" class="btn btn-red">Apply Now<svg>→</svg></a>                 <!-- arrow AFTER text -->
  <a href="{{route('contact')}}" class="btn btn-outline">Download Brochure<svg>↓</svg></a>
  <a href="#enquire" class="btn btn-outline">Enquire Now</a>                        <!-- NO arrow -->
</div>
```

**Request:** Add arrow SVG to **Enquire Now** too, and remove the **trailing arrow icon** ("after ka arrow").

**My interpretation (enterprise-standard, consistent):**
Make all 3 hero CTAs **leading-arrow** style — arrow icon placed **BEFORE** the label, and remove the **trailing** arrow after the label:
```html
<a class="btn btn-red"><svg>→</svg> Apply Now</a>
<a class="btn btn-outline"><svg>→</svg> Download Brochure</a>
<a class="btn btn-outline"><svg>→</svg> Enquire Now</a>
```
- Uniform arrow (single `→` style, right-chevron) on all three.
- Consistent `gap` from existing `.btn` CSS (`gap:10px`).
- ⚠️ **Flag:** "remove the after arrow" could alternatively mean "keep trailing arrows and just add to Enquire". I will **confirm this one** before coding (see questions).

---

## Change 2 — Recognition label text

**Current (line 121):**
```html
<span class="lab">Recognised / Accredited</span>
```
**Change to:**
```html
<span class="lab">Recognised &amp; Accredited</span>
```
(HTML entity `&amp;` so it renders as `&`.)

---

## Change 3 — Recognition logo slider: logos only, bigger, highlighted

**Current (lines 126–131, duplicated for marquee):**
```html
<div class="rec-card">{!! $renderLogoChip(...,'rec-logo',...) !!}<div><div class="t">{{name}}</div><div class="s">{{note}}</div></div></div>
```
Currently: logo chip (46px) + text block (name + note).

**Change to:** Show **only the logo**, larger, with a highlight ring on hover.
- Remove the text wrapper (`.t`, `.s`).
- Keep `$renderLogoChip` (it still emits the logo `<img>` inside `.rec-logo`, with initials fallback if no logo).
- Enlarge logo chip via CSS: `.rec-logo` 46px → **~96–110px**, square-ish, centered, generous padding; card becomes a clean logo tile.
- Highlight: hover glow / navy ring to make logos stand out; white tile background for contrast.
- Content-agnostic: if a logo URL is missing, the initials fallback chip renders (bigger, centered).

**CSS changes (`program-detail.css` lines 149–154):**
- `.rec-card`: remove min-width text fit → make a clean centered tile (e.g. `padding:20px; min-width:120px; justify-content:center`).
- `.rec-logo`: width/height ~104px, larger radius, `background:#fff`.
- `.rec-logo img`: keep `object-fit:contain` (never crop, clean).
- Add `.rec-card:hover` ring/glow highlight.

---

## Change 4 — About the University section

**Current (lines 290–301):**
```html
<div class="gau-copy">
  <h2>...</h2>
  @if($university->description)<p>{!! $university->description !!}</p>@endif   <!-- may contain <li> items -->
  <div class="gau-metrics">
     <div class="gau-metric"> ... Established ... </div>      <!-- keep (1st) -->
     <div class="gau-metric"> ... Intl / International outlook ... </div>  <!-- REMOVE (2nd) -->
  </div>
  <div class="gau-tick">Internationally focused curriculum</div>  <!-- REMOVE static -->
  <div class="gau-tick">Designed for the global workplace</div>   <!-- REMOVE static -->
</div>
```

**Changes:**
1. **`li` list styling (conditional):** if the university `description` contains list items (`<li>`), style each with a **red dot + ring animation**. Since `description` is RichEditor HTML, target `.gau-copy li` (and its parent `ul`/`ol`) in CSS:
   ```css
   .page-pd .gau-copy ul, .page-pd .gau-copy ol { list-style:none; padding-left:0; margin:0 0 22px; }
   .page-pd .gau-copy li { position:relative; padding-left:26px; margin-bottom:12px;
                            color:rgba(255,255,255,.85); font-size:15px; line-height:1.6; }
   .page-pd .gau-copy li::before { content:""; position:absolute; left:0; top:8px; width:10px; height:10px;
                                    border-radius:50%; background:var(--color-mba-red);
                                    box-shadow:0 0 0 0 rgba(178,2,2,.6); animation:gauRing 1.8s ease-out infinite; }
   @keyframes gauRing { 0%{box-shadow:0 0 0 0 rgba(178,2,2,.55)} 100%{box-shadow:0 0 0 9px rgba(178,2,2,0)} }
   ```
   (Gated by `prefers-reduced-motion` too.)
2. **Remove 2nd `gau-metric`** ("Intl / International outlook") — line 296.
3. **Remove both static `.gau-tick` lines** — lines 298–299.
4. `.gau-metrics` now has only 1 metric → optional small CSS tweak so a single metric sits cleanly (e.g. `grid-template-columns:auto` or keep as-is; single cell is fine).

---

## Verification plan (after code)
- Playwright screenshot at 1320 / 390.
- Confirm: Enquire Now has arrow (no trailing arrow), label says "Recognised & Accredited", slider shows only bigger logos, GAU has 1 metric + no static ticks, li→red dots with ring.
- No horizontal overflow, no console errors.
- Tests still green (19 pass / 3 pre-existing).

---

## Questions before coding (1)
The phrase "Enquire Now me arrow add karke **after ka arrow icon remove** kardo" is ambiguous. Please confirm which arrow style you want on all 3 hero CTAs.
