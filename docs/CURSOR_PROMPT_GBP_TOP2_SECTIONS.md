# Cursor AI Prompt — Global Bachelor's Pathway: Top 2 Sections Update

> **Copy everything below the line into Cursor AI.**

---

## TASK

Modify the **top 2 sections** of the Global Bachelor's Pathway page to match the updated SOP. Do NOT touch any sections below these two.

### Files to Modify
1. `resources/views/pages/global-bachelors-pathway.blade.php`
2. `public/css/pages/global-bachelors-pathway.css`

### What Changed in the SOP

**BEFORE (current blade):**
- Hero heading = "Your Structured Route to a Globally Recognised European Bachelor's Degree"
- Section 2 = "What is the Maverick Bachelor's Pathway Programme?"

**AFTER (updated SOP):**
- Hero heading = "Global Bachelor's Pathway Programme" (short, punchy)
- Hero sub-heading = A unique creative sub-heading (you create this)
- **NEW Section 1** = "Your Structured Route to a Globally Recognised European Bachelor's Degree" (the old hero heading moves here as its own section)
- Section 2 = "What is the Maverick Bachelor's Pathway Programme?" (unchanged)

---

## RULES

1. **BEM methodology only** — all new CSS classes use `.gbp-block__element--modifier` pattern
2. **No Tailwind** — custom CSS only
3. **Use existing design tokens** — `--color-mba-blue`, `--color-mba-dark-blue`, `--color-mba-red`, etc.
4. **Use GSAP + AnimationUtils** for animations
5. **Do NOT modify sections 3-13** — only touch Hero and the new Section 1
6. **Keep all existing data objects** — only modify `$hero` and add a new `$intro` object

---

## CHANGE 1: MODIFY HERO DATA

In the `@php` block, modify the `$hero` object:

### Current `$hero`:
```php
$hero = (object)[
    'tag' => 'GLOBAL BACHELOR\'S PATHWAY',
    'heading' => 'Your Structured Route to a Globally Recognised',
    'heading_italic' => 'European Bachelor\'s Degree',
    'description' => 'Begin your Bachelor\'s Degree Pathway in UAE...',
    'sub_description' => 'Designed for students and parents...',
    'background_image' => asset('...'),
    'highlights' => collect([...]),
];
```

### New `$hero`:
```php
$hero = (object)[
    'tag' => 'GLOBAL BACHELOR\'S PATHWAY',
    'heading' => 'Global Bachelor\'s',
    'heading_italic' => 'Pathway Programme',
    'sub_heading' => 'Your gateway to a globally recognised European Bachelor\'s degree — structured pathways, flexible learning, and international progression through Maverick Business Academy London.',
    'background_image' => asset('https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600'),
    'highlights' => collect([
        (object)['label' => 'Study Route', 'value' => 'UAE / Hybrid / Online + European University Progression'],
        (object)['label' => 'Destinations', 'value' => 'Hungary · Romania · Moldova'],
        (object)['label' => 'Focus', 'value' => 'International Pathways · European Degree · Credit Transfer'],
    ]),
];
```

**Key changes:**
- `heading` → "Global Bachelor's"
- `heading_italic` → "Pathway Programme"
- `description` and `sub_description` → replaced with single `sub_heading`
- `highlights` → keep but simplify slightly

---

## CHANGE 2: ADD NEW `$intro` DATA OBJECT

Add this **after** the `$hero` object in the `@php` block:

```php
$intro = (object)[
    'tag' => 'YOUR PATHWAY',
    'heading_line1' => 'Your Structured Route to a',
    'heading_line2' => 'Globally Recognised European',
    'heading_italic' => 'Bachelor\'s Degree',
    'paragraphs' => [
        'Begin your Bachelor\'s Degree Pathway in UAE with Maverick Business Academy London and progress towards an internationally recognised European Bachelor\'s degree through our partner university pathways in Hungary, Romania, and Moldova.',
        'Designed for students and parents seeking a smarter, affordable, and globally focused study route, the Maverick Bachelor\'s Global Pathway helps learners begin their academic journey with structured support and progress confidently towards international university completion, leading to an Affordable Bachelor\'s Degree in Europe.',
    ],
    'highlights' => collect([
        (object)['icon' => 'globe', 'label' => 'International Pathways', 'value' => 'Study in UAE, progress to Europe'],
        (object)['icon' => 'award', 'label' => 'Recognised Degree', 'value' => 'Globally accepted European qualification'],
        (object)['icon' => 'credit-card', 'label' => 'Cost Effective', 'value' => 'Affordable alternative to full overseas study'],
        (object)['icon' => 'users', 'label' => 'Full Support', 'value' => 'Visa guidance, career counselling, academic mentoring'],
    ]),
];
```

---

## CHANGE 3: MODIFY HERO HTML

Replace the cinematic hero content section. The hero should be **shorter and punchier** now — just the programme name + sub-heading + highlights + CTAs.

