# DUAL MBA PROGRAMME — Landing Page Blueprint Document
## Maverick Business Academy London

---

## TABLE OF CONTENTS

1. [Content Analysis Summary](#1-content-analysis-summary)
2. [Information Architecture & Section Breakdown](#2-information-architecture--section-breakdown)
3. [Design System](#3-design-system)
4. [Animation Logic](#4-animation-logic)
5. [Component Library](#5-component-library)
6. [CTA Strategy](#6-cta-strategy)
7. [Backend-Ready Data Architecture](#7-backend-ready-data-architecture)
8. [Asset Requirements](#8-asset-requirements)

---

## 1. CONTENT ANALYSIS SUMMARY

### 1.1 Core Value Propositions (Ranked by Conversion Impact)

| # | Value Proposition | Conversion Role | Section Placement |
|---|-------------------|-----------------|-------------------|
| 1 | **Two MBA Degrees in One Programme** | Primary hook — instant differentiation | Hero, Sticky CTA |
| 2 | **1-Year Duration** | Urgency + feasibility signal | Hero Quick Stats, Programme Overview |
| 3 | **100% Online / Weekend Classes** | Removes friction for working professionals | Hero Quick Stats, Why Choose section |
| 4 | **Triple University Accreditation** (GAU, RBS, UCA) | Trust & credibility anchor | Trust Bar, Accreditation Section |
| 5 | **Future-Focused Specialisations** (AI, Analytics, etc.) | Relevance signal for modern professionals | Specialisations Grid |
| 6 | **Affordable Investment + Scholarships** | Reduces financial objection | Pricing/Investment Section |
| 7 | **Career Outcome Framing** (employer value, global opportunities) | Aspiration trigger | Why Employers Value, Career Outcomes |

### 1.2 Target Audience Personas

| Persona | Description | Primary Motivator | Key Content Hook |
|---------|-------------|-------------------|------------------|
| **Career Climber** | Mid-level professional (3-8 yrs experience) seeking promotion | "Get ahead faster" | Two degrees = double the credentials |
| **Career Switcher** | Professional pivoting to a new industry | "Break into a new field with confidence" | Specialisation options |
| **Entrepreneur** | Business owner seeking formal strategic education | "Build a business with MBA-level strategy" | Leadership + practical curriculum |
| **International Professional** | Global professional seeking internationally recognised qualifications | "Credentials that travel" | Triple university accreditation |

### 1.3 Content Gaps Identified (Recommend Adding)

- **Specific fee/pricing information** — currently only "Affordable Investment" mentioned
- **Eligibility criteria** — not detailed in document
- **Application process / timeline** — not provided
- **Specific module/course names** — only specialisation areas listed
- **Student success stories / testimonials** — to be created for social proof
- **FAQs** — to address common objections

> **Recommendation:** These gaps should be filled via the MySQL/Filament CMS backend for dynamic content management.

---

## 2. INFORMATION ARCHITECTURE & SECTION BREAKDOWN

### 2.1 Page Flow (Conversion-Optimised Scroll Sequence)

The page follows a **"Hook → Proof → Detail → Aspire → Act"** conversion funnel:

```
┌─────────────────────────────────────────────────┐
│  [S0] NAVIGATION BAR                            │  ← Sticky, transparent → solid on scroll
│       Logo | Programme | Specialisations |      │
│       Accreditation | FAQ | Apply Now (CTA)     │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S1] HERO SECTION                              │  ← HOOK: First 3 seconds
│       Headline + Sub-headline                   │
│       Quick Stats Bar (Duration, Mode, Degrees) │
│       Dual CTA: "Apply Now" + "Download         │
│       Brochure"                                 │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S2] TRUST BAR / ACCREDITATION STRIP           │  ← PROOF: Immediate credibility
│       Partner University Logos                   │
│       (GAU | RBS | UCA)                         │
│       "Awarded by internationally recognised    │
│       institutions"                             │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S3] PROGRAMME OVERVIEW                        │  ← DETAIL: What you get
│       "One Programme. Two Degrees."             │
│       Visual split: General MBA ←→ Specialised  │
│       MBA                                       │
│       Integrated pathway diagram                │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S4] WHY CHOOSE DUAL MBA                       │  ← DETAIL: Feature cards
│       6 key benefits as icon + title + desc     │
│       cards in a responsive grid                │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S5] SPECIALISATIONS GRID                      │  ← DETAIL: Pathways
│       Clickable specialisation cards            │
│       (AI, Finance, HR, Supply Chain,           │
│       Healthcare, IT, Project Mgmt, Analytics)  │
│       Each with icon + brief description        │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S6] WHY EMPLOYERS VALUE A DUAL MBA            │  ← ASPIRE: External validation
│       Employer-value checklist with visual       │
│       treatment                                 │
│       Stats/counter animation                   │
│       (e.g., "8 Key Skills Employers Seek")     │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S7] STUDENT SUCCESS STORIES / TESTIMONIALS    │  ← PROOF: Social proof
│       Carousel or grid of testimonial cards     │
│       Photo + Name + Role + Quote               │
│       Star ratings or outcome metrics           │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S8] HOW IT WORKS / APPLICATION PROCESS        │  ← ACT: Remove confusion
│       Step 1 → Step 2 → Step 3 → Step 4        │
│       Visual timeline / process flow            │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S9] FAQ ACCORDION                             │  ← ACT: Overcome objections
│       Collapsible Q&A panels                    │
│       Dynamic content from Filament CMS         │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S10] FINAL CTA / CONVERSION SECTION           │  ← ACT: Close
│        Strong headline + urgency element        │
│        "Apply Now" + "Book a Consultation"      │
│        + "Download Brochure"                    │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  [S11] FOOTER                                   │
│        Academy info, contact, social links,     │
│        legal, partner logos repeat              │
│                                                 │
└─────────────────────────────────────────────────┘
```

### 2.2 Section Detail Specifications

#### S0 — NAVIGATION BAR
- **Behaviour:** Transparent overlay on hero → solid background on scroll (glass-morphism effect)
- **Left:** Academy logo (Maverick Business Academy London)
- **Center/Right:** Programme | Specialisations | Accreditation | FAQ
- **Far Right:** Primary CTA button — "Apply Now"
- **Mobile:** Hamburger menu with slide-in drawer
- **Filament Integration:** Nav items can be managed as a `NavigationItem` resource

#### S1 — HERO SECTION
- **Layout:** Full-viewport height, split layout (60/40 or asymmetric)
- **Left Side:**
  - Pre-headline tag: `DUAL MBA PROGRAMME`
  - H1: *"Earn Two MBA Degrees. Expand Your Expertise. Accelerate Your Global Career."*
  - Sub-headline: *"One Programme. Two International MBA Qualifications. Unlimited Career Opportunities."*
  - Quick Stats Strip: `1 Year` | `100% Online` | `Weekend Classes` | `2 Degrees`
  - Dual CTA Buttons: `Apply Now` (primary) + `Download Brochure` (secondary/outline)
- **Right Side:** Hero image (professional in a modern learning environment / campus aesthetic)
- **Background:** Subtle gradient overlay or geometric pattern
- **Filament Integration:** Hero text fields editable via `HeroContent` Filament resource

#### S2 — TRUST BAR / ACCREDITATION STRIP
- **Layout:** Full-width horizontal strip, visually distinct background
- **Content:** Three partner logos in a row with "Awarded By" label above
  - Girne American University (GAU) logo
  - Rushford Business School (RBS) logo
  - University for the Creative Arts (UCA) logo
- **Style:** Logos presented at equal height, with subtle dividers, on a contrasting background strip
- **Animation:** Logos fade-in sequentially on scroll
- **Filament Integration:** `PartnerInstitution` resource with logo upload, name, URL

#### S3 — PROGRAMME OVERVIEW
- **Layout:** Centered section with a visual "pathway" diagram
- **Concept:** Two cards side by side connected by a visual bridge/arrow
  - **Card A:** "MBA (General)" — broad business leadership
  - **Card B:** "MBA (Specialisation)" — your chosen expertise
  - **Bridge Element:** "One Integrated Pathway" connecting both
- **Supporting Text:** Paragraph from document about the dual programme advantage
- **Visual:** Abstract pathway illustration or isometric diagram
- **Filament Integration:** `ProgrammeDetail` resource with rich text editor

#### S4 — WHY CHOOSE THE DUAL MBA
- **Layout:** 2x3 or 3x2 responsive card grid
- **Cards:** Each benefit from the "Why Choose" section:
  1. Leadership That Drives Results
  2. Two MBA Qualifications
  3. Designed for Working Professionals
  4. Industry-Relevant Curriculum
  5. Future-Focused Specialisations
  6. Global Career Opportunities
  7. Affordable Investment
- **Card Structure:** Icon (FontAwesome/Lucide) + Title + 2-line description
- **Animation:** Staggered reveal on scroll
- **Filament Integration:** `BenefitCard` repeater resource

#### S5 — SPECIALISATIONS GRID
- **Layout:** Responsive grid (4 columns desktop, 2 mobile)
- **Cards for each specialisation:**
  - Artificial Intelligence
  - Finance
  - Human Resource Management
  - Supply Chain Management
  - Project Management
  - Information Technology
  - Healthcare Management
  - Business Analytics
- **Card Structure:** Icon + Specialisation Name + Brief tagline
- **Interaction:** Hover effect with colour shift or card lift
- **Optional:** Click to expand with more detail (drawer/modal)
- **Filament Integration:** `Specialisation` resource with icon selector, title, description

#### S6 — WHY EMPLOYERS VALUE A DUAL MBA
- **Layout:** Split layout — Left: Illustration/Image | Right: Checklist
- **Checklist Items** (from document):
  - Broader business understanding
  - Advanced industry knowledge
  - Strong leadership capability
  - Analytical thinking
  - Strategic decision-making
  - Cross-functional management skills
  - Adaptability in changing industries
  - Commitment to professional development
- **Visual Treatment:** Checkmark icons, alternating subtle background on items
- **Counter Animation:** "8 Key Competencies Employers Seek" with animated number count-up
- **Filament Integration:** `EmployerValue` resource with repeater for checklist items

#### S7 — STUDENT SUCCESS STORIES / TESTIMONIALS
- **Layout:** Horizontal carousel (desktop) / Vertical stack (mobile)
- **Card Structure:**
  - Student photo (circular crop)
  - Full name
  - Current role/company
  - Quote (2-3 lines)
  - Programme completed (e.g., "Dual MBA — AI Specialisation")
  - Optional: Star rating or career outcome metric
- **Controls:** Prev/Next arrows + dot indicators + auto-play with pause on hover
- **Filament Integration:** `Testimonial` resource with image upload, rich text, categorisation

#### S8 — HOW IT WORKS / APPLICATION PROCESS
- **Layout:** Horizontal timeline (desktop) / Vertical steps (mobile)
- **Steps:**
  1. **Submit Application** — Complete the online application form
  2. **Review & Admission** — Our admissions team reviews your profile
  3. **Enrolment & Onboarding** — Secure your place and access learning materials
  4. **Begin Your Journey** — Start classes and work towards your Dual MBA
- **Visual:** Numbered circles connected by a line/path, with icon for each step
- **Animation:** Steps reveal sequentially as user scrolls
- **Filament Integration:** `ApplicationStep` resource with order, icon, title, description

#### S9 — FAQ ACCORDION
- **Layout:** Single column, centered, with expandable panels
- **Suggested FAQ Topics:**
  - What is the Dual MBA Programme?
  - Who is this programme designed for?
  - How long does the programme take to complete?
  - Can I study while working full-time?
  - What specialisations are available?
  - Are the degrees internationally recognised?
  - What are the fees and payment options?
  - Are scholarships available?
  - What are the eligibility requirements?
  - How do I apply?
- **Interaction:** Click to expand/collapse, only one open at a time
- **Animation:** Smooth height transition with rotate on chevron icon
- **Filament Integration:** `FAQ` resource with category, question, answer (rich text), sort order

#### S10 — FINAL CTA / CONVERSION SECTION
- **Layout:** Full-width banner with strong visual background (gradient or image overlay)
- **Content:**
  - Headline: *"Your Future Starts Here. Apply for the Dual MBA Programme Today."*
  - Supporting text: 1-2 lines reinforcing the value
  - Three CTA buttons:
    - `Apply Now` (primary, bold)
    - `Book a Free Consultation` (secondary)
    - `Download Brochure` (tertiary/text link)
- **Urgency Element:** Optional intake deadline or limited seats indicator
- **Filament Integration:** `CTASection` resource with headline, body, CTA links

#### S11 — FOOTER
- **Layout:** Multi-column footer
- **Columns:**
  - **About:** Academy description, logo
  - **Quick Links:** Programme, Specialisations, Apply, FAQ
  - **Contact:** Email, Phone, Address, Map link
  - **Social:** LinkedIn, Instagram, Facebook, Twitter/X, YouTube icons
- **Bottom Bar:** Copyright, Privacy Policy, Terms & Conditions
- **Partner Logos:** Repeat of GAU, RBS, UCA logos (smaller)
- **Filament Integration:** `FooterContent` resource, `SocialLink` resource

---

## 3. DESIGN SYSTEM

### 3.1 Design Philosophy

> **"Institutional Trust Meets Modern Ambition"**

The design language bridges the gap between traditional academic credibility and contemporary digital-first aesthetics. It should feel **authoritative but approachable**, **premium but not elitist**, and **professional but energising**.

### 3.2 Colour Palette

The palette below is a **proposed Modern Education system**. It is designed to be easily remapped to your existing Maverick brand guidelines once shared.

#### Primary Colours

| Token | Colour | Hex | Usage |
|-------|--------|-----|-------|
| `--color-primary` | Deep Navy | `#0B1D3A` | Primary text, navbar solid state, headings |
| `--color-primary-light` | Twilight Blue | `#1A3A5C` | Card backgrounds, hover states |
| `--color-accent` | Amber Gold | `#D4A843` | CTAs, highlights, active states, icons |
| `--color-accent-hover` | Warm Gold | `#C49A30` | CTA hover states |

#### Secondary Colours

| Token | Colour | Hex | Usage |
|-------|--------|-----|-------|
| `--color-secondary` | Slate Teal | `#2E6B6A` | Secondary buttons, links, tags |
| `--color-secondary-light` | Mist Teal | `#D0E8E7` | Alternating section backgrounds |

#### Neutral Palette

| Token | Colour | Hex | Usage |
|-------|--------|-----|-------|
| `--color-bg-primary` | Off-White | `#F8F6F2` | Page background |
| `--color-bg-secondary` | Warm Grey | `#EDEAE4` | Alternating section backgrounds |
| `--color-bg-dark` | Charcoal Navy | `#0D1B2A` | Dark sections (Final CTA, Footer) |
| `--color-text-primary` | Almost Black | `#1A1A2E` | Body text on light backgrounds |
| `--color-text-secondary` | Dark Grey | `#4A4A5A` | Secondary/descriptive text |
| `--color-text-inverse` | Pure White | `#FFFFFF` | Text on dark backgrounds |
| `--color-text-muted` | Medium Grey | `#7A7A8A` | Captions, metadata |
| `--color-border` | Light Grey | `#E0DDD7` | Borders, dividers |

#### Semantic Colours

| Token | Colour | Hex | Usage |
|-------|--------|-----|-------|
| `--color-success` | Forest Green | `#2D8B4E` | Checkmarks, success states |
| `--color-info` | Steel Blue | `#3B82B0` | Info badges, tooltips |

### 3.3 Typography

#### Font Stack

| Role | Font Family | Weight | Fallback | Source |
|------|-------------|--------|----------|--------|
| **Display / H1** | **Playfair Display** | 700 (Bold) | Georgia, serif | Google Fonts |
| **Headings / H2-H4** | **DM Sans** | 600 (Semi-Bold) | system-ui, sans-serif | Google Fonts |
| **Body** | **DM Sans** | 400 (Regular) | system-ui, sans-serif | Google Fonts |
| **Labels / Stats** | **DM Sans** | 500 (Medium) | system-ui, sans-serif | Google Fonts |
| **Accent / Pre-headings** | **DM Sans** | 700 (Bold), UPPERCASE, Letter-spaced | system-ui, sans-serif | Google Fonts |

#### Type Scale

| Element | Size (Desktop) | Size (Mobile) | Line Height | Letter Spacing | Weight |
|---------|----------------|---------------|-------------|----------------|--------|
| **H1 (Hero)** | 56px / 3.5rem | 36px / 2.25rem | 1.1 | -0.02em | 700 |
| **H2 (Section)** | 40px / 2.5rem | 28px / 1.75rem | 1.2 | -0.01em | 600 |
| **H3 (Card Title)** | 24px / 1.5rem | 20px / 1.25rem | 1.3 | 0 | 600 |
| **H4 (Sub-section)** | 20px / 1.25rem | 18px / 1.125rem | 1.4 | 0 | 600 |
| **Body Large** | 18px / 1.125rem | 16px / 1rem | 1.7 | 0.01em | 400 |
| **Body** | 16px / 1rem | 15px / 0.9375rem | 1.7 | 0.01em | 400 |
| **Small / Caption** | 14px / 0.875rem | 13px / 0.8125rem | 1.5 | 0.02em | 400 |
| **Label / Tag** | 12px / 0.75rem | 11px / 0.6875rem | 1.4 | 0.1em | 700 |
| **Stat Number** | 48px / 3rem | 36px / 2.25rem | 1.0 | -0.02em | 700 |

#### Typographic Principles

- **Hierarchy through contrast:** Display font (Playfair Display) is serif — used ONLY for H1 and major section headlines to create a premium academic feel. All other text uses DM Sans for clean readability.
- **Pre-heading tags:** Uppercase, small, letter-spaced DM Sans in `--color-accent` to label sections (e.g., `PROGRAMME OVERVIEW`, `SPECIALISATIONS`).
- **Maximum body text width:** 680px for optimal readability.
- **Generous line-height:** 1.7 for body text ensures breathing room.

### 3.4 Spacing System

Based on an 8px base grid:

| Token | Value | Usage |
|-------|-------|-------|
| `--space-xs` | 4px | Inline gaps, icon padding |
| `--space-sm` | 8px | Tight internal padding |
| `--space-md` | 16px | Card padding, element gaps |
| `--space-lg` | 24px | Section internal padding |
| `--space-xl` | 32px | Between content blocks |
| `--space-2xl` | 48px | Between sections (mobile) |
| `--space-3xl` | 64px | Between sections (desktop) |
| `--space-4xl` | 96px | Major section padding (desktop) |
| `--space-5xl` | 128px | Hero section vertical padding |

### 3.5 Elevation & Depth

| Level | Box Shadow | Usage |
|-------|-----------|-------|
| **Level 0** | None | Flat elements |
| **Level 1** | `0 1px 3px rgba(11,29,58,0.06)` | Subtle cards, inputs |
| **Level 2** | `0 4px 12px rgba(11,29,58,0.08)` | Cards at rest |
| **Level 3** | `0 8px 24px rgba(11,29,58,0.12)` | Cards on hover, dropdown |
| **Level 4** | `0 16px 48px rgba(11,29,58,0.16)` | Modals, overlays |
| **Glass** | `backdrop-filter: blur(16px); background: rgba(248,246,242,0.85)` | Navbar solid state |

### 3.6 Border Radius

| Token | Value | Usage |
|-------|-------|-------|
| `--radius-sm` | 6px | Buttons, inputs, tags |
| `--radius-md` | 12px | Cards, panels |
| `--radius-lg` | 20px | Large feature cards, images |
| `--radius-pill` | 9999px | Pill buttons, badges |
| `--radius-circle` | 50% | Avatars, circular icons |

---

## 4. ANIMATION LOGIC

### 4.1 Animation Framework Choice

**Primary:** GSAP (GreenSock) via CDN — industry-standard, performant, Blade-compatible
**Fallback:** CSS `@keyframes` + `IntersectionObserver` for lightweight scroll-reveal

### 4.2 Global Animation Tokens

```
Duration Scale:
  --duration-fast:    200ms   (hover, micro-interactions)
  --duration-base:    400ms   (scroll-reveal, transitions)
  --duration-slow:    600ms   (complex reveals, hero entrance)
  --duration-stagger: 100ms   (delay between sequential items)

Easing:
  --ease-out:     cubic-bezier(0.16, 1, 0.3, 1)    (primary exit easing)
  --ease-in-out:  cubic-bezier(0.65, 0, 0.35, 1)   (bidirectional)
  --ease-bounce:  cubic-bezier(0.34, 1.56, 0.64, 1) (playful, for stats)
```

### 4.3 Section-by-Section Animation Map

| Section | Animation | Trigger | Duration | Details |
|---------|-----------|---------|----------|---------|
| **S0 Navbar** | Background opacity transition | Scroll past hero | 300ms | Transparent → glass-morphism → solid. Logo and links fade slightly. |
| **S1 Hero** | Staggered entrance | Page load | 600ms + stagger | Pre-heading slides in (delay 0) → H1 fades up (delay 100ms) → Sub-heading (delay 200ms) → Stats bar (delay 300ms) → CTAs (delay 400ms). Right image scales from 0.95 → 1.0 with opacity. |
| **S2 Trust Bar** | Sequential logo fade-in | Scroll into view | 400ms + 150ms stagger | Each logo fades in with a slight upward slide, left to right. |
| **S3 Programme Overview** | Card slide-in from sides | Scroll 20% visible | 500ms | Left card slides from left, right card from right, bridge element fades in last. |
| **S4 Why Choose** | Staggered card reveal | Scroll 15% visible | 400ms + 100ms stagger | Cards fade up one by one in reading order (top-left → bottom-right). |
| **S5 Specialisations** | Grid cascade | Scroll 15% visible | 300ms + 80ms stagger | Cards appear in a cascade pattern (column by column or row by row). |
| **S6 Employer Value** | Counter animation + checklist reveal | Scroll 30% visible | 800ms (counter) + stagger (list) | Number counts up from 0 → 8. Checklist items reveal sequentially with checkmark "draw" animation. |
| **S7 Testimonials** | Carousel auto-play | Scroll into view | 500ms (transition) | Cards slide horizontally. Manual controls override auto-play. |
| **S8 Process Steps** | Timeline draw + step pop-in | Scroll 20% visible | 600ms + 200ms stagger | Connection line "draws" between steps. Each step circle pops in as line reaches it. |
| **S9 FAQ** | Smooth expand/collapse | User click | 300ms | Panel height animates. Chevron rotates 180°. Content fades in after height transition. |
| **S10 Final CTA** | Parallax background + text fade-up | Scroll into view | 500ms | Background image has subtle parallax. Text and buttons fade up with stagger. |

### 4.4 Micro-Interactions

| Element | Interaction | Animation |
|---------|------------|-----------|
| **Primary CTA Button** | Hover | Background brightens, subtle scale(1.02), shadow deepens |
| **Primary CTA Button** | Click | Scale(0.98) momentary press effect |
| **Secondary CTA Button** | Hover | Border color fills to solid background, text color inverts |
| **Cards** | Hover | translateY(-4px), shadow elevates to Level 3 |
| **Specialisation Cards** | Hover | Icon colour shifts to accent, subtle background tint |
| **Nav Links** | Hover | Underline slides in from left (pseudo-element width transition) |
| **Accordion Chevron** | Open/Close | Rotate 0° → 180° |
| **Logo (Trust Bar)** | Hover | Subtle opacity increase (0.7 → 1.0) if greyscale default |
| **Smooth Scroll** | Nav click | `scroll-behavior: smooth` with offset for sticky nav height |

### 4.5 Performance Guidelines

- **Animate only `transform` and `opacity`** — avoid animating layout properties (width, height, top, left)
- **Use `will-change` sparingly** — only on elements about to animate
- **`IntersectionObserver` threshold:** 0.15 for most sections (trigger when 15% visible)
- **Respect `prefers-reduced-motion`:** Disable all scroll-reveal and auto-play animations; keep only essential state transitions
- **GSAP ScrollTrigger `once: true`:** Animations fire only once per session (no replay on scroll back)
- **Lazy load images** below the fold using `loading="lazy"` or IntersectionObserver

---

## 5. COMPONENT LIBRARY

### 5.1 Core Components (Blade/Tailwind Compatible)

| Component | Variants | Tailwind Strategy |
|-----------|----------|-------------------|
| **Button** | Primary (filled), Secondary (outline), Ghost (text), Icon Button | Utility classes with `@apply` in component CSS |
| **Card** | Feature Card, Specialisation Card, Testimonial Card, Stat Card | Shared base + variant modifiers |
| **Badge / Tag** | Pre-heading tag, Status tag, Category tag | Pill-shaped, uppercase, letter-spaced |
| **Accordion** | Single item, Group (one-open-at-a-time) | Alpine.js `x-data` for state management |
| **Carousel** | Testimonial carousel with controls | GSAP or Swiper.js integration |
| **Timeline** | Horizontal (desktop), Vertical (mobile) | Flexbox with pseudo-element connectors |
| **Stat Counter** | Animated number with label | GSAP CountUp or custom JS |
| **Logo Strip** | Horizontal row of partner logos | Flexbox with equal-height constraint |
| **Nav Bar** | Transparent, Solid, Mobile Drawer | Alpine.js for toggle, CSS transitions |
| **Section Wrapper** | Standard, Dark, Accent Background | Consistent padding tokens, bg colour variants |
| **CTA Banner** | Full-width with background image/gradient | Overlay + text alignment utilities |

### 5.2 Component Anatomy — Primary Button Example

```
┌──────────────────────────────────┐
│  [Icon?]  BUTTON LABEL           │
└──────────────────────────────────┘

Padding:      12px 32px (py-3 px-8)
Font:         DM Sans, 600, 15px, uppercase, 0.05em tracking
Background:   var(--color-accent) → #D4A843
Text:         var(--color-bg-dark) → #0D1B2A (dark text on gold)
Radius:       var(--radius-sm) → 6px
Shadow:       Level 1 at rest, Level 2 on hover
Transition:   background 200ms ease, transform 200ms ease, box-shadow 200ms ease
Hover:        background → var(--color-accent-hover), translateY(-1px), shadow Level 2
Active:       scale(0.98), shadow Level 1
```

---

## 6. CTA STRATEGY

### 6.1 Multi-CTA Placement Map

| Location | CTA(s) | Type | Purpose |
|----------|--------|------|---------|
| **Navbar** | Apply Now | Primary Button (persistent) | Always-accessible conversion point |
| **Hero** | Apply Now + Download Brochure | Primary + Secondary | Dual intent capture (hot + warm leads) |
| **After Programme Overview (S3)** | Explore Specialisations | Text Link / Scroll CTA | Guide deeper into content |
| **After Specialisations (S5)** | Apply Now | Primary Button | Capture post-exploration intent |
| **After Testimonials (S7)** | Book a Free Consultation | Secondary Button | Lower-commitment alternative |
| **Final CTA Section (S10)** | Apply Now + Book Consultation + Download Brochure | All three | Maximum optionality at decision point |
| **Sticky Mobile CTA** | Apply Now | Floating bottom bar (mobile only) | Always-accessible on mobile |

### 6.2 CTA Hierarchy

1. **Apply Now** — Primary (Gold/Accent, filled, bold). Appears most frequently.
2. **Book a Free Consultation** — Secondary (Outline, teal/secondary colour). Personal touch alternative.
3. **Download Brochure** — Tertiary (Ghost/text link with icon). Lead magnet for research-phase visitors.

---

## 7. BACKEND-READY DATA ARCHITECTURE

### 7.1 Filament CMS Resource Model

The layout is designed so that **every content section maps to a Filament Resource**, enabling non-technical staff to update page content without code changes.

| Section | Filament Resource | Key Fields | Notes |
|---------|-------------------|------------|-------|
| Hero | `HeroContent` | headline, sub_headline, stats (JSON), cta_primary_text, cta_primary_url, cta_secondary_text, cta_secondary_url, hero_image | Single record (singleton) |
| Trust Bar | `PartnerInstitution` | name, logo (image), url, display_order | 3+ records, ordered |
| Programme Overview | `ProgrammeOverview` | title, body (rich text), card_general_title, card_general_desc, card_specialised_title, card_specialised_desc | Singleton |
| Benefits | `Benefit` | icon (string/enum), title, description, display_order | 6-8 records |
| Specialisations | `Specialisation` | icon, title, tagline, description (optional long), display_order, is_active | 8+ records |
| Employer Values | `EmployerValue` | icon, label, display_order | 8 records, repeater |
| Testimonials | `Testimonial` | student_name, photo (image), role, company, quote, programme, rating, is_featured | Multiple records |
| Process Steps | `ApplicationStep` | step_number, icon, title, description | 4 records |
| FAQs | `FAQ` | question, answer (rich text), category, display_order, is_active | Multiple, categorised |
| Final CTA | `CTAContent` | headline, body, cta_buttons (JSON array), background_image | Singleton |
| Footer | `FooterContent` | about_text, contact_email, contact_phone, address, social_links (JSON) | Singleton |
| SEO | `PageSEO` | meta_title, meta_description, og_image, canonical_url | Singleton per page |

### 7.2 MySQL Table Naming Convention

```
dual_mba_hero_contents
dual_mba_partner_institutions
dual_mba_benefits
dual_mba_specialisations
dual_mba_employer_values
dual_mba_testimonials
dual_mba_application_steps
dual_mba_faqs
dual_mba_cta_contents
dual_mba_footer_contents
dual_mba_page_seos
```

All tables include: `id`, `created_at`, `updated_at`, `is_active` (boolean), and follow Laravel migration conventions.

---

## 8. ASSET REQUIREMENTS

### 8.1 Images (Pexels CDN Placeholders)

| Slot | Description | Suggested Pexels Search | Dimensions | Format |
|------|-------------|-------------------------|------------|--------|
| Hero Image | Professional in modern learning environment, diverse, aspirational | "professional working laptop modern office" | 1200x800 | WebP/JPG |
| Programme Overview BG | Abstract geometric or campus/library setting | "modern university library" | 1920x600 | WebP/JPG |
| Employer Section | Business meeting, diverse team, boardroom | "diverse business team meeting" | 800x600 | WebP/JPG |
| Final CTA Background | Graduation, celebration, or city skyline | "graduation celebration university" | 1920x800 | WebP/JPG |
| Testimonial Photos (x6) | Professional headshots, diverse | "professional portrait" | 200x200 | WebP/JPG |
| Specialisation Icons | Custom icon set (FontAwesome/Lucide) | N/A — Icon font | SVG | SVG |

### 8.2 Partner Logos

| Logo | Source | Notes |
|------|--------|-------|
| Girne American University (GAU) | To be provided by client | Needed in SVG/PNG with transparent bg |
| Rushford Business School (RBS) | To be provided by client | Needed in SVG/PNG with transparent bg |
| University for the Creative Arts (UCA) | To be provided by client | Needed in SVG/PNG with transparent bg |
| Maverick Business Academy London | To be provided by client | Primary brand logo, header + footer |

### 8.3 Icon Library

**Recommendation:** Lucide Icons (open source, consistent, lightweight)
- Leadership: `crown`, `trophy`
- Education: `graduation-cap`, `book-open`
- Global: `globe`, `map-pin`
- Technology: `cpu`, `brain-circuit`
- Finance: `trending-up`, `bar-chart-3`
- Healthcare: `heart-pulse`, `stethoscope`
- HR: `users`, `user-check`
- Supply Chain: `truck`, `package`
- Project Management: `kanban`, `clipboard-check`
- Online Learning: `monitor`, `wifi`
- Clock/Duration: `clock`, `calendar`
- Checkmark: `check-circle`, `badge-check`

---

## SUMMARY & NEXT STEPS

### What This Blueprint Covers:
- Full content analysis with value proposition mapping
- 12-section page architecture optimised for conversion
- Complete design system (colours, typography, spacing, elevation)
- Detailed animation specifications per section
- Component library definitions
- Multi-CTA conversion strategy
- Backend-ready Filament/MySQL data model
- Asset requirements checklist

### Awaiting Your Input:
1. **Brand Guidelines** — Please share your Maverick Academy colour palette, logo files, and any typography preferences to replace the proposed system.
2. **Partner Logos** — GAU, RBS, UCA logo files needed.
3. **Content Gaps** — Fee details, eligibility criteria, and application process specifics for dynamic content.
4. **Approval** — Please review this Blueprint and confirm before I proceed to the **static design implementation phase**.

---

*Blueprint prepared for Maverick Business Academy London — Dual MBA Programme Landing Page*
*Architecture Version: 1.0*
