# Our Story Page — Blade Completion Backlog

## Purpose

Track animations dropped/adapted during animation refactor because their DOM
targets don't exist in current blade. Use this as tracker when Emergent
redesigns blade template properly.

## Dropped Animations (Need Blade Elements)

### 🔴 HIGH PRIORITY (Client SOP mentions these)

1. **Journey Horizontal Pinned Timeline** ⭐ ($10K centerpiece)
   - Old selectors: `[data-journey-pin]`, `[data-journey-track]`,
     `[data-journey-slide]`, `[data-journey-dot]`, `[data-journey-hint]`
   - Current blade: Simple vertical `.journey__timeline` + `.journey__item`
   - Stub ready: `components/timeline-pinned.js`

2. **Gallery Section with Lightbox**
   - Old selectors: `#gallery`, `[data-gallery-grid]`, `[data-gallery-item]`,
     `#os-lightbox`, `#os-lightbox-img`, `#os-lightbox-caption`
   - Current blade: NO gallery section exists
   - Stub ready: `components/gallery-collage.js`
   - Note: `@include('sections.our-story-gallery')` exists but may need
     full rebuild

### 🟡 MEDIUM PRIORITY (Design polish)

3. **Hero Parallax Shapes**
   - Old selectors: `.os-hero__shape` (parallax), `.os-hero__content`
   - Current blade: `#hero` with basic `.fade-up` only
   - Impact: Hero feels less premium/cinematic

4. **Beginning Image Accent**
   - Old selector: `.os-beginning__image-accent` scale animation
   - Current blade: Only `.beginning__image-wrapper` exists
   - Impact: Missing depth/layering visual

5. **Today Section Pills**
   - Old selector: `.os-today__pill` stagger animation
   - Current blade: No pills structure
   - Impact: Missing program-type visual chips

### 🟢 LOW PRIORITY (Already handled by generic)

6. **CEO Message Selector Mismatch**
   - Old: `#ceo-message` / Blade: `#ceo-quote`
   - Handled by generic `section-reveal.js`
   - No action needed unless custom animation wanted

7. **Final CTA Specific Animations**
   - Old had specific label/heading/subtitle/button animations
   - Handled by generic `section-reveal.js`
   - No action needed unless custom animation wanted

## Adapted Animations (Working)

- ✅ Hero fade-up (`#hero`)
- ✅ Beginning image slide-in (`.beginning__image-wrapper`)
- ✅ Today image slide-in (`.today__image-wrapper`)
- ✅ Impact counter (reads `.impact__stat-value` text content)
- ✅ Section reveals for all sections via generic component
- ✅ Footer year + form + column animations

## Next Steps

When Emergent redesigns Our Story blade per client SOP:

1. Add missing sections (gallery, horizontal timeline pin)
2. Add missing selectors (hero shapes, beginning accent, today pills)
3. Fill in stub components (`gallery-collage.js`, `timeline-pinned.js`)
4. Wire orchestrator (`pages/our-story.js`) to load new components