### New Hero Content:
```html
{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic Design)
═══════════════════════════════════════════ --}}
<section class="cinematic-hero" aria-label="Global Bachelor's Pathway Hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
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
    <div class="cinematic-hero__content">
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $hero->tag }}
        </span>
        <h1 class="cinematic-hero__title">
            {{ $hero->heading }}<br>
            <em>{{ $hero->heading_italic }}</em>
        </h1>
        <p class="cinematic-hero__description">{{ $hero->sub_heading }}</p>

        <div class="gbp-hero__highlights">
            @foreach($hero->highlights as $h)
            <div class="gbp-highlight">
                <span class="gbp-highlight__label">{{ $h->label }}</span>
                <span class="gbp-highlight__value">{{ $h->value }}</span>
            </div>
            @endforeach
        </div>

        <div class="gbp-hero__ctas">
            <a href="#enquire" class="btn btn--primary" data-testid="hero-cta-enquire">Enquire Now</a>
            <a href="#advisor" class="btn btn--secondary" data-testid="hero-cta-advisor">Speak to an Advisor</a>
        </div>
    </div>
</section>
```

---

## CHANGE 4: ADD NEW SECTION 1 (INTRO)

Insert this **immediately after** the cinematic hero section, **before** the Overview section (Section 2).

### Design Spec:
- **Class:** `gbp-intro`
- **Background:** Dark navy (`var(--color-mba-dark-blue)`)
- **Layout:** Centered content, max-width 900px
- **Heading:** Large section title with text-reveal animation
- **Paragraphs:** Two descriptive paragraphs with fade-up
- **Highlights:** Four icon cards in a row (2x2 on mobile)
- **Animation:** Text-reveal on heading, staggered fade-up on paragraphs and cards

### HTML:
```html
{{-- ═══════════════════════════════════════════
     SECTION 1: YOUR STRUCTURED ROUTE
     Bold statement band — Dark background
═══════════════════════════════════════════ --}}
<section class="gbp-intro" data-testid="gbp-intro">
    <div class="container">
        <div class="gbp-intro__content">
            <span class="gbp-intro__label">
                <span class="gbp-intro__label-line"></span>
                {{ $intro->tag }}
            </span>
            <h2 class="gbp-intro__heading">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $intro->heading_line1 }}</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $intro->heading_line2 }}</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $intro->heading_italic }}</em></span></span>
            </h2>
            @foreach($intro->paragraphs as $paragraph)
            <p class="gbp-intro__paragraph fade-up">{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="gbp-intro__highlights">
            @foreach($intro->highlights as $highlight)
            <div class="gbp-intro-card fade-up">
                <div class="gbp-intro-card__icon">
                    <span data-lucide="{{ $highlight->icon }}"></span>
                </div>
                <div class="gbp-intro-card__content">
                    <span class="gbp-intro-card__label">{{ $highlight->label }}</span>
                    <span class="gbp-intro-card__value">{{ $highlight->value }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
```

---

## CHANGE 5: ADD CSS FOR NEW SECTION

Add these styles to `global-bachelors-pathway.css`:

