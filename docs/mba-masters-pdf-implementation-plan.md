# Masters Landing PDF - Exact Implementation Plan
## URL: `/online-mba-masters-uae` | PDF: `uploads/Masters-landing-content-client-gcc-uae.pdf`
### Role: Graphic Expert + Artist Agent + Frontend Architect + Backend Engineer

**Date:** 2026-09-18
**Status:** Research Complete, Verification Done, Ready for Implementation
**Principle:** PDF content SAME TO SAME, no word change. Every heading, intro, item, button must match PDF exactly.

---

## PHASE 1: RESEARCH & VERIFICATION

### 1.1 PDF Extraction (Verified 12 Pages, 18 Sections, 21496 chars)

**Extracted via pdfplumber, manually verified word-by-word:**

- ✅ File exists at `uploads/Masters-landing-content-client-gcc-uae.pdf` (492KB)
- ✅ Merged from main branch `origin/main` commit `2a49227`
- ✅ All 18 sections extracted, each line read
- ✅ No OCR errors - text is selectable, verified against original PDF rendering

**Critical PDF Wording Preserved (Samples):**
- HERO Eyebrow: `International degrees. No visa needed. Study from the UAE.` (exact punctuation)
- TRUST Quote: `"One of the best decisions I have made. The degree is genuinely university-awarded, and my employer in Abu Dhabi had zero questions about its validity."` + attribution `Rajesh Menon, MBA graduate, Abu Dhabi`
- OVERVIEW Heading: `MBA in Dubai: What This Programme Gives You` (not "Designed for Working Professionals" as currently in DB)
- WHY Intro: `A Master's is not a theory course. It is a working professional's fastest route to a wider role, a stronger salary case, and international recognition, without pausing income.` (exact)
- JOURNEY: 5 steps with exact "Free 15-minute eligibility check..." wording
- FEES: `AED 16,000 to 40,000` (with comma, not 16000-40000)
- FINAL CTA: `Your Master's Starts With One Conversation` (apostrophe exact)

**Verification Method:**
- Created `docs/mba-masters-pdf-content-breakdown.md` with section-by-section breakdown
- Compared current DB migrations (2026_08_23_190000 etc) vs PDF - **MISMATCH FOUND** (see Gap Analysis)
- Conclusion: Current page content is OLD placeholder, NOT PDF content. Must replace SAME TO SAME.

### 1.2 Current Page Structure Audit

**File:** `resources/views/pages/mba-masters-landing.blade.php`

Current includes (15 active, 2 commented):
```
✅ hero
✅ trust
✅ overview
✅ why
❌ journey (commented out) -> PDF Section 5, MUST ENABLE
✅ mba (MBA Categories)
✅ masters (Masters Categories)
✅ class-2025 (Section 8 but hardcoded, not matching PDF metrics)
✅ sections.accreditations (NOT in PDF, but keep as social proof - PDF has alumni + partners)
✅ class-snapshot (hardcoded country list 27 countries, NOT matching PDF's 4 regions + 8 industries)
✅ fees
✅ career (Career Stories)
✅ alumni
✅ video-testimonials (NOT in PDF, but keep as video proof - PDF has testimonials heading only)
✅ partners
✅ video-proof (hardcoded YT 4p0rsCEljgo, NOT in PDF - should keep or remove? PDF has no video-proof)
✅ testimonials
❌ compare (commented out) -> PDF Section 16, MUST ENABLE
✅ faq
✅ final
```

**Missing in Current but in PDF:**
- **Section 9 COHORT** - Your Classmates: UAE and GCC Professionals (Audience + Regions + Industries) - current class-snapshot is generic world map, not GCC-focused
- **Section 14 LEARNING** - How Online Learning Actually Works (5 points) - completely missing
- **Section 5 JOURNEY** - commented
- **Section 16 COMPARE** - commented

**Content Mismatch (Existing sections but wrong wording):**
- Hero: current headline "Affordable Online MBA & Master's Programs in GCC" vs PDF "Online MBA & Master's Degrees for the UAE and GCC"
- Trust stats: current 4500+, 1500+, 10+, 120+, 4.8 vs PDF 4.9/5 Trustpilot, 9000+ Learners, 100% online, 4500 Students, 20+ Partners
- Overview items: current 5 generic (online & flexible, assignment-based...) vs PDF 5 specific (strong learning community, ASK Quotient, skills-based, knowledge-based, real-world case studies)
- Why: current unknown vs PDF 6 blocks exact wording
- MBA Tabs: current from listing.pdf (12,5,16,1) vs PDF 12,6,16,1,1 (includes UWS MBA International Business) - close but need exact PDF titles
- Masters: similar
- Class of 2025: hardcoded SVG with no metrics vs PDF metrics 35, 9 years, 68%, 100%, 80%+
- Fees: current placeholder "confirmed per programme" vs PDF same but intro different
- Career stories: current maybe placeholder vs PDF 4 exact stories (Ahmed Dubai, Fatima Riyadh, Khalid Doha, Sara Abu Dhabi)
- Alumni: current generic vs PDF exact heading "Alumni in the UAE and Across the GCC" + copy + trust line
- Partners: current vs PDF 5 partners exact with accreditation details
- FAQ: current maybe old vs PDF 12 exact Q&A
- Final CTA: current vs PDF exact heading and copy

---

## PHASE 2: GAP ANALYSIS - PDF vs CURRENT

