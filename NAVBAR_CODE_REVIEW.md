# Navbar — Code Review & URL Catalog

Branch: `dev/overall-development`
Reviewed files:
- `resources/views/partials/navbar.blade.php` (670 lines) — markup
- `public/assets/css/main.css` (lines ~581–1100) — navbar styles
- `public/assets/css/responsive.css` — mobile navbar styles
- `public/assets/js/navigation.js` (796 lines) — all JS behavior
- `app/Settings/SiteSettings.php` — `$site` settings source

---

## 1. Architecture / Data flow

```
layouts/app.blade.php:44  →  @include('partials.navbar')
navbar.blade.php          →  markup (desktop mega-menu + mobile drawer)
  ├─ styles:  main.css (navbar block, lines ~581+) + responsive.css (mobile)
  └─ behavior: public/assets/js/navigation.js (loaded globally)
Data: $site (SiteSettings) → logo_url, logo_white_url, apply_now_url, etc.
```

**Structure:**
- Desktop: logo + top-level menu (Programs [mega], About Us [dropdown], Global Pathways [dropdown], Insights [dropdown]) + Contact Us link + Apply Now CTA.
- Programs = **mega menu** with 7 category tabs (Diplomas, Bachelor's, Master's, Doctorate, Executive, Corporate, Certifications).
- Mobile: hamburger → full-screen drawer with nested accordions (top-level + program categories).
- JS: 8 modules (scroll hide/show, active link, mega menu w/ GSAP, dropdowns, global close on Escape/outside-click, mobile drawer, mobile top-level accordion, mobile category accordion).

---

## 2. FULL URL CATALOG (all links in navbar)

### Route() named links
| Link | Route name | Location |
|------|-----------|----------|
| Logo → Home | `route('home')` | line 5 |
| Contact Us (desktop) | `route('contact')` | line 442 |
| Contact Us (mobile) | `route('contact')` | line 661 |
| Our Story (mobile) | `route('our-story')` | line 600 |
| Apply Now (desktop CTA) | `$site->apply_now_url` (configurable) | line 449 |
| Apply Now (mobile) | `url('/apply/')` | line 667 |

### Static `url('/...')` links (hardcoded)
**Category landing pages:**
- `/masters-degrees/`, `/doctorate-degrees/`, `/executive-education/`, `/corporate-training/`, `/certifications/`, `/diplomas/`

**Diploma programs:**
- `/programs/diploma-business-management/`
- `/programs/diploma-marketing/`
- `/programs/diploma-hr-management/`
- `/programs/diploma-project-management/`
- `/programs/diploma-logistics-supply-chain/`
- `/programs/diploma-finance/`
- `/programs/diploma-it-management/`
- `/programs/diploma-hospitality-management/`

**Bachelor's programs:**
- `/programs/bba/`, `/programs/bsc/`, `/programs/ba/`
- `/programs/bba-marketing/`, `/programs/bba-finance/`, `/programs/bsc-computer-science/`, `/programs/bsc-psychology/`, `/programs/bba-hr-management/`

**Master's programs:**
- `/programs/mba/`, `/programs/msc/`, `/programs/ma/`, `/programs/executive-mba/`
- `/programs/mba-finance/`, `/programs/mba-marketing/`, `/programs/msc-data-analytics/`, `/programs/ma-psychology/`

**Doctorate programs:**
- `/programs/dba/`, `/programs/phd-management/`, `/programs/phd-business-administration/`, `/programs/professional-doctorate-leadership/`, `/programs/dba-strategic-management/`, `/programs/phd-education/`

**Executive / Certificates:**
- `/programs/mini-mba/`, `/programs/executive-leadership-program/`, `/programs/strategic-management-certificate/`, `/programs/executive-certificate-finance/`, `/programs/executive-certificate-marketing/`, `/programs/leadership-excellence-program/`

**Corporate training:**
- `/programs/corporate-leadership-training/`, `/programs/team-building-management/`, `/programs/sales-excellence-training/`, `/programs/customer-service-excellence/`, `/programs/project-management-training/`, `/programs/digital-transformation-training/`

**Certifications:**
- `/certifications/digital-marketing-professional/`, `/certifications/project-management-professional/`, `/certifications/investment-management-analyst/`, `/certifications/purchasing-procurement-manager/`, `/certifications/purchasing-procurement-professional/`, `/certifications/logistics-manager/`, `/certifications/sustainability-leadership-management/`, `/certifications/training-development-professional/`

**About / Other pages:**
- `/our-story/`, `/leadership-board/`, `/accreditations/`, `/global-university-partners/`, `/csr-community-impact/`, `/media-gallery/`
- `/pathway-programs/`, `/global-opportunities/`
- `/news/`, `/events/`, `/blogs/`, `/student-success/`
- `/apply/` (mobile Apply Now)

> **Total ≈ 80 distinct links.** Many are duplicated between desktop mega-menu and mobile drawer (same target).

---

## 3. Production-readiness assessment

### ✅ Good
- Content-driven (mega menu panels rendered from `$accreditationGroups`/data, not pure hardcode in JS).
- Active-nav-link uses JS path matching (robust vs trailing slash).
- Accessibility: `role="menubar"`, `aria-haspopup`, `aria-expanded`, `aria-hidden`, `aria-label`, Escape/outside-click close, mobile drawer `aria-controls`.
- GSAP entrance for mega menu, reduced-motion considered (need to confirm).
- Scroll hide/show (is-scrolled / is-hidden) — good pattern.
- `$site->apply_now_url` is configurable (enterprise-friendly).

### 🔴 Production concerns (likely to fix)
1. **~80 hardcoded `url('/...')` links** scattered in blade. High risk:
   - Drift between navbar links and actual routes/program slugs.
   - Duplicated in desktop + mobile (maintenance burden).
   - **Recommendation:** centralize into a config array / service / single partial so both desktop + mobile iterate the same data. Or drive from Program model (real slugs).
2. **`/programs/{slug}` routes** — many `/programs/...` links are static strings; if the real slugs differ (e.g. the program-detail uses `bsc-business-management`), these **would 404**. Need to verify each against seeded/DB slugs.
3. **`url('/apply/')` (mobile) vs `$site->apply_now_url` (desktop)** — inconsistent; should use the same configurable setting.
4. **Duplicate markup** — desktop mega-menu and mobile drawer repeat all program links separately (maintenance/consistency risk).
5. **Active-link only matches top-level `<a>`** — the Programs mega is a `<button>`, so "Programs" never gets `is-current` when on a program page. Minor UX.
6. **Navbar `is-current`** doesn't highlight the Programs mega category for the current program.

### 🟡 Improvements
- Extract nav data (labels + urls + hierarchy) to a single source (config/helper) and render both desktop + mobile from it.
- Use `route('...')` named routes where possible; for program detail links, generate from Program model or a curated config.
- Add reduced-motion gating to GSAP mega entrance (verify).
- Consider focus-trap in mobile drawer.

---

## 4. Next steps (options)
I can, with your approval, in the next phase:
- **Option A:** Build a **centralized navigation config** (single source of truth) and refactor both desktop + mobile to iterate it — eliminates ~80 duplicated hardcoded links.
- **Option B:** Verify every `/programs/...` link against real DB slugs and fix broken ones.
- **Option C:** Add focus-trap + reduced-motion hardening to navigation.js.
- **Option D:** Make Programs mega show active state for current program.

Tell me which direction (or all) to pursue.