```css
/* ============================================================
   SECTION 1: INTRO — Bold Statement Band
   Dark background, centered editorial content
   ============================================================ */
.gbp-intro {
    padding: clamp(80px, 12vw, 160px) 0;
    background: var(--g-navy-deep);
    color: #fff;
    position: relative;
    overflow: hidden;
}

/* Subtle radial gradient accent */
.gbp-intro::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(
            800px 400px at 50% 0%,
            rgba(178, 2, 2, 0.15),
            transparent 60%
        ),
        radial-gradient(
            600px 300px at 80% 100%,
            rgba(15, 41, 131, 0.3),
            transparent 60%
        );
    pointer-events: none;
}

/* Noise texture overlay */
.gbp-intro::after {
    content: "";
    position: absolute;
    inset: 0;
    opacity: 0.03;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 200px;
    pointer-events: none;
}

.gbp-intro > .container {
    position: relative;
    z-index: 1;
    max-width: 1100px;
}

/* ── Label ── */
.gbp-intro__label {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 32px;
}

.gbp-intro__label-line {
    display: block;
    width: 44px;
    height: 2px;
    background: var(--g-red);
}

/* ── Heading ── */
.gbp-intro__heading {
    font-family: var(--font-display);
    font-size: clamp(36px, 5.5vw, 72px);
    font-weight: 500;
    line-height: 1.08;
    letter-spacing: -0.03em;
    color: #fff;
    margin: 0 0 40px;
    max-width: 16ch;
}

.gbp-intro__heading em {
    font-style: italic;
    color: var(--g-red);
    font-weight: 400;
}

/* ── Paragraphs ── */
.gbp-intro__paragraph {
    font-family: var(--font-body);
    font-size: clamp(16px, 1.2vw, 19px);
    line-height: 1.75;
    color: rgba(255, 255, 255, 0.82);
    margin: 0 0 20px;
    max-width: 72ch;
}

.gbp-intro__paragraph:last-of-type {
    color: rgba(255, 255, 255, 0.65);
}

/* ── Highlights Grid ── */
.gbp-intro__highlights {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-top: 56px;
    padding-top: 48px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

/* ── Highlight Card ── */
.gbp-intro-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 24px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    transition:
        transform 0.5s var(--g-ease),
        border-color 0.4s ease,
        background 0.4s ease;
}

.gbp-intro-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.07);
}

.gbp-intro-card__icon {
    width: 48px;
    height: 48px;
    background: rgba(178, 2, 2, 0.2);
    border-radius: 12px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    transition: background 0.4s ease;
}

.gbp-intro-card:hover .gbp-intro-card__icon {
    background: var(--g-red);
}

.gbp-intro-card__icon [data-lucide] {
    width: 22px;
    height: 22px;
    color: var(--g-red);
    transition: color 0.4s ease;
}

.gbp-intro-card:hover .gbp-intro-card__icon [data-lucide] {
    color: #fff;
}

.gbp-intro-card__content {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.gbp-intro-card__label {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 500;
    color: #fff;
    letter-spacing: -0.005em;
}

.gbp-intro-card__value {
    font-family: var(--font-body);
    font-size: 13px;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.6);
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .gbp-intro__highlights {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .gbp-intro {
        padding: clamp(64px, 10vw, 120px) 0;
    }
    .gbp-intro__heading {
        max-width: 100%;
    }
    .gbp-intro__highlights {
        grid-template-columns: 1fr;
        gap: 12px;
        margin-top: 40px;
        padding-top: 36px;
    }
    .gbp-intro-card {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .gbp-intro {
        padding: 56px 0;
    }
    .gbp-intro-card {
        gap: 12px;
        padding: 16px;
    }
    .gbp-intro-card__icon {
        width: 40px;
        height: 40px;
    }
}
```

---

## CHANGE 6: ADD ANIMATIONS

In the `@push('scripts')` block, add these animation calls:

```javascript
// ── Section 1: Intro ──
AnimationUtils.textReveal('.gbp-intro .text-reveal-inner', { stagger: 0.15 });
AnimationUtils.fadeUp('.gbp-intro .fade-up', { stagger: 0.12, y: 25 });
AnimationUtils.cards('.gbp-intro-card', { stagger: 0.1, y: 30 });
```

---

## CHANGE 7: UPDATE OVERVIEW SECTION SEQUENCE

The Overview section now has `data-testid="gbp-overview"` — no changes needed to its content, but add a comment to clarify it's now Section 2:

```html
{{-- ═══════════════════════════════════════════
     SECTION 2: WHAT IS THE PATHWAY PROGRAMME
═══════════════════════════════════════════ --}}
```

---

## VISUAL RESULT

### Before:
```
┌─────────────────────────────────────┐
│  CINEMATIC HERO                     │
│  "Your Structured Route to a        │
│   Globally Recognised European      │
│   Bachelor's Degree"                │
│  [Long description paragraphs]      │
│  [Highlights] [CTAs]                │
├─────────────────────────────────────┤
│  OVERVIEW SECTION                   │
│  "What is the Pathway Programme?"   │
└─────────────────────────────────────┘
```

### After:
```
┌─────────────────────────────────────┐
│  CINEMATIC HERO (shorter)           │
│  "Global Bachelor's                 │
│   Pathway Programme"                │
│  [Unique sub-heading]               │
│  [Highlights] [CTAs]                │
├─────────────────────────────────────┤
│  NEW: INTRO SECTION (dark)          │
│  "Your Structured Route to a        │
│   Globally Recognised European      │
│   Bachelor's Degree"                │
│  [Two descriptive paragraphs]       │
│  [4 highlight cards in a row]       │
├─────────────────────────────────────┤
│  OVERVIEW SECTION                   │
│  "What is the Pathway Programme?"   │
└─────────────────────────────────────┘
```

---

## CHECKLIST

- [ ] `$hero` object updated with new heading + sub_heading
- [ ] `$intro` object added with heading lines, paragraphs, highlights
- [ ] Hero HTML uses new shorter heading
- [ ] New Section 1 HTML inserted after Hero
- [ ] `.gbp-intro` CSS added with dark background, gradient, noise texture
- [ ] `.gbp-intro-card` CSS added with hover effects
- [ ] Responsive styles at 1024px, 768px, 480px
- [ ] GSAP animations added for text-reveal, fade-up, cards
- [ ] Sections 3-13 untouched
- [ ] BEM naming throughout
- [ ] No Tailwind classes
- [ ] Lucide icons used for highlight cards

---

## DO NOT

- ❌ Do NOT modify sections 3-13
- ❌ Do NOT change the Overview section content
- ❌ Do NOT remove the cinematic hero component import
- ❌ Do NOT use Tailwind classes
- ❌ Do NOT merge any git branches
- ❌ Do NOT modify any other files