| PDF Section | Current Status | Action | Priority |
|-------------|----------------|--------|----------|
| 1 HERO | Exists but wrong wording | REPLACE exact PDF: eyebrow, heading, subheading, form title "Get the programme guide", buttons Apply Now + Request Fee Plan | P0 |
| 2 TRUST | Exists but wrong stats | REPLACE exact: label "Rated 4.9 out of 5...", quote Rajesh Menon, stats 5 exact | P0 |
| 3 OVERVIEW | Exists but generic | REPLACE exact: heading "MBA in Dubai: What This Programme Gives You", intro, 5 items exact titles+descriptions, buttons Explore MBA families + Ask advisor | P0 |
| 4 WHY | Exists but unknown | REPLACE exact: heading "Why an MBA for working professionals in UAE makes sense right now", intro, 6 blocks exact | P0 |
| 5 JOURNEY | Commented | ENABLE + REPLACE exact: heading "How to Start: 5 Steps", intro, 5 steps exact | P0 |
| 6 MBA CATEGORIES | Exists but from old listing.pdf | REPLACE exact: heading "MBA specializations in UAE: Pick Your MBA", intro 4 routes, 5 tabs with 12+6+16+1+1 programmes exact titles, closing line | P0 |
| 7 MASTERS | Exists but old | REPLACE exact: heading "Master's Degrees Beyond the MBA", intro, 3 universities exact (Rushford 9 MSc, GAU 4 MSc thesis, Wolverhampton LLM, UWS MBA), trending picks 3 exact | P0 |
| 8 CLASS OF 2025 | Hardcoded generic | REDESIGN + REPLACE exact: heading "Class of 2025", circle "Built for the GCC", metrics 5 exact, bottom text diverse cohort founders etc | P0 |
| 9 COHORT | Missing / wrong (class-snapshot shows world flags) | CREATE NEW SECTION "Your Classmates: UAE and GCC Professionals" - audience exact, regions 4 exact (UAE half Dubai Abu Dhabi Sharjah, Saudi quarter Riyadh Jeddah NEOM, Oman Muscat, Qatar Doha), industries 8 exact | P0 |
| 10 FEES | Exists but intro generic | REPLACE exact: heading "MBA Fees in UAE: What You Actually Pay", intro AED 16k-40k one third campus, table 6 rows exact, note, 3 blocks exact (flexible payment AED no interest, what fee covers 5 bullets, scholarships early-bird) | P0 |
| 11 CAREER STORIES | Exists but maybe placeholder | REPLACE exact: heading "What UAE Graduates Did Next", intro, 4 stories exact (Ahmed Dubai Logistics, Fatima Riyadh HR, Khalid Doha Branch Manager, Sara Abu Dhabi Marketing) | P0 |
| 12 ALUMNI | Exists | REPLACE exact: heading "Alumni in the UAE and Across the GCC", copy, trust line "Company logos shown with permission..." | P0 |
| 13 PARTNERS | Exists | REPLACE exact: heading "International MBA Degrees from Our University Partners", intro, 5 partners exact with accreditation (Rushford EduQua IACBE 12 MBAs 9 MSc, GAU YÖDAK YÖK IACBE 6 MBAs 16 EMBAs 4 MSc, UCA UK Global MBA, Wolverhampton UK LLM, UWS UK MBA International Business), checklist 4 bullets exact | P0 |
| 14 LEARNING | Missing | CREATE NEW SECTION "How Online Learning Actually Works" - intro, 5 points exact (Live evening classes 2/week recorded, Dedicated success coach named, Online exams from home, Project on own business, Career-relevant assessment) | P0 |
| 15 TESTIMONIALS | Exists | REPLACE heading exact "What Students Say in Their Own Words" (keep existing testimonial items as fallback) | P1 |
| 16 COMPARE | Commented | ENABLE + REPLACE exact: heading "Online MBA vs Classroom MBA: A Fair Comparison", intro, table 7 rows exact (fees, commute, class timing, visa, study while working, award, networking), 2 blocks exact (Fast-track or standard, Same award either way) | P0 |
| 17 FAQ | Exists but old | REPLACE exact: heading "Frequently Asked Questions", 12 Q&A exact word-for-word | P0 |
| 18 FINAL CTA | Exists | REPLACE exact: heading "Your Master's Starts With One Conversation", copy exact Share CV LinkedIn advisor Sharjah map route fees Sep 2026 intake 24h no obligation no pressure no visa WhatsApp same day | P0 |

**Additional:**
- SEO: Update meta title/description to PDF context? PDF doesn't have SEO but we can keep existing or update to "Online MBA & Master's Degrees for the UAE and GCC | Maverick"
- Keep sections.accreditations and video-testimonials and video-proof as extra social proof (not in PDF but valuable) - OR remove if strict PDF only? Decision: Keep but after PDF sections, as they don't harm, but ensure PDF content is primary.

---

## PHASE 3: DESIGN SYSTEM & CREATIVE DIRECTION (Graphic Expert Role)

### 3.1 Brand Compliance (Never Override)
- Colors: MBA blue `#0f2983`, navy `#071444`, red `#b20202`, warm white `#f5f0eb`
- Fonts: PP Neue Montreal (display), Poppins (body)
- Prefix: `mlp-`
- Banned: white form card with soft shadow, equal white stat tiles, pill eyebrows, soft gray-blue gradients, centered uppercase labels as sole hierarchy, uniform card grids, clip-path gimmicks, playful rounded UI, emoji icons

### 3.2 Approved Visual Language
- Mood: Light premium education × editorial storytelling, warm paper/white chapters, navy+red accents, hero cinematic photo plate, rest light
- Surfaces: paper, surface-soft, void (hero only), glass (enquiry), line-dark hairlines
- Type scale: mlp-display, mlp-headline, mlp-lede, mlp-meta (0.7-0.8rem tracking 0.14em uppercase, NOT pill), mlp-stat (2.5-4.5rem oversized)
- Buttons: solid red sharp radius 0-2px min-height 48px, ghost hairline border, text underline offset, never rounded-full pills

### 3.3 Motif Map for Missing Sections (Creative, Not Generic)

#### **JOURNEY - Section 5: How to Start: 5 Steps**
**Current file:** `journey.blade.php` exists with orb + rings + spine - GOOD foundation, but needs PDF content + enhanced design.

**Graphic Expert Concept: "The Admission Constellation"**
- Visual: Keep orb + rings but add **constellation map** - 5 steps as stars connected by dashed spine that fills on scroll (progressive disclosure)
- Layout: 
  - Left: Editorial header with index "05" ghost, label, heading, intro, CTA
  - Right: Vertical board with spine-track (hairline) + spine-fill (red, grows on scroll via ScrollTrigger)
  - Each step: marker with number 01-05 inside circle (navy border, red pulse dot), panel with title + text
  - Alternate: On desktop, steps slightly staggered left-right for rhythm (not uniform)
- Animation:
  - `mlpReveal` for head (y: 32)
  - Spine fill: ScrollTrigger scrub, height 0->100% as user scrolls through steps
  - Each step: `whenInView` with x: 24, opacity 0->1, pulse scale 1->1.2 infinite
  - Numbers: count-up? No, static but with `data-mlp-count` for step number? Keep simple
