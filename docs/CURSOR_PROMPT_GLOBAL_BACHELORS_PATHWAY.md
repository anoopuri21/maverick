# Cursor AI Prompt — Global Bachelor's Pathway Page (Complete Redesign)

> **Copy everything below this line into Cursor AI.**

---

## TASK

Completely redesign and rewrite the **Global Bachelor's Pathway** page for Maverick Business Academy. **Discard the existing page entirely** — start from scratch following the exact design system, cinematic hero pattern, and SOP section sequence described below.

### Files to Rewrite (do NOT modify anything else)
- `resources/views/pages/global-bachelors-pathway.blade.php` — **DELETE all content, rewrite from scratch**
- `public/css/pages/global-bachelors-pathway.css` — **DELETE all content, rewrite from scratch**

### URL & Route
- **URL:** `/global-bachelors-pathway/`
- **Route:** Already exists in `routes/web.php` — do NOT change the route
- **Controller:** Already exists — do NOT change the controller
- **Layout:** `@extends('layouts.app')` — do NOT change

### Hard Rules
1. **Use ONLY custom CSS with BEM methodology** — NO Tailwind classes on the frontend. Tailwind is only used by Filament admin panel.
2. **Use the reusable cinematic hero component** from `public/assets/css/components/cinematic-hero.css` — import it in the blade `@push('styles')`.
3. **Use existing design system tokens** from `public/assets/css/main.css` — import it (it's likely already imported in the layout).
4. **Use GSAP + ScrollTrigger** for all scroll-based animations. Use the shared `AnimationUtils` from `public/assets/js/animations-utils.js` where possible.
5. **All fonts:** PP Neue Montreal (display) + Poppins (body) — already loaded globally.
6. **All colors via CSS variables:** `--color-mba-blue: #0f2983`, `--color-mba-dark-blue: #071444`, `--color-mba-red: #b20202`, `--color-warm-white: #f5f0eb`, `--color-white: #ffffff`, `--color-accent: #ebebf3`, `--color-accent-light: #beafff`, `--color-black: #000000`.
7. **Lucide Icons** — use `<span data-lucide="icon-name"></span>` for icons (they get initialized globally).
8. **Do NOT merge any branches.**
9. **Do NOT modify any other files** — only the two files listed above.

---

## DESIGN SYSTEM REFERENCE

### CSS Variable Tokens (from `main.css`)
```css
--font-display: "PP Neue Montreal", sans-serif;
--font-body: "Poppins", sans-serif;
--color-mba-blue: #0f2983;
--color-mba-dark-blue: #071444;
--color-mba-red: #b20202;
--color-warm-white: #f5f0eb;
--color-white: #ffffff;
--color-accent: #ebebf3;
--color-accent-light: #beafff;
--color-black: #000000;
--color-near-black: #0a0a0a;
--color-light-grey: #a0a0a0;
--section-padding: clamp(80px, 10vh, 140px);
--container-padding: clamp(24px, 5vw, 80px);
--max-width: 1400px;
--navbar-height: 80px;
--fs-hero: clamp(42px, 8vw, 120px);
--fs-hero-sub: clamp(32px, 5vw, 72px);
--fs-section-title: clamp(36px, 5vw, 72px);
--fs-card-title: clamp(20px, 2.5vw, 32px);
--fs-body: clamp(15px, 1.2vw, 18px);
--transition-fast: 0.3s ease;
--transition-normal: 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
```

### Typography Classes (from `main.css`)
```css
.display-text    { font-family: var(--font-display); font-size: var(--fs-hero); font-weight: 700; line-height: 1; letter-spacing: -0.03em; }
.section-title   { font-family: var(--font-display); font-size: var(--fs-section-title); font-weight: 500; line-height: 1.1; letter-spacing: -0.02em; }
.card-title      { font-family: var(--font-display); font-size: var(--fs-card-title); font-weight: 600; line-height: 1.2; }
.body-text       { font-family: var(--font-body); font-size: var(--fs-body); line-height: 1.7; }
.caption-text    { font-family: var(--font-body); font-size: 13px; text-transform: uppercase; letter-spacing: 0.15em; }
```

### Layout Utilities (from `main.css`)
```css
.container        { max-width: var(--max-width); margin: 0 auto; padding: 0 var(--container-padding); }
.section-wrapper  { padding: var(--section-padding) 0; }
.section--light   { background: var(--color-white); }
```

### Button Classes (from `main.css`)
```css
.btn                { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; font-family: var(--font-body); font-size: 14px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; cursor: pointer; }
.btn::after         { content: "→"; margin-left: 4px; }
.btn--primary       { background: var(--color-mba-red); color: #fff; }
.btn--primary:hover { background: var(--color-mba-blue); }
.btn--secondary     { background: transparent; color: var(--color-white); border: 1px solid rgba(255,255,255,0.3); }
.btn--secondary:hover { border-color: var(--color-white); background: rgba(255,255,255,0.06); }
```

### Section Label (from `main.css`)
```html
<span class="section-label"><span>SECTION NAME</span></span>
```

### Animation Utility Classes (from `main.css`)
```css
.fade-up          { opacity: 0; transform: translateY(40px); will-change: transform, opacity; }
.text-reveal-wrapper { overflow: hidden; display: block; }
.text-reveal-inner   { display: block; transform: translateY(110%); will-change: transform; }
```

---

## CINEMATIC HERO — Reusable Component

**DO NOT create a new hero.** Use the existing cinematic hero component from `public/assets/css/components/cinematic-hero.css`. Import it in the blade file.

### Hero HTML Structure (MUST follow exactly):
```html
<link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">

<section class="cinematic-hero" aria-label="Page Hero">
    <!-- Background layers -->
    <div class="cinematic-hero__bg" aria-hidden="true">
        <div class="cinematic-hero__bg-image" style="background-image: url('HERO_IMAGE_URL')"></div>
        <div class="cinematic-hero__gradient"></div>
        <div class="cinematic-hero__noise"></div>
        <div class="cinematic-hero__shapes">
            <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none">
                <circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none">
                <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/>
            </svg>
        </div>
        <div class="cinematic-hero__particles">
            @for($i = 0; $i < 6; $i++)
                <div class="cinematic-hero__particle"></div>
            @endfor
        </div>
        <div class="cinematic-hero__scanline"></div>
        <div class="cinematic-hero__corners">
            <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
        </div>
    </div>

    <!-- Content (left-aligned) -->
    <div class="container cinematic-hero__content">
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            GLOBAL BACHELOR'S PATHWAY
        </span>
        <h1 class="cinematic-hero__title">
            Your Structured Route to a Globally Recognised<br>
            <em>European Bachelor's Degree</em>
        </h1>
        <p class="cinematic-hero__description">
            Begin your Bachelor's Degree Pathway in UAE with Maverick Business Academy London
            and progress towards an internationally recognised European Bachelor's degree through
            our partner university pathways in Hungary, Romania, and Moldova.
        </p>
    </div>
</section>
```

---

## SOP SECTION SEQUENCE (MUST follow this exact order)

| # | Section | Background | Key Elements |
|---|---------|------------|--------------|
| 1 | **Hero** (Cinematic) | Dark (#071444) | Left-aligned title, eyebrow with red line, description, scroll hint |
| 2 | **What is the Pathway Programme?** | Light (#ffffff) | Two-column grid: text left, stats/stages right |
| 3 | **Why Choose This Pathway?** | Light (#f5f0eb warm) | Sticky left column + scrolling numbered cards right |
| 4 | **Explore Europe with Your Choices** | Dark (#071444) | Three country cards: Hungary, Romania, Moldova |
| 5 | **Programme Pathway Structure** | Light (#ffffff) | Four-stage vertical timeline with connector lines |
| 6 | **Study Destinations** | Dark (#071444) | Alternating left/right zig-zag layout with images |
| 7 | **Cost & Time Advantage** | Dark (#071444) | Split layout: text left, comparison table right |
| 8 | **Programs Offered (Pathway Areas)** | Light (#ffffff) | Four-column icon card grid |
| 9 | **Partner University Progression** | Dark (#071444) | Three-column country progression cards |
| 10 | **Admission Requirements** | Light (#ffffff) | Two-column: eligibility list + entry requirements |
| 11 | **Documents Required** | Dark (#071444) | Three-column document group cards |
| 12 | **Final CTA** | Red (#b20202) | Centered heading, description, CTA buttons |

---

## SECTION-BY-SECTION CONTENT & DESIGN SPEC

### SECTION 1: HERO (Cinematic)
- Use the reusable cinematic hero component above
- Background image: `https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600`
- Eyebrow: `GLOBAL BACHELOR'S PATHWAY` with red accent line
- Title: `Your Structured Route to a Globally Recognised` / `<em>European Bachelor's Degree</em>`
- Description paragraph about the pathway programme
- Add small highlight badges below description (Study Route, Destinations, Focus)
- CTA buttons: "Enquire Now" (primary), "Speak to an Advisor" (secondary)

### SECTION 2: WHAT IS THE PATHWAY PROGRAMME?
**Class:** `gbp-overview section-wrapper section--light`
**Layout:** Two-column grid
**Left column:**
- Section label: `PROGRAMME OVERVIEW`
- Heading: `What is the Maverick <em>Bachelor's Pathway Programme?</em>`
- Two paragraphs explaining the programme
- Use `.text-reveal-wrapper` + `.text-reveal-inner` for heading animation
- Use `.fade-up` class on all text elements

**Right column:**
- Four stat cards showing the pathway stages:
  - `~6 Mo` → LEVEL 4 DIPLOMA
  - `~6 Mo` → LEVEL 5 DIPLOMA
  - Progression to International Partner University
  - International Bachelor's Degree

**Animations:**
- Section label: fade-up from opacity:0, y:16
- Heading: text-reveal (y: 110% → 0%)
- Paragraphs: fade-up staggered
- Stats: staggered fade-up with 0.1s delay between each

---

### SECTION 3: WHY CHOOSE THIS PATHWAY?
**Class:** `gbp-why section-wrapper section--light section--warm`
**Layout:** Two-column — sticky left + scrolling cards right (CSS `position: sticky`)

**Left column (sticky):**
- Section label: `OUR VALUE`
- Heading: `Why Choose This <em>Pathway Programme?</em>`
- Blockquote: "A smarter alternative to the traditional overseas route — start with Maverick, progress internationally at the right stage."
- Paragraph explaining the value proposition

**Right column (scrolling cards):**
Five numbered cards with Lucide icons:
1. `clock` — Save Time — "The pathway can help students save up to one year compared with the traditional Bachelor's route."
2. `award` — Earn 240 UK Credits — "Students complete structured UK credit-based qualifications before progressing to the university stage."
3. `shuffle` — Flexible Learning Route — "Students can begin their studies through flexible learning before moving into the final university progression stage."
4. `graduation-cap` — Direct University Progression — "The programme is designed to support progression to selected partner universities."
5. `trending-down` — Cost-Effective Study Route — "Students and parents can reduce overall study cost compared with starting the full overseas route from year one."

**Card Design:**
- White background, subtle border
- Icon left, content right, number top-right
- Hover: slight elevation + border color change
- Each card: `fade-up` with staggered animation

---

### SECTION 4: EXPLORE EUROPE WITH YOUR CHOICES
**Class:** `gbp-explore section-wrapper`
**Background:** Dark (`var(--color-mba-dark-blue)`)
**Layout:** Centered heading + three-column country cards

**Header:**
- Section label: `YOUR OPTIONS`
- Heading: `Explore Europe with <em>Your Choices</em>`
- Subtitle paragraph about choosing European progression routes

**Three Country Cards:**
| Card | Flag | Country | Type | University | Highlights |
|------|------|---------|------|------------|------------|
| 1 | 🇭🇺 | Hungary | Premium European Pathway | International Business School, Budapest | International study experience, Dual degree opportunities, 100% placement assistance, Erasmus+ exchange |
| 2 | 🇷🇴 | Romania | Affordable European Pathway | Aurel Vlaicu University | Affordable tuition, One-year completion route, Lower cost of living, Direct university progression |
| 3 | 🇲🇩 | Moldova | Affordable European Pathway | USPEE, Moldova | Lower overall study cost, Reduced study duration, Student visa guidance, Flexible pathway structure |

**Card Design:**
- Dark blue background (`var(--color-mba-blue)`)
- Large flag emoji at top
- Country name in PP Neue Montreal
- Type as small badge/label
- University name
- Checkmark list of highlights
- Hover: border glow + slight scale

---

### SECTION 5: PROGRAMME PATHWAY STRUCTURE
**Class:** `gbp-stages section-wrapper section--light`
**Layout:** Centered heading + vertical timeline

**Header:**
- Section label: `PROGRAMME PATHWAY`
- Heading: `A Structured <em>Four-Stage Journey</em>`
- Subtitle: "From foundational diplomas in the UAE to an internationally recognised European bachelor's degree."

**Four Timeline Stages:**
| Stage | Year | Title | Duration | Description |
|-------|------|-------|----------|-------------|
| 1 | 01 | Level 4 Diploma | Approx. 6 Months | Students begin with a Level 4 Diploma designed to build the academic foundation required for bachelor's progression. |
| 2 | 02 | Level 5 Diploma | Approx. 6 Months | Students then complete a Level 5 Diploma, strengthening their academic knowledge and preparing them for international university progression. |
| 3 | 03 | International University Progression | Partner University Stage | After completing the required academic stages, students progress to an international partner university in Europe. |
| 4 | 04 | International Bachelor's Degree | Final Outcome | Upon successful completion of the final university stage, students receive an internationally recognised bachelor's degree from the partner university. |

**Timeline Design:**
- Vertical line in red (`var(--color-mba-red)`)
- Circular dot markers with stage numbers
- Cards alternate left/right (or all right on mobile)
- Dot animates in with scale, line draws with GSAP
- Cards fade-up with stagger

---

### SECTION 6: STUDY DESTINATIONS
**Class:** `gbp-destinations section-wrapper`
**Background:** Dark (`var(--color-mba-dark-blue)`)
**Layout:** Alternating zig-zag (image left/content right, then image right/content left)

**Header:**
- Section label: `STUDY DESTINATIONS`
- Heading: `Choose Your European <em>Study Destination</em>`

**Three Destination Blocks:**

**Hungary (image right, content left):**
- Label: `PREMIUM EUROPEAN PATHWAY`
- Title: `Study in <em>Hungary</em>`
- Partner: `International Business School, Budapest`
- Description paragraph
- Checkmark list: International study experience in Budapest, Dual degree opportunities with University of Buckingham (UK) and Dublin Business School (Ireland), 100% placement assistance and career mentoring, Internship support connected to KPMG, Microsoft, Amazon and more, 9–12 month post-study work opportunity, Access to 27+ Schengen countries, Erasmus+ student exchange opportunity, No IELTS / TOEFL required
- Best For tag
- Image: `https://images.pexels.com/photos/3722721/pexels-photo-3722721.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760`

**Romania (image left, content right):**
- Label: `AFFORDABLE EUROPEAN PATHWAY`
- Title: `Study in <em>Romania</em>`
- Partner: `Aurel Vlaicu University, Romania`
- Checkmark list: Affordable tuition fees, One-year university completion route, Lower cost of living, Internationally recognised European degree, Direct university progression, Reduced overall study duration, Strong return on investment, Student visa guidance
- Best For tag
- Image: `https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760`

**Moldova (image right, content left):**
- Label: `AFFORDABLE EUROPEAN PATHWAY`
- Title: `Study in <em>Moldova</em>`
- Partner: `USPEE, Moldova`
- Checkmark list: Affordable tuition fees, Lower overall study cost, Reduced study duration, International university progression, Student visa guidance, Career and academic support, Flexible pathway structure
- Best For tag
- Image: `https://images.pexels.com/photos/1519088/pexels-photo-1519088.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760`

**Design:**
- Images: `aspect-ratio: 4/5`, `object-fit: cover`, subtle gradient overlay
- Content: white text on dark background
- Checkmarks: red SVG check icons
- Animate image with parallax, content with fade-up

---

### SECTION 7: COST & TIME ADVANTAGE
**Class:** `gbp-cost section-wrapper`
**Background:** Dark (`var(--color-mba-dark-blue)`)
**Layout:** Two-column split — text left, comparison cards right

**Left:**
- Section label: `COST & TIME ADVANTAGE`
- Heading: `A Smarter Alternative to the <em>Traditional 4-Year Route</em>`
- Description paragraph
- Closing paragraph

**Right:**
Four comparison rows:
| Label | Value | Variant |
|-------|-------|---------|
| Traditional Route | 4 Years | muted |
| Maverick Pathway | ~3 years | accent (highlighted) |
| Time Saving — Hungary | Up to 12 Months | muted |
| Time Saving — Romania & Moldova | Up to 24 Months | muted |

**Design:**
- Muted rows: subtle dark background
- Accent row: red/blue gradient background, white text
- Animate rows with staggered fade-up

---

### SECTION 8: PROGRAMS OFFERED (Pathway Areas)
**Class:** `gbp-areas section-wrapper section--light`
**Layout:** Centered heading + four-column icon card grid

**Header:**
- Section label: `PATHWAY AREAS`
- Heading: `Choose a Bachelor's Pathway That Matches <em>Your Career Goals</em>`
- Subtitle: "Career-focused pathway areas across business, technology, hospitality, and international management fields."

**Four Cards:**
| Icon | Title | Programs |
|------|-------|----------|
| `briefcase` | Business & Management | Business Administration, Business Management, International Business, Marketing, Human Resource Management, Finance & Accounting, Entrepreneurship, Business Analytics |
| `cpu` | IT & Data | Information Technology, Management Information Systems, Computer Science, Data Science, Business Analytics, AI & Data Analytics |
| `compass` | Hospitality & Tourism | Hospitality Management, Tourism Management, International Hospitality & Tourism, Service Management |
| `globe` | International & European Studies | International Relations, International Business Management, European Business Studies, Business & Administration |

**Card Design:**
- White background, subtle border
- Icon at top (red color `var(--color-mba-red)`)
- Title in PP Neue Montreal
- Description paragraph
- Program list with bullet points
- Hover: border color change + slight elevation

---

### SECTION 9: PARTNER UNIVERSITY PROGRESSION
**Class:** `gbp-partners section-wrapper`
**Background:** Dark (`var(--color-mba-dark-blue)`)
**Layout:** Centered heading + three-column country cards

**Header:**
- Section label: `PROGRESSION OPTIONS`
- Heading: `Partner University <em>Progression Options</em>`
- Subtitle: "Three European progression routes — pick the one that fits your budget, timeline, and career direction."

**Three Cards:**
| Country Code | Name | Description | Best Suited For |
|-------------|------|-------------|-----------------|
| HU | Hungary — Premium European Pathway | Progress to International Business School, Budapest through Maverick's premium European route. | Business Management, International Business, Marketing, Finance, Data Analytics, AI & Business, Entrepreneurship |
| RO | Romania — Affordable European Pathway | Progress to Aurel Vlaicu University, Romania through Maverick's affordable European pathway. | Business Administration, Management, Information Technology, Data Science, Hospitality & Tourism, International Business |
| MD | Moldova — Affordable European Pathway | Progress to USPEE, Moldova through Maverick's affordable European pathway. | Business Administration, Management, Information Technology, Tourism & Hospitality, General Business Studies |

**Card Design:**
- Large country code at top (outlined text style)
- Title in PP Neue Montreal
- Description paragraph
- "Best Suited For" section with tag pills
- Hover: border glow

---

### SECTION 10: ADMISSION REQUIREMENTS
**Class:** `gbp-admission section-wrapper section--light`
**Layout:** Two-column grid

**Left Column — Who Can Apply?**
- High school / Grade 12 graduates
- Students who want to study bachelor's abroad
- Students looking for a European bachelor's degree
- Students seeking a cost-effective alternative to studying overseas from year one
- Students interested in credit transfer and international university progression
- Working professionals who want to complete their bachelor's degree pathway

**Right Column — General Entry Requirements**
- High school / Grade 12 certificate or equivalent
- Academic transcripts / mark sheets
- Passport copy
- Passport-size photograph
- Updated CV, if applicable
- English language evidence, if required
- Completed application form
- Any additional documents required by the partner university or visa process
- Note: "No IELTS / TOEFL required, subject to admission requirements."

**Design:**
- Checkmark SVG icons for each list item
- Cards with subtle borders
- Staggered fade-up animation

---

### SECTION 11: DOCUMENTS REQUIRED
**Class:** `gbp-docs section-wrapper`
**Background:** Dark (`var(--color-mba-dark-blue)`)
**Layout:** Three-column card grid

**Header:**
- Section label: `CHECKLIST`
- Heading: `Documents Required <em>for Admission</em>`

**Three Document Groups:**
| Icon | Title | Items |
|------|-------|-------|
| `user` | Personal Documents | Passport copy, Passport-size photograph, Emirates ID copy (if applicable), Updated CV (if applicable) |
| `book-open` | Academic Documents | High school / Grade 12 certificate, Academic transcripts / mark sheets, Previous diploma or college documents (if applicable), English language documents (if required) |
| `file-check` | Additional Documents for Visa Stage | Bank statement or financial proof (if required), Accommodation details (if required), Travel insurance (if required), Medical documents (if required), Any additional documents requested by the embassy or university |

**Card Design:**
- Dark blue background
- Icon at top (white or accent color)
- Title in PP Neue Montreal
- Bulleted list
- Subtle border, hover effect

---

### SECTION 12: FINAL CTA
**Class:** `gbp-final section-wrapper`
**Background:** Red (`var(--color-mba-red)`)
**Layout:** Centered content

**Content:**
- Eyebrow: "Your Global Career Starts Here"
- Heading: `Start Your Global <em>Bachelor's Journey</em>`
- Subtitle: "Your international bachelor's degree pathway starts here."
- Description paragraph about beginning with Maverick and progressing to partner universities
- Three CTA buttons:
  1. "Speak to an Admission Advisor" (white button)
  2. "Download Brochure" (outline white)
  3. "Apply for the Next Intake" (outline white)

**Design:**
- Rounded container with shadow
- White text on red
- Buttons with hover transitions
- Fade-up animation for all elements

---

## BLADE FILE STRUCTURE

```blade
@extends('layouts.app')

@section('title', "Global Bachelor's Pathway Programme | Study Bachelor's in Europe")
@section('meta_description', 'Start your Bachelor\'s journey with Maverick Business Academy London...')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-bachelors-pathway.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-gbp">

    {{-- Static data via @php block (same data as current blade) --}}
    @php
        $hero = (object)[ ... ];
        $overview = (object)[ ... ];
        // ... all data objects ...
    @endphp

    {{-- 1. HERO (Cinematic) --}}
    <section class="cinematic-hero"> ... </section>

    {{-- 2. WHAT IS THE PATHWAY PROGRAMME? --}}
    <section class="gbp-overview section-wrapper section--light"> ... </section>

    {{-- Continue for all 12 sections... --}}

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Use AnimationUtils from animations-utils.js
    // Text reveals
    AnimationUtils.textReveal('.gbp-overview .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-why .text-reveal-inner');
    // ... etc for each section heading

    // Fade-ups
    AnimationUtils.fadeUp('.gbp-overview .fade-up', { stagger: 0.1 });
    // ... etc

    // Section labels
    AnimationUtils.sectionLabel('.gbp-overview');
    AnimationUtils.sectionLabel('.gbp-why');
    // ... etc

    // Cards
    AnimationUtils.cards('.gbp-why-card');
    AnimationUtils.cards('.gbp-explore-card');
    // ... etc

    // Parallax for destination images
    AnimationUtils.parallax('.gbp-dest__image');
});
</script>
@endpush
```

---

## CSS FILE STRUCTURE (`global-bachelors-pathway.css`)

```css
/* =========================================================
   GLOBAL BACHELOR'S PATHWAY — Page Styles
   Maverick Business Academy — BEM Methodology
   ========================================================= */

/* Import reusable components if not already in layout */
/* cinematic-hero.css is imported via <link> in blade */

/* =========================================================
   1. HERO HIGHLIGHTS (Below cinematic hero title)
   ========================================================= */
.gbp-hero__highlights { ... }
.gbp-highlight { ... }

/* =========================================================
   2. OVERVIEW SECTION
   ========================================================= */
.gbp-overview { ... }
.gbp-overview__grid { ... }
.gbp-overview__main { ... }
.gbp-overview__heading { ... }
.gbp-overview__stats { ... }
.gbp-stat { ... }

/* =========================================================
   3. WHY CHOOSE SECTION (Sticky + Cards)
   ========================================================= */
.gbp-why { ... }
.gbp-why__grid { ... }
.gbp-why__sticky { ... }
.gbp-why__cards { ... }
.gbp-why-card { ... }

/* =========================================================
   4. EXPLORE EUROPE SECTION
   ========================================================= */
.gbp-explore { ... }
.gbp-explore__grid { ... }
.gbp-explore-card { ... }

/* =========================================================
   5. STAGES TIMELINE
   ========================================================= */
.gbp-stages { ... }
.gbp-stages__timeline { ... }
.gbp-stage { ... }

/* =========================================================
   6. DESTINATIONS (Zig-zag)
   ========================================================= */
.gbp-destinations { ... }
.gbp-dest { ... }
.gbp-dest--left { ... }
.gbp-dest--right { ... }

/* =========================================================
   7. COST & TIME
   ========================================================= */
.gbp-cost { ... }
.gbp-cost__grid { ... }
.gbp-cost-row { ... }

/* =========================================================
   8. PATHWAY AREAS
   ========================================================= */
.gbp-areas { ... }
.gbp-areas__grid { ... }
.gbp-area-card { ... }

/* =========================================================
   9. PARTNER PROGRESSION
   ========================================================= */
.gbp-partners { ... }
.gbp-partners__grid { ... }
.gbp-partner-card { ... }

/* =========================================================
   10. ADMISSION
   ========================================================= */
.gbp-admission { ... }
.gbp-admission__grid { ... }
.gbp-admission__list { ... }

/* =========================================================
   11. DOCUMENTS
   ========================================================= */
.gbp-docs { ... }
.gbp-docs__grid { ... }
.gbp-doc-card { ... }

/* =========================================================
   12. FINAL CTA
   ========================================================= */
.gbp-final { ... }
.gbp-final__inner { ... }
.gbp-final__ctas { ... }

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 1024px) { ... }
@media (max-width: 768px) { ... }
@media (max-width: 480px) { ... }
```

---

## ANIMATION SPECIFICATION

### GSAP + ScrollTrigger Animations (via `animations-utils.js`)

All animations should be initialized in the `@push('scripts')` block:

```javascript
document.addEventListener('DOMContentLoaded', () => {

    // ── Section Labels ──
    const sections = [
        '.gbp-overview', '.gbp-why', '.gbp-explore', '.gbp-stages',
        '.gbp-destinations', '.gbp-cost', '.gbp-areas', '.gbp-partners',
        '.gbp-admission', '.gbp-docs', '.gbp-final'
    ];
    sections.forEach(s => AnimationUtils.sectionLabel(s));

    // ── Text Reveals (Headings) ──
    AnimationUtils.textReveal('.gbp-overview .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-why .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-explore .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-stages .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-destinations .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-cost .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-areas .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-partners .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-admission .text-reveal-inner');
    AnimationUtils.textReveal('.gbp-docs .text-reveal-inner');

    // ── Fade Up Elements ──
    sections.forEach(s => {
        AnimationUtils.fadeUp(`${s} .fade-up`, { stagger: 0.1 });
    });

    // ── Cards ──
    AnimationUtils.cards('.gbp-why-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-explore-card', { stagger: 0.15 });
    AnimationUtils.cards('.gbp-area-card', { stagger: 0.1 });
    AnimationUtils.cards('.gbp-partner-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-doc-card', { stagger: 0.12 });

    // ── Timeline Stages ──
    AnimationUtils.cards('.gbp-stage', { stagger: 0.2 });

    // ── Line Scale (Timeline connector) ──
    AnimationUtils.lineScale('.gbp-stages__line');

    // ── Destination Images (Parallax) ──
    document.querySelectorAll('.gbp-dest__image').forEach(img => {
        AnimationUtils.parallax(img, { y: -30 });
    });

    // ── Stat Counters ──
    document.querySelectorAll('.gbp-stat__number[data-target]').forEach(el => {
        AnimationUtils.counter(el, parseInt(el.dataset.target));
    });

});
```

### Important Animation Notes:
- **NEVER use `.fade-up` class on hero section content** — it causes visibility issues since hero is above the fold and scroll triggers may not fire. Use the cinematic hero's built-in CSS animations instead.
- All `text-reveal-inner` elements start at `transform: translateY(110%)` — GSAP animates them to `0%`.
- All `.fade-up` elements start at `opacity: 0; transform: translateY(40px)` — GSAP animates them to visible.
- Use `toggleActions: "play none none none"` so animations only play once.
- Respect `prefers-reduced-motion` — check `AnimationUtils.prefersReducedMotion` and skip animations if true.

---

## REFERENCE: HOW OTHER PAGES ARE STRUCTURED

### Our Story Page (`resources/views/pages/our-story.blade.php`)
- Uses `os-hero` custom hero (NOT the reusable cinematic hero — this was built before the reusable component)
- CSS file: `public/assets/css/our-story.css`
- Uses ES module animations (separate system — don't replicate this)

### Dual MBA Page (`resources/views/pages/dual-mba.blade.php`)
- Uses the reusable cinematic hero component
- CSS file: `public/css/pages/dual-mba.css`
- Uses `AnimationUtils` from `animations-utils.js`
- 16 SOP sections, alternating light/dark backgrounds

### Edutainment Page (`resources/views/pages/edutainment.blade.php`)
- Uses the reusable cinematic hero component
- CSS file: `public/css/pages/edutainment.css`
- 12 SOP sections

**Follow the Dual MBA and Edutainment patterns** — they use the modern reusable cinematic hero and `AnimationUtils`.

---

## RESPONSIVE BREAKPOINTS

```css
/* Tablet */
@media (max-width: 1024px) {
    /* Two-column grids → single column */
    /* Reduce padding */
    /* Stack side-by-side layouts */
}

/* Mobile */
@media (max-width: 768px) {
    /* All grids → single column */
    /* Zig-zag destinations → stack vertically */
    /* Timeline → single column */
    /* Sticky sections → remove sticky */
    /* Cards → full width */
    /* Reduce font sizes */
    /* Hide parallax effects */
}

/* Small Mobile */
@media (max-width: 480px) {
    /* Further reduce spacing */
    /* Smaller fonts */
    /* Stack CTA buttons vertically */
}
```

---

## FINAL CHECKLIST

Before finishing, verify:
- [ ] Blade file uses `@extends('layouts.app')`
- [ ] Cinematic hero uses the reusable component (imported via `<link>`)
- [ ] All 12 sections present in exact SOP order
- [ ] BEM class naming throughout (`.gbp-block__element--modifier`)
- [ ] No Tailwind classes anywhere in the frontend
- [ ] CSS variables used for all colors, fonts, spacing
- [ ] GSAP animations initialized in `@push('scripts')`
- [ ] Uses `AnimationUtils` for text-reveal, fade-up, cards, parallax
- [ ] `.fade-up` NOT used in hero section (use cinematic hero's built-in animations)
- [ ] All text content matches SOP exactly
- [ ] Responsive design at 1024px, 768px, 480px breakpoints
- [ ] `data-testid` attributes on all major sections
- [ ] `loading="lazy"` on images below the fold
- [ ] `aria-label` on sections for accessibility
- [ ] CSS file has no unused selectors
- [ ] Blade file has all PHP data objects at the top

---

## GIT INFO (For reference only — do NOT merge branches)

- **Remote:** `git remote add origin https://github.com/anoopuri21/maverick.git`
- **Branch:** Create a new branch `feature/global-bachelors-pathway-redesign` from `main`
- **Commit:** After completing the work, commit with message: `feat(gbp): complete redesign of Global Bachelor's Pathway page per SOP`
