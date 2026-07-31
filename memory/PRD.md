# Maverick Business Academy — PRD

## Original Problem Statement
Design and implement pages for Maverick Business Academy London (premium UK business academy).

## Architecture
- **Stack**: Laravel 13 + Filament 3 + Blade + Custom CSS + GSAP animations
- **Pattern**: BEM CSS, GSAP ScrollTrigger, Lenis smooth scroll, Lucide icons

## Completed Work

### 1. Dual MBA Landing Page (Jul 28)
- Route: `/dual-degrees` → 10-section conversion page
- Files: `dual-mba.blade.php`, `dual-mba.css`, `dual-mba.js`

### 2. Our Story Page Redesign (Jul 28)
- Complete visual redesign — DESIGN ONLY, no backend changes
- **Bug fixed**: Removed broken `@push('styles')` pointing to non-existent `css/pages/our-story.css`
- **Sections removed**: Awards, Accreditations (per SOP — dedicated pages exist)
- **New section order**: Hero → Beginning → Today → Impact → Vision → Journey (cinematic timeline) → CEO Message → Gallery → Final CTA
- **New file**: `sections/our-story-gallery.blade.php` — dynamic collage with lightbox
- **Centerpiece**: Horizontal pinned scroll timeline with GSAP ScrollTrigger
- Files modified: `our-story.blade.php`, `our-story.css`, `our-story-animations.js`
- File created: `sections/our-story-gallery.blade.php`

## Variables Preserved (Our Story)
- $hero, $beginning, $today, $impact, $vision, $timelines, $galleryImages
- All field accessors unchanged
- All @include directives preserved: ceo-message, our-story-gallery, final-cta

## Backlog
- [ ] Wire up $galleryImages in PageController (collection with ->image_url, ->caption, ->category)
- [ ] Partner university logos for Dual MBA trust bar
- [ ] Filament CMS resources for Dual MBA dynamic content
- [ ] News/Blog page redesign (pending branch merge)