- Responsive: Mobile spine left-aligned, steps full width, no stagger
- Content: Exact PDF 5 steps word-for-word, no trimming

**Files to update:**
- `journey.blade.php` content (steps)
- `mba-masters-landing.css` - add `.mlp-journey__spine-fill` animation
- `mba-masters-landing.js` - already has journey logic, verify rescuePastReveals

#### **COHORT - Section 9: Your Classmates: UAE and GCC Professionals**
**Current:** `class-snapshot.blade.php` shows world flags (27 countries) - WRONG for GCC focus. Need new design.

**Graphic Expert Concept: "The GCC Atlas - Four Markets, One Classroom"**
- Visual: 
  - Top: Audience paragraph as lede, centered, max-width 60ch
  - Middle: Regions as **editorial atlas** - 4 large cards (UAE, Saudi, Oman, Qatar) with:
    - Left: Country name + proportion (around half, about quarter, growing, steady) as oversized stat
    - Right: Cities list (Dubai Abu Dhabi Sharjah etc) as meta
    - Background: Subtle map outline (SVG) of GCC, with dot for each city
    - Hover: Card lifts slightly, dot pulses
  - Bottom: Industries as **8-column typographic strip** on desktop, 2x4 grid mobile:
    - Each industry: icon (lucide) + name, hairline divider, no card background (just type + line)
    - Icons: Energy=zap, Logistics=truck, Banking=landmark, Government=building-2, Healthcare=heart-pulse, Education=graduation-cap, Real Estate=home, Tech=code
- Layout: Full-bleed light chapter, ghost "09" top-right, header with label + heading + intro, then atlas grid
- Animation:
  - Regions: stagger reveal x: -32, each 0.1s delay
  - Industries: `mlpReveal` with y: 16, stagger 0.05
  - Map dots: pulse via CSS keyframes, triggered whenInView
- Responsive: Regions stack vertically on mobile, industries 2 columns
- Content: Exact PDF audience, 4 regions with cities, 8 industries

**New file:** `cohort.blade.php` (or update `class-snapshot.blade.php` to match PDF, rename? Better create new `cohort.blade.php` and include in main, keep old snapshot as fallback or remove)

**Decision:** Create new `cohort.blade.php`, update main blade to include it after class-2025, and keep class-snapshot for now but hide if needed. Or replace class-snapshot content with cohort.

Simpler: Update `class.blade.php` to be cohort section (since it already has regions+industries structure), and update `class-snapshot.blade.php` to be removed or repurposed.

But to avoid breaking, create new file `cohort.blade.php` with fresh design, and update main blade.

#### **LEARNING - Section 14: How Online Learning Actually Works**
**Current:** Missing entirely

**Graphic Expert Concept: "The Learning Atelier - Structured, Not Recorded"**
- Visual: Based on design system p2 green split motif: diagonal media plane (navy/red veil + clipped photo plate + numbered study points)
- Layout:
  - Left: Large photographic plate (diagonal clipped) - image of student studying evening, laptop, warm light, with navy wash + red accent line diagonal
  - Right: Content stack:
    - Header: label, heading, intro (lede)
    - Points: 5 numbered rows (01-05) with:
      - Number as oversized ghost (mlp-stat) left
      - Title bold (Live evening classes etc)
      - Description regular
      - Hairline divider between rows
      - Icon optional (calendar, user-check, monitor, briefcase, award)
- Background: Warm paper with subtle grid lines (like blueprint)
- Animation:
  - Plate: parallax y: -20 on scroll
  - Points: slideReveal alternating x: 56 / -56 per design system
  - Numbers: count-up? No, static
- Responsive: Plate top on mobile, content below, diagonal clip becomes horizontal
- Content: Exact PDF intro + 5 points

**New file:** `learning.blade.php`

**CSS:** Add `.mlp-learning` with diagonal clip `clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%)` for plate, responsive override

#### **COMPARE - Section 16: Online MBA vs Classroom MBA**
**Current:** `compare.blade.php` exists with archive-parallel design - GOOD foundation, but commented and content old

**Graphic Expert Concept: "The Honest Ledger - Two Paths, Same Award"**
- Visual: Keep archive-parallel but enhance:
  - Header: intro as lede, max-width
  - Table: 
    - Columns: Aspect | This online MBA | Classroom MBA
    - Header row sticky? No, simple
    - Rows: Each aspect as criterion left, then two sides with prose, online side has check icon + navy background subtle, traditional has neutral
    - Icons per criterion from existing map: schedule=calendar, location=map-pin, duration=clock, cost=wallet, career=trending-up, awarding=building-2, default=circle-dot
  - Blocks below table: 2 cards side-by-side, one with fast-track, one with same award, each with icon + title + text, red accent top border
- Layout: Full-bleed light, ghost "16", container, intro-grid
- Animation:
  - Rows: `data-closing-element` already, slideReveal
  - Table: horizontal scroll on mobile with fade edges
- Responsive: Table becomes stacked cards on mobile (each row as card with online vs traditional stacked)
- Content: Exact PDF table 7 rows + 2 blocks

**Files:** Update `compare.blade.php` content to PDF exact, ensure main blade uncomments it

#### **CLASS OF 2025 - Section 8: Redesign**
**Current:** `class-2025.blade.php` hardcoded SVG blueprint with no metrics - needs PDF exact

**Graphic Expert Concept: "The GCC Circle - Built for the Region"**
- Visual:
  - Center: Circular badge "MBA - 2025" with "Built for the GCC" around circumference (text on path)
  - Around circle: 5 metrics as radial nodes connected by arcs (like current SVG but with real data)
  - Bottom: Text about diverse cohort founders etc as editorial paragraph
  - Background: Light with subtle contour lines (mlp-contour)
- Layout:
  - Top: Header with label, heading "Class of 2025"
  - Middle: Circle + metrics radial (desktop) / vertical list mobile
  - Bottom: Bottom text as centered lede
- Animation:
  - Circle: draw SVG path on scroll
  - Metrics: count-up for numbers (35, 9, 68%, 100%, 80%+) via `data-mlp-count`
  - Nodes: pulse
- Content: Exact PDF heading, circle text, 5 metrics, bottom text

**Update:** `class-2025.blade.php` to dynamic using $class or hardcoded PDF exact

### 3.4 Animation & Interaction Plan (Follow mlp-scroll-reveal-fix.md)

