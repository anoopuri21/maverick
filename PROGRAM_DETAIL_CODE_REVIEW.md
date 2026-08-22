# Program Detail Page — Code Review (Layer-by-Layer)

Branch: `dev/overall-development` · File: `resources/views/pages/programs/detail.blade.php` (579 lines)

---

## 1. Architecture / Data Flow (connections)

```
Route:  GET /programs/{slug}  →  ProgramController@show  →  pages.programs.detail
        (routes/web.php:16)

Controller (ProgramController@show):
  Program::where('slug')->where('is_active',true)
    ->with(['programCategory','faqs'=>active only,'seo'])   ← eager loads
    ->first()  (else abort 404)
  returns view('pages.programs.detail', ['program' => $program])

Blade @php block (lines 20-58) builds ALL section vars from model ACCESSORS:
  $highlights, $recognition, $snapshot, $benefits, $learning, $careers,
  $structure, $support, $university, $accreditationGroups, $testimonials,
  $fees, $reviews  ← each = $program->{x}_list  (accessors in Program model)
  $faqs = $program->faqs (loaded relation)
  + helper closures: $initials, $renderLogoChip, image URLs (media_url)
  + $hasScholarship (content sniffing) + $verifyTag

Model (Program):
  JSON casts: highlights, recognition, snapshot, benefits, learning, careers,
             structure, support, university, accreditation_groups,
             testimonials, fees, reviews  (all 'array')
  Accessors normalize raw arrays → blade-safe Collections.
```

**Key connection insight:** Blade is 100% content-agnostic — every section has `@if($x->count())` guard. The `@php` block is the single source that maps JSON → view. This is clean.

---

## 2. Layer-by-layer breakdown

| Layer | Files | Role | Health |
|-------|-------|------|--------|
| **Route** | `routes/web.php` | `/programs/{slug}` → show | ✅ |
| **Controller** | `ProgramController@show` | load + 404 | ✅ clean, eager-loads |
| **Model** | `Program` | JSON casts + accessors | ✅ good |
| **Blade** | `detail.blade.php` | 16 sections render | ✅ content-agnostic |
| **CSS** | `program-detail.css` (465 ln) | design system + 16 components | ✅ organized |
| **JS (inline)** | in blade `@push('scripts')` | accordion, reveal, story slider | ✅ works |
| **JS (file)** | `program-detail.js` | scrollspy + google-reviews + marquee | ⚠️ **ORPHANED** |

---

## 3. Issues found

### 🔴 Critical / must-fix

1. **`program-detail.js` is 100% orphaned dead code.**
   Loaded via `layouts/app.blade.php:85` on `programs.show`, but its targets —
   `#pd-reviews`, `[data-pd-dots]`, `[data-pd-marquee]`, `.pd-logo-strip__track`,
   `[data-clamp-toggle]` — **do not exist** in the current blade. It also imports
   `testimonial-slider.js`. It silently does nothing (wasted module load).
   → **Action:** delete the include (or the file) OR port its real features.

2. **Hero meta / snapshot mismatch — `verify-tag` defined but unused.**
   `$verifyTag` is built (line 57) but **never echoed** anywhere. The Credits/Study
   Mode `VERIFY` flags promised in the design are NOT actually shown. Either remove
   it or actually render it on the snapshot items that need verification.

### 🟡 Improvement opportunities

3. **Story slider logic only shows nav if >3 testimonials**, but `story-track`
   uses inline JS with `perView()` — the math `translateX(-si*(100/v)%)` doesn't
   account for the `gap:18px`, so slides aren't pixel-perfect. Minor.

4. **`hero-card` (Programme at a Glance) shows `snapshot->take(6)`** — hardcoded 6.
   If a programme has fewer snapshot items, the card is short; if more, truncated
   silently. Content-agnostic rule violated slightly (fine, but note it).

5. **Inline JS duplicates global scripts** — accordion/reveal logic is inline in
   blade, but there's ALSO `reveal-observer.js` + `animations-core.js` available
   globally (loaded on other routes). Could unify. Not breaking, just duplicated
   patterns.

6. **`$learnImgUrl` is hardcoded** to one asset regardless of programme. The
   "What You'll Learn" photo is the SAME image for every programme — should be
   content-driven (per-program image) or optional. Same for hero fallback.

7. **`hero-meta` shows `duration` + `level`** but hero also shows the badge with
   level — slight redundancy (level appears twice: badge + meta).

8. **Accessibility:** `.story-media .play` uses an `<a>` with `data-modal-video`
   but no real modal handler is wired in the current blade (the old one used
   `data-modal-video` globally). Video testimonials currently won't open anything.
   → **Action:** verify video modal works or add a lightweight handler.

---

## 4. UX change opportunities (proposed)

Because you asked for UX changes, here are high-value, safe candidates:

| # | Area | UX change | Effort |
|---|------|-----------|--------|
| A | **Dead JS cleanup** | Remove orphaned `program-detail.js` include; keep inline JS | Low |
| B | **VERIFY flags** | Actually show the `verify-tag` on Credits/Study-Mode snapshot tiles (was promised, not delivered) | Low |
| C | **Hero redundancy** | De-dup level (badge vs meta) — keep badge, drop from meta or vice versa | Low |
| D | **Programme at a Glance** | Make card height consistent / show all snapshot, or show "view all" | Low |
| E | **Video testimonials** | Wire a working lightbox/modal for `data-modal-video` | Med |
| F | **What You'll Learn photo** | Make it content-driven (per-program) or remove | Low |
| G | **Hero scroll cue** | Add a subtle "scroll" indicator at hero bottom | Low |
| H | **Sticky bar progress** | Add scroll progress bar under sticky bar (nice premium touch) | Low |

---

## 5. Recommendation
Fix **A** (dead code) + **B** (VERIFY flags actually shown) + **C** (redundancy) as
the core clean-up, then apply 1–2 UX micro-enhancements (**G/H**) for premium feel.
All low-risk, content-agnostic, reversible.

---

> Next: awaiting your pick of which UX changes to implement (or tell me your own).
