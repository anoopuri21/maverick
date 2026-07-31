# Dual MBA Programme — PRD

## Original Problem Statement
Design and implement a high-conversion landing page for the "Dual Degree" (Dual MBA) academic programme at Maverick Business Academy London. Content sourced from the provided DUAL MBA PROGRAMME.docx.

## Architecture
- **Stack**: Laravel 13 + Filament 3 + Blade + Tailwind (static CSS) + GSAP animations
- **Pattern**: Existing Maverick codebase — BEM CSS, GSAP ScrollTrigger, Lenis smooth scroll, Lucide icons
- **Route**: `/dual-degrees` → `PageController@dualMba` → `pages.dual-mba` Blade view

## Files Created/Modified
| File | Action | Purpose |
|------|--------|---------|
| `routes/web.php` | Modified | Added `/dual-degrees` route |
| `app/Http/Controllers/PageController.php` | Modified | Added `dualMba()` method |
| `resources/views/pages/dual-mba.blade.php` | Created | Full landing page Blade view |
| `public/assets/css/dual-mba.css` | Created | Page-specific styles (BEM) |
| `public/assets/js/dual-mba.js` | Created | GSAP animations, FAQ accordion, testimonial carousel |
| `DUAL_MBA_BLUEPRINT.md` | Created | Architecture blueprint document |

## Implemented Sections (10)
1. **Hero** — Full-viewport, split layout, quick stats bar, dual CTAs
2. **Trust Bar** — Partner university logos (GAU, RBS, UCA) with "Awarded By" label
3. **Programme Overview** — Two-card + bridge visual for General MBA ↔ Specialised MBA
4. **Why Choose** — 7 benefit cards in responsive grid
5. **Specialisations Grid** — 8 specialisation cards (AI, Finance, HR, Supply Chain, PM, IT, Healthcare, Analytics)
6. **Why Employers Value** — Split layout with counter animation + 8-item checklist
7. **Testimonials** — Carousel with 5 cards, prev/next controls, auto-play
8. **Application Process** — 4-step horizontal timeline
9. **FAQ Accordion** — 7 collapsible Q&A items
10. **Final CTA** — Full-width conversion banner with 3 CTAs

## What's Still Needed
- **P0**: Partner university logo files (GAU, RBS, UCA) — currently placeholder with onerror fallback
- **P1**: Real testimonial data / student photos
- **P2**: Fee details, eligibility criteria for FAQ expansion
- **P2**: Filament CMS resources for dynamic content management

## Backlog
- [ ] Filament admin resources for each section (HeroContent, Specialisation, FAQ, Testimonial, etc.)
- [ ] MySQL migrations for Dual MBA content tables
- [ ] Real student testimonials integration
- [ ] Brochure download PDF integration
- [ ] Application form integration
- [ ] A/B testing for CTA copy variants