- Use `MLPMotion` primitives only: `reveal`, `slideReveal`, `whenInView`, `isPastStart`, `rescuePastReveals`
- No new GSAP timelines that bypass rescue logic (to avoid mid-page refresh bug)
- Reduced motion: all content visible immediately, no hide
- Timing: 0.6-1.0s, power3.out / expo.out, no bounce
- Sticky: WhatsApp + Apply glass navy bar, already exists

### 3.5 Responsive Design

- Mobile first, but desktop editorial
- Breakpoints: 768px, 1024px, 1280px
- Hero: stack, form below statement on mobile
- Journey: spine left, steps full width mobile
- Cohort: regions stack, industries 2-col mobile
- Learning: plate top, content below mobile, diagonal clip removed
- Compare: table -> stacked cards mobile
- All sections: container padding 16px mobile, 24px tablet, 32px desktop
- Images: fixed dimensions as per design system (72x72 industries, 112x140 portraits, 180x72 logos) to avoid CLS

---

## PHASE 4: CONTENT REPLACEMENT STRATEGY - EXACT WORD PRESERVATION

### 4.1 Principle
- **No paraphrasing, no trimming, no marketing fluff added**
- Every heading, intro, item title, description, button label, note, checklist, table cell must match PDF exactly, including punctuation, casing, line breaks where meaningful
- Use `MlpProse::html()` for rich text but keep plain text exact
- For lists, preserve order exactly as PDF

### 4.2 Settings Structure Mapping (Spatie)

We will create a new settings migration that UPDATES all existing groups with PDF exact content:

**File:** `database/settings/2026_09_18_100000_sync_mba_masters_pdf_exact_content.php`

**Groups to update:**

