# SOP Diff Analysis — Global Bachelor's Pathway (Top 2 Sections)

## Current State vs Updated SOP

### HERO SECTION
| Field | Current (Blade) | Updated (SOP PDF) |
|-------|-----------------|-------------------|
| Heading | "Your Structured Route to a Globally Recognised European Bachelor's Degree" | **"Global Bachelor's Pathway Programme"** |
| Sub-heading | (none — this text IS the heading) | **"Create a unique short sub-heading related"** |
| Tag/Eyebrow | "GLOBAL BACHELOR'S PATHWAY" | Keep as-is |

### NEW SECTION 1 (doesn't exist yet)
| Field | Current (Blade) | Updated (SOP PDF) |
|-------|-----------------|-------------------|
| Title | (this IS the hero heading currently) | **"Your Structured Route to a Globally Recognised European Bachelor's Degree"** |
| Position | N/A | **Immediately after Hero, before "What is the Pathway Programme?"** |

### SECTION 2 (Current "Overview" — becomes Section 2)
No changes needed — stays as "What is the Maverick Bachelor's Pathway Programme?"

---

## Summary of Required Changes

1. **Hero**: Change heading from long sentence to short punchy "Global Bachelor's Pathway Programme" + add a creative sub-heading
2. **New Section 1**: Create "Your Structured Route to a Globally Recognised European Bachelor's Degree" — this is a brand new introductory/overview section between Hero and Overview

---

## Design Plan for New Section 1

### Purpose
This is a **cinematic intro/manifesto section** — it sets the tone for the entire page. It should feel like a bold editorial statement that introduces the value proposition before diving into details.

### Content (extracted from existing hero description + SOP intent)
The existing hero has two paragraphs that describe the programme:
1. "Begin your Bachelor's Degree Pathway in UAE with Maverick Business Academy London and progress towards an internationally recognised European Bachelor's degree through our partner university pathways in Hungary, Romania, and Moldova."
2. "Designed for students and parents seeking a smarter, affordable, and globally focused study route, the Maverick Bachelor's Global Pathway helps learners begin their academic journey with structured support and progress confidently towards international university completion, leading to an Affordable Bachelor's Degree in Europe."

Plus 3 highlight badges: Study Route, Destinations, Focus.

### Design Approach
- **Background:** Dark (`var(--color-mba-dark-blue)`) — creates contrast with the cinematic hero above and the light "Overview" section below
- **Layout:** Full-width editorial statement with large typography
- **Heading:** "Your Structured Route to a Globally Recognised" / `<em>European Bachelor's Degree</em>` — using the design system's section-title styling
- **Content:** The two descriptive paragraphs + highlight badges
- **Animation:** Text-reveal on heading, fade-up on paragraphs and badges

### Alternative Design Approaches (pick one)

**Option A: Bold Statement Band**
- Dark background with subtle gradient
- Large heading centered
- Two paragraphs below
- Highlight badges in a row at bottom
- Clean, editorial feel

**Option B: Split with Stats**
- Dark background
- Left: heading + paragraphs
- Right: Key stats/metrics (same stats from Overview section: ~6 Mo Level 4, ~6 Mo Level 5, etc.)
- More informative, data-driven

**Option C: Cinematic Reveal**
- Dark background with noise texture
- Very large heading that takes up most of the viewport
- Subtle fade-in paragraphs below
- Minimalist, high-impact

---

## Files to Modify

1. `resources/views/pages/global-bachelors-pathway.blade.php`
   - Modify `$hero` data object (change heading, add sub_heading)
   - Add new `$section1` data object
   - Modify Hero HTML section
   - Add new Section 1 HTML after Hero

2. `public/css/pages/global-bachelors-pathway.css`
   - Add new `.gbp-intro` section styles (or whatever BEM class is chosen)
   - Modify hero styles if needed for new heading structure

---

## Recommended Design: Option A (Bold Statement Band)

### Rationale
- Cleanest implementation
- Creates strong visual hierarchy: Hero → Bold Statement → Details
- Matches the editorial tone of the SOP
- Easy to animate with existing AnimationUtils