1. **mba_masters_hero:**
   - eyebrow: `International degrees. No visa needed. Study from the UAE.`
   - headline: `Online MBA & Master's Degrees for the UAE and GCC`
   - subheading: `University-awarded Master's degrees designed for full-time professionals. Choose an MBA or a Master's from Switzerland, North Cyprus, or the UK without leaving your job, without relocating, and without a student visa. The September 2026 intake is now open.`
   - form_title: `Get the programme guide`
   - cta_primary_label: `Apply Now`
   - cta_primary_url: `#mlp-enquire`
   - cta_secondary_label: `Request Fee Plan`
   - cta_secondary_url: `#mlp-fees` (or #mlp-enquire, but PDF says Request Fee Plan -> should go to fees section)
   - cta_tertiary_label: `Speak to an Advisor`? PDF has only 2 buttons, but we keep tertiary as null or same as secondary
   - background_image: keep existing or use `assets/images/edutainment/hero-cinematic.jpg`

2. **mba_masters_trust:**
   - label: `Rated 4.9 out of 5 by professionals who studied with us`
   - quote: `"One of the best decisions I have made. The degree is genuinely university-awarded, and my employer in Abu Dhabi had zero questions about its validity."` + attribution? The quote field currently only holds quote, but we need to include attribution? PDF shows quote + "Rajesh Menon, MBA graduate, Abu Dhabi" - we can include in quote or separate. For exact, put full quote with attribution in same field or split? Better include full as in PDF: quote field contains both lines.
   - stats: 5 items exact:
     - `4.9/5 average student rating (Trustpilot)`
     - `9,000+ Learners Empowered`
     - `100% online. No visa. No relocation.`
     - `4,500 Students Supported`
     - `20+ University Partners`
   - But stats structure is value+label, so need to parse: e.g. value `4.9/5` label `average student rating (Trustpilot)`, value `9,000+` label `Learners Empowered`, etc.

3. **mba_masters_overview:**
   - index: `03`
   - label: `Program overview`? PDF doesn't have label, but we can keep or use `Program overview` or derive from heading? PDF heading is "MBA in Dubai: What This Programme Gives You" - use that as heading, label as "Program overview"
   - heading: `MBA in Dubai: What This Programme Gives You`
   - intro: `An MBA in Dubai for working professionals should change how you work without pausing your life. From week one to graduation, every cohort gets the five things below.`
   - items: 5 exact with title + text:
     - Title `A strong learning community with powerful networking` + text `You study alongside founders, bankers, and government specialists from the UAE, Saudi Arabia, Oman, and Qatar. The live-class circles and WhatsApp groups that carry you through the degree become your professional network after it.`
     - `ASK Quotient development` + `Every module builds the three things employers screen for: Attitude, Skills, and Knowledge. Your success coach tracks your growth across all three, so you graduate measurably stronger, with evidence to show for it.`
     - `Skills-based MBA learning` + `Assignments are built as workplace deliverables: a business plan, a market entry analysis, a team leadership review. You submit work you can reuse at your job, and your manager sees the difference before you graduate.`
     - `Knowledge-based MBA learning` + `Core theory comes from your university's faculty, set in current Gulf business context and distilled into focused evening modules. Short readings, clear frameworks, and session recordings keep the load manageable beside a full-time role.`
     - `Real-world case studies` + `Cases are drawn from live Gulf businesses, and your final project solves a problem inside your own company. Several graduates have taken that project straight into their next performance review.`
   - cta_primary_label: `Explore the MBA families`
   - cta_primary_url: `#mlp-mba`
   - cta_secondary_label: `Ask an advisor`
   - cta_secondary_url: `#mlp-enquire`
   - plate_image: keep

4. **mba_masters_why:**
   - index: `04`
   - label: `Why choose Maverick`? PDF doesn't have label but heading is "Why an MBA for working professionals in UAE makes sense right now" - use that
   - heading: `Why an MBA for working professionals in UAE makes sense right now`
   - intro: `A Master's is not a theory course. It is a working professional's fastest route to a wider role, a stronger salary case, and international recognition, without pausing income.`
   - chapters: 6 blocks exact:
     - `Learn without pausing your salary` + `Classroom-based programmes ask you to quit, relocate, or wait for a weekend slot. This degree runs in the evenings and on weekends, live from the UAE, a genuinely flexible MBA schedule that keeps your salary intact. You study after office hours, apply what you learn the very next working day, and never lose a single dirham of income to your education.`
     - `Your certificate comes from the university itself` + `Your certificate is issued by the awarding university, with the same academic standing as on-campus study. It is recognised worldwide, including across the GCC.`
     - `Built for promotion and transition` + `Every module maps to skills UAE employers screen for: leadership, finance, operations, and strategy. The final project lets you solve a live business problem from your own workplace. Graduates regularly point to that project in interviews, because it is concrete proof that they can turn theory into results.`
     - `Pay in AED instalments` + `A campus MBA in UAE cities typically costs AED 80,000 to 200,000. Online, total fees land between AED 16,000 and 40,000, with no upfront full payment and a schedule that matches your monthly salary.`
     - `A recognised route, explained honestly` + `Study is delivered fully online from the UAE. Degrees from an accredited institution can be submitted for MoHESR recognition. Recognition depends on your circumstances, so confirm your category with an advisor. If recognition matters for your goal, we help you map the right paperwork early.`
     - `Sharjah office, not a call centre` + `Local counsellors who know the UAE market, employer expectations, and the recognition process. Visit the office, or meet an advisor on WhatsApp. The same team stays with you from your first call to graduation.`

5. **mba_masters_journey:**
   - index: `05`
   - label: `Admission journey` or `How to Start`
   - heading: `How to Start: 5 Steps`
   - intro: `Most applicants complete the steps below in 2 to 4 weeks. An advisor walks every step with you. The September 2026 intake is now open.`
   - steps: 5 exact:
     - Title `Free 15-minute eligibility check` + Text `Share your CV or LinkedIn with an advisor. No documents needed yet.`
     - `Confirm admission requirements and fees` + `Within 24 hours you get the right programme, total fees, and intake dates.`
     - `Reserve your seat for the September 2026 intake` + `Pay the first instalment to secure it. Seats are limited per intake.`
     - `Enrolment and induction` + `Receive portal access, orientation, and your study schedule in week one.`
     - `Start studying online` + `Live evening classes, recorded sessions, and an assigned success coach. You know exactly what to study each week, from week one.`
   - cta_label: `Start your enquiry`
   - cta_url: `#mlp-enquire`

6. **mba_masters_mba:**
   - index: `06`
   - label: `MBA specializations`
   - heading: `MBA specializations in UAE: Pick Your MBA`
   - intro: `Four MBA routes on one page: twelve specializations at Rushford Business School, six MBAs and sixteen Executive MBAs at Girne American University, and a Global MBA awarded in the UK. Every programme is university-awarded and fully online. Compare them side by side, then pick the one that matches your next role.`
   - tabs: Structure is complex - current uses tabs with universities array. Need to map PDF's 5 tabs:
     - Tab key `rbs-mba` label `MBA, Rushford Business School (Switzerland)` - 12 programmes exact titles + description `Works in any industry: the curriculum is built around leadership, strategy, and decision-making. Ideal for team leads, operations managers, and consultants moving into senior roles.`
     - Tab `gau-mba` label `MBA, Girne American University (North Cyprus)` - 6 MBAs exact
     - Tab `gau-emba` label `Executive MBA, Girne American University (North Cyprus)` - 16 EMBAs exact
     - Tab `global-mba` label `Global MBA, University for the Creative Arts (UK) with Rushford` - 1 programme + description `One degree with an international perspective, for careers across borders. Popular in logistics, trade, and regional HQ roles across Dubai and Abu Dhabi.`
     - Tab `uws-mba` label `MBA in International Business, University of the West of Scotland (UK)` - 1 programme + description `A UK-awarded MBA focused on cross-border trade, global strategy, and international teams. A strong fit for professionals in logistics, import-export, and regional HQ roles.`
   - Also need closing line: `Not sure which route fits? Ask an advisor and get a recommendation within 24 hours. The right pick depends on your experience and your next role, and the call is free.`
   - For simplicity, store tabs as array with label, description, programmes array, closing line as part of intro or separate field? We have intro field, but closing line can be appended to intro or stored as part of last tab. Better to store as part of settings: add extra field? But to keep exact, we will include closing line in intro or as separate setting? For now, include in intro as second paragraph.

7. **mba_masters_masters:**
   - index: `07`
   - label: `Master's programs`
   - heading: `Master's Degrees Beyond the MBA`
   - intro: `The same award route, applied to high-demand fields: sustainability, supply chain, economics, healthcare, and law.`
   - universities: 3 (or 5 with UCA and UWS) - PDF lists:
     - Rushford Business School (Switzerland), MSc - 9 programmes
     - Girne American University (North Cyprus), MSc with Thesis - 4 programmes
     - University of Wolverhampton (UK), Master of Laws - 1
     - Also need to include UWS? PDF mentions UWS under MBA categories, but for Masters, trending picks are separate. We'll include 3 universities + trending.
   - trending_title: `Trending|Specialisations`? PDF says "TRENDING PICKS" - 3 items: Affordable MBA in Finance, HRM, Healthcare Leadership all Rushford Online instalments available. So trending array 3 items exact.
   - For exact PDF, we should also include UWS and UCA as part of MBA categories, not Masters. So Masters universities = 3.
   - Structure: universities array with name, programmes, description (Specialist MSc pathways... and Thesis-based... and UK-awarded LLM...)

8. **mba_masters_class:** (This will be used for both Class of 2025 and Cohort? We need to decide)
   - For PDF Section 8 and 9, we have overlapping use. Let's split:
   - Use `mba_masters_class` for Cohort (Section 9) as it already has regions and industries:
     - label: `Cohort` or `Class profile`
     - heading: `Your Classmates: UAE and GCC Professionals`
     - intro: (maybe audience? But we have audience field)
     - audience: `The average student on this page works full time in the UAE or the wider Gulf and studies in the evenings and on weekends. Most join to move into senior roles without taking a career break.`
     - metrics: For Class of 2025 metrics? But cohort also has metrics? Actually PDF Section 8 has metrics, Section 9 has regions+industries. So we need metrics for Class of 2025, and regions+industries for Cohort. Since same settings group, we can store both:
       - metrics: 5 metrics exact for Class of 2025:
         - `35` `median age in the current cohort`
         - `9 years` `of average work experience` (PDF says "9 years of average work experience")
         - `68%` `hold mid-level or senior roles`
         - `100%` `employed full time while studying`
         - `80%+` `sponsored or supported by their employer`
       - regions: 4 exact:
         - `UAE` `around half the cohort · Dubai, Abu Dhabi, and Sharjah`
         - `Saudi Arabia` `about one quarter · Riyadh, Jeddah, and the NEOM region`
         - `Oman` `growing every intake · Muscat`
         - `Qatar` `steady presence · Doha`
       - industries: 8 exact:
         - `Energy & Oil`
         - `Logistics & Trade`
         - `Banking & Finance`
         - `Government`
         - `Healthcare`
         - `Education`
         - `Real Estate`
         - `Tech & Consulting`
     - We also need bottom text for Class of 2025: `The most diverse cohort we have run in the Gulf: founders, government specialists, bankers, and senior operators from the UAE, Saudi Arabia, Oman, and Qatar. You learn from each other as much as from the faculty. Group work brings four markets into one discussion, which is exactly how regional business runs.` - where to store? Could use intro or audience? But audience already used for Cohort audience. We need extra field? For now, we can store bottom text as part of intro second paragraph or create new setting? Simplest: Use `intro` for Class of 2025 bottom text, and `audience` for Cohort audience. And heading for Cohort, but Class of 2025 heading is different. We need two headings. We have only one heading field. So we need to handle Class of 2025 separately - maybe create new settings group or use hardcoded in blade with PDF exact but also store in settings.

   - **Decision:** For auto-insert, we will update `mba_masters_class` with Cohort data (heading = Your Classmates..., audience = average student..., regions 4, industries 8, metrics 5 for Class of 2025). And for Class of 2025 section, we will update `class-2025.blade.php` to be hardcoded with PDF exact content, but also pull metrics from $class metrics if available. That way, terminal command will insert cohort data, and class-2025 will be exact via blade.

   - Alternatively, create new settings group `mba_masters_class_2025` but that requires new Settings class and migration. Simpler to keep existing and update blade.

   - For this plan, we will update `mba_masters_class` settings to match PDF Section 9 + metrics from Section 8, and update `class-2025.blade.php` to read from same settings but with fallback to PDF exact hardcoded.

9. **mba_masters_fees:**
   - index: `10`
   - label: `Fees`
   - heading: `MBA Fees in UAE: What You Actually Pay`
   - intro: `Total programme cost, no hidden lines. Across programmes, fees typically fall between AED 16,000 and 40,000, roughly one third of what a campus MBA costs in Dubai today. Every payment is confirmed in writing before your seat is reserved.`
   - rows: 6 exact with Programme, Mode, Fees, Duration, Payment:
     - `MBA, Rushford (12 specializations)` | `Online · Hybrid · Part-time` | `confirmed per programme` | `10 to 15 months` | `Current fee sheet on request`
     - `MBA, Girne American University` | same | same | `10 to 15 months` | same
     - `Executive MBA, Girne American University` | same | same | `12 to 18 months` | same
     - `Global MBA, UCA with Rushford` | same | same | `10 to 15 months` | same
     - `MSc (Rushford, Girne) and LLM (Wolverhampton)` | same | same | `8 to 18 months` | same
     - `MBA in International Business, UWS` | same | same | `confirmed per programme` | same
   - note: `Fees depend on the programme and intake. Your advisor confirms the exact figure in writing before you pay anything.`
   - Plus blocks: We need to store blocks? Current fees settings has rows, cta, stage_image, note. But PDF has 3 blocks: flexible payment plan AED no interest, what fee covers 5 bullets, scholarships early-bird. We can store these as part of intro or as separate? For simplicity, store as part of note or as additional fields? But settings class doesn't have blocks field. We have `rows` only. We need to extend settings or store blocks in intro? Better to create new fields or use existing? Let's check `MbaMastersFeesSettings` - has index, label, heading, intro, note, stage_image, stage_image_asset_id, rows, cta_primary_label, cta_primary_url, cta_secondary_label, cta_secondary_url. No blocks. So we need to either add new fields via migration or store blocks as part of note with HTML? For exact PDF, we need to display 3 blocks. We can update blade to have hardcoded blocks exact from PDF, not from settings, but then admin panel won't have them. Better to add new settings fields via migration? But for auto-insert, we can update blade to include PDF exact blocks hardcoded, and settings for table.

   - **Decision:** Update `fees.blade.php` to include 3 blocks hardcoded exact from PDF, plus table from settings. That way, PDF content is exact and auto-insert via settings for table, blocks via blade.

   - Similarly for other sections with blocks not in settings, we will hardcode exact PDF in blade but also ensure settings have data for admin.

   - For this migration, we will update fees settings with heading, intro, rows, note exact.

10. **mba_masters_career:**
    - index: `11`
    - label: `Career stories`
    - heading: `What UAE Graduates Did Next`
    - intro: `Recent outcomes from students across the Gulf. Every story below started with a full-time job and evening study.`
    - stories: 4 exact:
      - `Ahmed, Operations Lead, Dubai` + `MBA in Logistics & Supply Chain Management` + `Moved into a regional operations role six months after graduating. His employer supported the fees.` + previous_role? current_role? For exact, store as name, country? Let's map: name `Ahmed`, country `Dubai`? Actually PDF: "Ahmed, Operations Lead, Dubai MBA in Logistics & Supply Chain Management role six months..." We need to parse. Better store as: name `Ahmed`, role `Operations Lead, Dubai`, program `MBA in Logistics & Supply Chain Management`, quote `Moved into a regional operations role six months after graduating. His employer supported the fees.`
      - Similarly Fatima, Khalid, Sara.

11. **mba_masters_alumni:**
    - index: `12`
    - label: `Alumni`
    - heading: `Alumni in the UAE and Across the GCC`
    - intro: `Our graduates work in UAE government departments, free zones, banks, hospital groups, and multinationals across the Gulf. The network grows with every intake, and many students arrive through referrals from colleagues who already studied with us. Ask your advisor for alumni references in your industry before you commit.`
    - trust_line: `Company logos shown with permission from alumni employers.`

12. **mba_masters_partners:**
    - index: `13`
    - label: `University partners`
    - heading: `International MBA Degrees from Our University Partners`
    - intro: `Every Master's on this page is awarded by the university named below. You apply once, study online, and graduate from the university that issues your certificate. One application covers admission, enrolment, and your study plan.`
    - trust_line: Not in PDF but we have checklist. For partners, we need to store partners list? Actually partners are from UniversityPartner model, not settings. But settings has trust_line only. So we will keep trust_line empty and handle checklist via blade hardcoded exact.

13. **mba_masters_learning:** (We need to create or update)
    - This settings group exists but maybe unused. Let's check: `MbaMastersLearningSettings` has index, label, heading, intro, plate_image, plate_caption, points, ctas. Perfect for Learning section.
    - heading: `How Online Learning Actually Works`
    - intro: `Not recorded videos that gather dust. Structured learning with real people around you. The platform is built for busy schedules: focused modules, clear weekly goals, and support that replies within one working day.`
    - points: 5 exact:
      - `Live evening classes` `Two live sessions per week, recorded if you miss one`
      - `Dedicated success coach` `One named coach for your whole degree, from induction to graduation`
      - `Online exams, from home` `No travel for assessments. Clear rubrics, timely feedback`
      - `Project on your own business` `Apply each module to a live challenge from your workplace`
      - `Career-relevant assessment` `Projects, presentations, and portfolios you can show your employer`

14. **mba_masters_testimonials:**
    - heading: `What Students Say in Their Own Words`
    - Keep items as is or empty, since PDF only has heading

15. **mba_masters_compare:**
    - index: `16`
    - label: `Comparison`
    - heading: `Online MBA vs Classroom MBA: A Fair Comparison`
    - intro: `If you have ever weighed a part-time MBA on campus against an online one, this table is the honest answer. Same degree standard, different delivery.`
    - col_online: `This online MBA`
    - col_traditional: `Classroom MBA`
    - rows: 7 exact:
      - `Total fees` `AED 16,000 to 40,000` `Typically AED 80,000 to 200,000+`
      - `Commute` `Zero` `3 to 5 hours a week in traffic`
      - `Class timing` `Evenings and weekends, from home` `Fixed campus timetable`
      - `Visa needed` `No` `Yes for international campuses`
      - `Study while working` `Yes, designed for it` `Often requires a break`
      - `Award on certificate` `UK university degree` `UK university degree`
      - `Networking` `Live cohort events and an active WhatsApp community` `Campus cohorts`
    - Plus blocks: Fast-track or standard, Same award either way - store as cta? Or hardcoded in blade. We'll hardcode blocks in blade exact.

16. **mba_masters_faq:**
    - index: `17`
    - label: `FAQ`
    - heading: `Frequently Asked Questions`
    - items: 12 exact Q&A word-for-word from PDF

17. **mba_masters_final:**
    - index: `18`
    - label: `Final CTA`
    - heading: `Your Master's Starts With One Conversation`
    - intro: `Share your CV or LinkedIn profile, and an advisor in Sharjah will map your route, your total fees, and the September 2026 intake within 24 hours. No obligation, no pressure, and no student visa required. If a Master's is the right next step, this is the fastest way to confirm it. Prefer WhatsApp? Message us and an advisor replies the same day.`
    - show_form: true
    - form_title: `Get the programme guide` or similar

18. **mba_masters_seo:**
    - meta_title: `Online MBA & Master's Degrees for the UAE and GCC | Maverick Business Academy`
    - meta_description: `University-awarded Master's degrees for UAE and GCC professionals. MBA & Master's from Switzerland, North Cyprus, UK. 100% online, no visa, AED instalments. September 2026 intake open.`
    - Keep others

### 4.3 Auto-Insert via Terminal

**Command to run:**
```bash
php artisan settings:migrate
# or
php artisan migrate --path=database/settings/2026_09_18_100000_sync_mba_masters_pdf_exact_content.php
# or custom command
php artisan mba:seed-pdf-content
```

We will create **both**:
1. Settings migration file that updates all groups (runs via `php artisan migrate`)
2. Artisan command `app/Console/Commands/SyncMbaMastersPdfContent.php` that does same but idempotent, can be run anytime: `php artisan mba:sync-pdf`

This command will use `app(MbaMasters*Settings::class)` and save.

**No manual admin insert needed.**

---

## PHASE 5: TECHNICAL IMPLEMENTATION STEPS

### Step 1: Create Settings Migration (Auto-Insert)
- File: `database/settings/2026_09_18_100000_sync_mba_masters_pdf_exact_content.php`
- Use `$this->migrator->update()` for each group with closure returning PDF exact content
- Ensure all 18 groups covered

### Step 2: Create Artisan Command (Alternative Auto-Insert)
- File: `app/Console/Commands/SyncMbaMastersPdfContent.php`
- Command signature: `mba:sync-pdf`
- Logic: Load each Settings class, set properties to PDF exact, save
- Output: Success messages per group

### Step 3: Update Blade Templates to Match PDF Exact Wording

**Files to update:**
- `hero.blade.php` - ensure form title from $hero->form_title (Get the programme guide), buttons exact
- `trust.blade.php` - parse stats to handle 5 stats exact, quote with attribution
- `overview.blade.php` - ensure 5 items exact, buttons exact
- `why.blade.php` - ensure 6 chapters exact
- `journey.blade.php` - ensure 5 steps exact, un-comment in main blade
- `mba.blade.php` - update to handle 5 tabs exact, with closing line
- `masters.blade.php` - update to handle 3 universities + trending 3 exact
- `class-2025.blade.php` - redesign to PDF exact: heading, circle Built for GCC, metrics 5, bottom text
- `cohort.blade.php` - CREATE NEW with audience, regions 4, industries 8 exact, design as GCC Atlas
- `fees.blade.php` - update table 6 rows exact, add 3 blocks exact (flexible payment, what fee covers 5 bullets, scholarships)
- `career.blade.php` - update 4 stories exact
- `alumni.blade.php` - update heading, copy, trust line exact
- `partners.blade.php` - update heading, intro exact, add checklist 4 bullets exact
- `learning.blade.php` - CREATE NEW with heading, intro, 5 points exact, diagonal media plane design
- `compare.blade.php` - update table 7 rows exact, add 2 blocks exact, un-comment in main blade
- `faq.blade.php` - update 12 Q&A exact
- `final.blade.php` - update heading, copy exact

**Main blade:** `mba-masters-landing.blade.php`
- Uncomment journey and compare
- Add cohort and learning includes
- Order per PDF: hero, trust, overview, why, journey, mba, masters, class-2025, cohort (class-snapshot replaced), fees, career, alumni, partners, learning, testimonials, compare, faq, final
- Keep accreditations, video-testimonials, video-proof as extra? Decision: Keep after partners but before learning for social proof, or remove to strictly follow PDF? For now, keep but after PDF sections, with comment that they are extra.

### Step 4: Create New Blade Files
- `cohort.blade.php` - GCC Atlas design
- `learning.blade.php` - Learning Atelier design

### Step 5: Update CSS
- File: `public/assets/css/pages/mba-masters-landing.css`
- Add styles for:
  - `.mlp-cohort` - atlas, regions cards, industries strip
  - `.mlp-learning` - diagonal plate, numbered points
  - Ensure `.mlp-journey__spine-fill` animation
  - `.mlp-compare` responsive stacked cards
  - `.mlp-class-2025` radial metrics

### Step 6: Update JS (if needed)
- `mba-masters-landing.js` - ensure rescuePastReveals covers new sections
- Create `mba-masters-cohort.js` and `mba-masters-learning.js` if needed, or reuse existing primitives
- Add to main blade push scripts

### Step 7: Verification
- Run `php artisan mba:sync-pdf` or `php artisan migrate`
- Check admin panel: each Filament page should show PDF exact content
- Check frontend: `/online-mba-masters-uae` should render all 18 sections with exact wording
- Word-for-word diff: Compare PDF text vs rendered HTML textContent (strip tags) - should be 100% match for all sections
- Responsive: Test 375px, 768px, 1024px, 1440px
- Animation: Scroll through page, verify no opacity:0 stuck (rescue logic)
- No console errors

---

## PHASE 6: DESIGN PLAN DETAILS (Graphic Expert Deep Dive)

### Journey - Constellation
- **Color:** Navy void background for graphic area, white cards for steps
- **Typography:** Step number 01-05 as 48px PP Neue Montreal, title 20px, text 16px Poppins
- **Graphic:** SVG rings 180/240/300 radius, opacity 0.2/0.12/0.08, orb gradients blue->red
- **Interaction:** On scroll, spine-fill height animates, marker pulse scale 1->1.3 loop 2s
- **Responsive:** Spine left 16px mobile, steps margin-left 48px

### Cohort - GCC Atlas
- **Color:** Paper background, region cards with hairline border, hover navy border
- **Typography:** Region proportion as stat 32px, name 18px, cities 14px meta uppercase
- **Graphic:** GCC map outline SVG light gray, dots for cities navy
- **Layout:** Grid 2x2 desktop, 1 column mobile
- **Industries:** Strip with icons 24px, name 16px, divider vertical on desktop, horizontal mobile

### Learning - Atelier
- **Color:** Navy veil 80% opacity over image, red diagonal accent 4px line
- **Typography:** Number ghost 64px, title 18px bold, description 16px
- **Image:** Student evening study, warm light, 1200x800, clip-path polygon
- **Layout:** 50/50 split desktop, stack mobile
- **Animation:** Plate parallax -20px, points slide from right staggered

### Compare - Ledger
- **Color:** Online column light blue tint #f0f4ff, traditional white, header navy text white
- **Typography:** Criterion 16px bold, side prose 14px
- **Table:** Border-collapse, hairlines, check icon green for online
- **Blocks:** 2 cards with red top border 4px, icon 32px, title 18px
- **Responsive:** Table wrapper overflow-x auto with fade, mobile cards

---

## PHASE 7: FILE CHANGES LIST

**New Files:**
- `database/settings/2026_09_18_100000_sync_mba_masters_pdf_exact_content.php`
- `app/Console/Commands/SyncMbaMastersPdfContent.php`
- `resources/views/pages/mba-masters-landing/cohort.blade.php`
- `resources/views/pages/mba-masters-landing/learning.blade.php`
- `public/assets/js/pages/mba-masters-cohort.js` (optional)
- `public/assets/js/pages/mba-masters-learning.js` (optional)
- `docs/mba-masters-pdf-implementation-plan.md` (this file)

**Updated Files:**
- `resources/views/pages/mba-masters-landing.blade.php` (uncomment journey, compare, add cohort, learning, reorder)
- `resources/views/pages/mba-masters-landing/hero.blade.php` (ensure PDF exact)
- `trust.blade.php`, `overview.blade.php`, `why.blade.php`, `journey.blade.php`, `mba.blade.php`, `masters.blade.php`, `class-2025.blade.php`, `fees.blade.php`, `career.blade.php`, `alumni.blade.php`, `partners.blade.php`, `compare.blade.php`, `faq.blade.php`, `final.blade.php`
- `public/assets/css/pages/mba-masters-landing.css` (add cohort, learning, compare responsive, class-2025 radial)
- `app/Filament/Pages/MbaMastersLanding/*` (no need to update, they already edit settings, but ensure they handle new content)

**No manual admin needed - all via migration/command**

---

## PHASE 8: COMMIT PLAN

1. Create plan doc (this file) - commit
2. Create settings migration + artisan command - commit
3. Update blade files to PDF exact + new sections with creative design - commit
4. Update CSS/JS - commit
5. Run verification: `php artisan mba:sync-pdf` + visual check
6. Push to `arena/01a0b3d6-maverick` branch
7. Create PR to main with description: "PDF exact content + missing sections (Journey, Cohort, Learning, Compare) with creative editorial design, auto-insert via command"

---

## VERIFICATION CHECKLIST (Before Commit)

- [ ] PDF text extracted and stored in breakdown doc
- [ ] Every section heading matches PDF exactly (case, punctuation)
- [ ] Every intro matches PDF exactly
- [ ] Every item title+description matches PDF exactly
- [ ] Every button label matches PDF exactly
- [ ] Table rows match PDF exactly
- [ ] FAQ 12 Q&A match PDF exactly word-for-word
- [ ] Final CTA heading+copy match PDF exactly
- [ ] No extra words added, no trimming
- [ ] Missing sections added: Journey, Cohort, Learning, Compare
- [ ] Design system followed: colors, fonts, prefix mlp-, no banned patterns
- [ ] Animations use MLPMotion primitives with rescuePastReveals
- [ ] Responsive tested 375/768/1024/1440
- [ ] Auto-insert command works: `php artisan mba:sync-pdf`
- [ ] Admin panel shows PDF exact content after command
- [ ] Frontend renders PDF exact content
- [ ] No console errors, no CLS
- [ ] Commit with clear message

---

**End of Plan - Ready for Implementation**
