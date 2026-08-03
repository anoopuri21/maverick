# Edutainment Page — Implementation Plan

## 📋 SOP Document Analysis

**Source:** `docs/Edutainment (1).docx`
**URL:** `/educational-tours-edutainment/`
**SEO Title:** Educational Tours for Students | Maverick Edutainment UAE
**H1:** Maverick Edutainment: Educational Tours That Bring Learning to Life

---

## 🎯 SOP Section Sequence (MUST FOLLOW EXACTLY)

| # | Section | Content Summary |
|---|---------|-----------------|
| 1 | **Hero** | H1 + Subheading + Description + 3 CTAs |
| 2 | **What Is Edutainment?** | Definition + list of activities |
| 3 | **Learning Beyond the Classroom** | Benefits list (8 items) |
| 4 | **Who Are Our Educational Tours Designed For?** | 6 audience segments |
| 5 | **Our Edutainment Programmes** | UAE Tours + International Tours + China Tour |
| 6 | **Educational Tour Themes** | 11 theme categories |
| 7 | **What Students Can Experience** | 4 experience categories (Academic, Professional, Cultural, Recreational) |
| 8 | **Why Choose Maverick Edutainment?** | 8 value propositions |
| 9 | **What Can Be Included in an Edutainment Package?** | Package inclusions list |
| 10 | **Educational Tours for Schools and Institutions** | 12 institution types |
| 11 | **Frequently Asked Questions** | 16+ FAQs |
| 12 | **Final CTA** | 3 CTAs + closing statement |

---

## 🎨 Design System Reference

### Colors (from main.css)
```css
--color-mba-blue: #0f2983;
--color-mba-dark-blue: #071444;
--color-mba-red: #b20202;
--color-warm-white: #f5f0eb;
--color-white: #ffffff;
```

### Typography
```css
--font-display: "PP Neue Montreal", sans-serif;
--font-body: "Poppins", sans-serif;
--fs-hero: clamp(42px, 8vw, 120px);
--fs-section-title: clamp(36px, 5vw, 72px);
--fs-body: clamp(15px, 1.2vw, 18px);
```

### Spacing
```css
--section-padding: clamp(80px, 10vh, 140px);
--container-padding: clamp(24px, 5vw, 80px);
--max-width: 1400px;
--navbar-height: 80px;
```

---

## 🎬 UI/UX Best Practices for This Page

### 1. Cinematic Scroll Storytelling
- **Parallax layers** on hero and key sections
- **Text reveal animations** (y: 110% → 0%)
- **Fade-up** for content blocks
- **Staggered reveals** for cards and lists
- **Horizontal scroll** for themes/experiences

### 2. Visual Hierarchy
- **Full-viewport hero** with background image/video
- **Section labels** (uppercase, red accent)
- **Large headings** with italic accents
- **Card-based layouts** for scanability

### 3. Engagement Patterns
- **Counter animations** for statistics
- **Accordion FAQ** with smooth expand
- **Image galleries** with lightbox
- **Testimonial carousel** for social proof

### 4. Performance
- **Lazy loading** for images below fold
- **IntersectionObserver** for scroll animations
- **Reduced motion** support
- **Mobile-first** responsive design

---

## 🏗️ File Structure

### New Files to Create

```
resources/views/pages/edutainment.blade.php          # Main page
resources/views/sections/edutainment/                 # Section partials
├── hero.blade.php
├── what-is.blade.php
├── learning-beyond.blade.php
├── who-for.blade.php
├── programmes.blade.php
├── themes.blade.php
├── experiences.blade.php
├── why-choose.blade.php
├── packages.blade.php
├── institutions.blade.php
├── faq.blade.php
└── final-cta.blade.php
public/css/pages/edutainment.css                      # Page styles
public/assets/js/pages/edutainment.js                 # Animations
```

### Files to Modify

```
routes/web.php                                        # Add route
resources/views/layouts/app.blade.php                 # Add CSS/JS loading
```

---

## 📐 Section-by-Section Design Plan

### S1: HERO — Cinematic Full-Viewport

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  [Background: Slow Ken Burns image of students traveling]   │
│  [Layer 1: Gradient overlay with noise texture]             │
│  [Layer 2: Floating geometric shapes (parallax)]            │
│  [Layer 3: Content]                                         │
│                                                             │
│     ┌─────────────────────────────────────────┐             │
│     │  EDUTAINMENT                            │  ← Tag     │
│     │                                         │             │
│     │  Maverick Edutainment:                  │             │
│     │  Educational Tours That                 │  ← H1      │
│     │  Bring Learning to Life                 │             │
│     │                                         │             │
│     │  Explore the World. Experience          │  ← H2     │
│     │  New Cultures. Learn Beyond             │             │
│     │  the Classroom.                         │             │
│     │                                         │             │
│     │  [Plan an Educational Tour]             │  ← CTAs   │
│     │  [Request a Custom Itinerary]           │             │
│     │  [Speak to Our Team]                    │             │
│     └─────────────────────────────────────────┘             │
│                                                             │
│  [Corner brackets] [Particles] [Scanline]                   │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Ken Burns effect on background
- Noise texture overlay
- Floating particles
- Scanline sweep
- Corner brackets
- Staggered text reveal
- CTA fade-up

**Reference:** Our Story hero section

---

### S2: WHAT IS EDUTAINMENT?

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  WHAT IS EDUTAINMENT?                                       │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  Edutainment is the combination of EDUCATION and            │
│  ENTERTAINMENT.                                             │
│                                                             │
│  At Maverick, Edutainment means creating carefully          │
│  planned educational journeys...                            │
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  A Maverick Edutainment programme may combine:          ││
│  │                                                         ││
│  │  ✓ Educational institution visits                       ││
│  │  ✓ University and campus experiences                    ││
│  │  ✓ Business and industry exposure                       ││
│  │  ✓ Cultural and historical exploration                  ││
│  │  ✓ Innovation and technology visits                     ││
│  │  ✓ Interactive workshops                                ││
│  │  ✓ Leadership and team-building activities              ││
│  │  ✓ Language and cultural immersion                      ││
│  │  ✓ Recreational experiences                             ││
│  │  ✓ Guided sightseeing                                   ││
│  │  ✓ Reflection and knowledge-sharing sessions            ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  "Learning becomes more memorable when students             │
│   experience it for themselves."                            │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Section label fade-up
- Heading text reveal
- Description fade-up
- Checklist items stagger reveal
- Quote fade-up with accent border

---

### S3: LEARNING BEYOND THE CLASSROOM

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  LEARNING BEYOND THE CLASSROOM                              │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  Some lessons are better understood when they               │
│  are experienced.                                           │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│  │ 🌍       │ │ 🎓       │ │ 💼       │ │ 🤝       │      │
│  │ Greater  │ │ Wider    │ │ Improved │ │ Stronger │      │
│  │ cultural │ │ global   │ │ confidence│ │ comms    │      │
│  │ awareness│ │ exposure │ │ & indep. │ │ skills   │      │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│  │ 📚       │ │ 🏭       │ │ 👥       │ │ ⭐       │      │
│  │ New acad │ │ Better   │ │ More     │ │ Memorable│      │
│  │ interests│ │ industry │ │ meaning- │ │ learning │      │
│  │          │ │ understand│ │ ful relat│ │ exp.     │      │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Section label + heading reveal
- Cards stagger fade-up (8 cards)
- Counter animation on hover

---

### S4: WHO ARE OUR TOURS DESIGNED FOR?

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  WHO ARE OUR EDUCATIONAL TOURS DESIGNED FOR?                │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  🎒 School Students                                     ││
│  │  Age-appropriate local and international educational    ││
│  │  tours combining discovery, cultural learning...        ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  🎓 College and University Students                     ││
│  │  Academic study tours that may include university       ││
│  │  visits, industry exposure, cultural experiences...     ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  [... 4 more cards ...]                                     │
│                                                             │
│  [Discuss Your Student Group]                               │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Cards stagger reveal (6 cards)
- Icon scale-in
- Hover lift effect

---

### S5: OUR EDUTAINMENT PROGRAMMES

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  OUR EDUTAINMENT PROGRAMMES                                 │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  [Image: UAE Skyline]                                   ││
│  │                                                         ││
│  │  UAE Educational Tours                                  ││
│  │  for School Students                                    ││
│  │                                                         ││
│  │  Help students discover the UAE through a carefully     ││
│  │  planned combination of education, culture...           ││
│  │                                                         ││
│  │  • Emirati heritage and traditions                      ││
│  │  • Science and technology                               ││
│  │  • Sustainability and environmental awareness           ││
│  │  • Space exploration                                    ││
│  │                                                         ││
│  │  [Plan a UAE Student Tour]                              ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  [Image: International Students]                        ││
│  │                                                         ││
│  │  International Study Tours                              ││
│  │                                                         ││
│  │  Take learning beyond national borders through a        ││
│  │  structured international educational journey...        ││
│  │                                                         ││
│  │  [Explore International Study Tours]                    ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  [Image: China]                                         ││
│  │                                                         ││
│  │  China Educational and Business Study Tour              ││
│  │                                                         ││
│  │  Discover one of the world's most influential centres   ││
│  │  for business, technology, manufacturing...             ││
│  │                                                         ││
│  │  • University Exposure                                  ││
│  │  • Business and Industry Visits                         ││
│  │  • Innovation and Entrepreneurship                      ││
│  │  • Cultural Immersion                                   ││
│  │                                                         ││
│  │  [Request a China Study Tour Itinerary]                 ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Cards slide-in from alternating sides
- Image parallax
- List items stagger reveal

---

### S6: EDUCATIONAL TOUR THEMES

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  EDUCATIONAL TOUR THEMES                                    │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  Every group has different learning goals. Maverick can     │
│  build the programme around one theme or combine several.   │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│  │ 💼       │ │ 🤖       │ │ 🌱       │ │ 🏛️       │      │
│  │ Business │ │ AI &     │ │ Sustain- │ │ Culture  │      │
│  │ & Entre- │ │ Technol- │ │ ability &│ │ & Heri-  │      │
│  │ preneursh│ │ ogy      │ │ Environ. │ │ tage     │      │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│  │ 🎯       │ │ 🔬       │ │ 🏨       │ │ 💰       │      │
│  │ Leader-  │ │ Science  │ │ Hospital-│ │ Finance  │      │
│  │ ship &   │ │ Engineer-│ │ ity &    │ │ & Intl.  │      │
│  │ Dev.     │ │ ing & Inn│ │ Tourism  │ │ Business │      │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐                   │
│  │ 🗣️       │ │ 🎓       │ │ 🔍       │                   │
│  │ Language │ │ University│ │ Research │                   │
│  │ & Culture│ │ & Career │ │ & Field  │                   │
│  │ Immers.  │ │ Explor.  │ │ Studies  │                   │
│  └──────────┘ └──────────┘ └──────────┘                   │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Horizontal scroll carousel (desktop)
- Staggered card reveal
- Icon scale-in

---

### S7: WHAT STUDENTS CAN EXPERIENCE

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  WHAT STUDENTS CAN EXPERIENCE                               │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  ┌───────────────────────┐ ┌───────────────────────┐       │
│  │  📚 Academic          │ │  💼 Professional       │       │
│  │  Experiences          │ │  Experiences           │       │
│  │                       │ │                        │       │
│  │  • University tours   │ │  • Company visits      │       │
│  │  • Guest lectures     │ │  • Industry present.   │       │
│  │  • Faculty interact.  │ │  • Entrepreneur meet   │       │
│  │  • Research exposure  │ │  • Startup visits      │       │
│  └───────────────────────┘ └───────────────────────┘       │
│                                                             │
│  ┌───────────────────────┐ ┌───────────────────────┐       │
│  │  🏛️ Cultural           │ │  🎢 Recreational      │       │
│  │  Experiences          │ │  Experiences           │       │
│  │                       │ │                        │       │
│  │  • Historical sites   │ │  • Theme parks         │       │
│  │  • Museums            │ │  • Adventure activities│       │
│  │  • Traditional arts   │ │  • City tours          │       │
│  │  • Local cuisine      │ │  • Team building       │       │
│  └───────────────────────┘ └───────────────────────┘       │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Cards stagger reveal (4 categories)
- List items slide-in

---

### S8: WHY CHOOSE MAVERICK EDUTAINMENT?

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  WHY CHOOSE MAVERICK EDUTAINMENT?                           │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  [Image: Students exploring]                                │
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  ✓ Education-Led Programme Design                       ││
│  │  ✓ Customised Itineraries                               ││
│  │  ✓ Local and International Destinations                 ││
│  │  ✓ Academic and Cultural Balance                        ││
│  │  ✓ Suitable for Different Age Groups                    ││
│  │  ✓ Support from Planning to Completion                  ││
│  │  ✓ Group Learning and Interaction                       ││
│  │  ✓ Meaningful Global Exposure                           ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Image parallax
- Checklist items stagger reveal
- Counter animation (if stats added)

---

### S9: WHAT CAN BE INCLUDED IN A PACKAGE?

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  WHAT CAN BE INCLUDED IN AN EDUTAINMENT PACKAGE?            │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  Package inclusions depend on the destination, group        │
│  requirements and selected itinerary.                       │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│  │ ✓ Edu.  │ │ ✓ Acad.  │ │ ✓ Business│ │ ✓ Work- │      │
│  │ itinerar│ │ & instit.│ │ or indust.│ │ shops &  │      │
│  │ y plan. │ │ visits   │ │ visits    │ │ learning │      │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │
│                                                             │
│  [... more items ...]                                       │
│                                                             │
│  [Request Package Details]                                  │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Grid stagger reveal
- Hover effects

---

### S10: EDUCATIONAL TOURS FOR SCHOOLS AND INSTITUTIONS

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  EDUCATIONAL TOURS FOR SCHOOLS AND INSTITUTIONS             │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  Maverick works with educational organisations to create    │
│  group experiences aligned with their requirements.         │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│  │ 🏫       │ │ 🎓       │ │ 🔄       │ │ 🌍       │      │
│  │ School   │ │ University│ │ Student  │ │ Intl.    │      │
│  │ edu. trips│ │ study tours│ │ cultural│ │ exposure │      │
│  │          │ │          │ │ exchanges│ │ visits   │      │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │
│                                                             │
│  [... 8 more types ...]                                     │
│                                                             │
│  [Partner with Maverick]                                    │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Card grid stagger reveal

---

### S11: FAQ ACCORDION

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  FREQUENTLY ASKED QUESTIONS                                 │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                             │
│  What does Edutainment mean?                          ▼    │
│  ─────────────────────────────────────────────────────     │
│  Is Edutainment the same as a normal tour?            ▼    │
│  ─────────────────────────────────────────────────────     │
│  Who can participate?                                 ▼    │
│  ─────────────────────────────────────────────────────     │
│  [... 13 more FAQs ...]                                     │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Accordion smooth expand/collapse
- Chevron rotation

---

### S12: FINAL CTA

**Design:**
```
┌─────────────────────────────────────────────────────────────┐
│  [Background: Students celebrating/learning]                │
│                                                             │
│  Transform a Student Trip into a Learning Journey           │
│                                                             │
│  Let your students discover new cultures, industries,       │
│  institutions and ideas through an experience they          │
│  will remember.                                             │
│                                                             │
│  See more. Experience more. Learn more.                     │
│                                                             │
│  [Plan Your Educational Tour]                               │
│  [Request an Itinerary]                                     │
│  [Enquire on WhatsApp]                                      │
└─────────────────────────────────────────────────────────────┘
```

**Animations:**
- Parallax background
- Text fade-up
- CTA stagger reveal

---

## 🛠️ Technical Implementation

### Route
```php
Route::get('/educational-tours-edutainment', function () {
    return view('pages.edutainment');
})->name('edutainment');
```

### CSS Loading (in layout or page)
```blade
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/edutainment.css') }}" />
@endpush
```

### JS Loading (in page)
```blade
@push('scripts')
<script src="{{ asset('assets/js/pages/edutainment.js') }}" defer></script>
@endpush
```

---

## 🎬 Animation Strategy

### Using AnimationUtils (from animations-utils.js)

```javascript
// Section reveals
AnimationUtils.sectionLabel('#edutainment-section');
AnimationUtils.textReveal('.edutainment__heading .text-reveal-inner');
AnimationUtils.fadeUp('.edutainment__card', { stagger: 0.1 });
AnimationUtils.cards('.edutainment__theme-card', { y: 30 });
```

### Custom Animations (if needed)
- Hero parallax shapes
- Counter animations
- FAQ accordion
- Horizontal scroll for themes

---

## 📋 Implementation Order

| Step | Task | Time |
|------|------|------|
| 1 | Create route | 2 min |
| 2 | Create main blade template | 15 min |
| 3 | Create section partials (12 sections) | 60 min |
| 4 | Create CSS file | 45 min |
| 5 | Create JS animations | 30 min |
| 6 | Test all sections | 15 min |
| 7 | Verify SOP compliance | 10 min |
| 8 | Push to GitHub | 5 min |

**Total: ~3 hours**

---

## ✅ Verification Checklist

### SOP Compliance
- [ ] URL: `/educational-tours-edutainment/`
- [ ] All 12 sections in correct order
- [ ] Content matches SOP word-for-word
- [ ] All CTAs present
- [ ] All FAQs present

### Design System
- [ ] Uses CSS custom properties
- [ ] Uses PP Neue Montreal + Poppins fonts
- [ ] Follows BEM naming convention
- [ ] Responsive design (mobile, tablet, desktop)

### Animations
- [ ] Hero cinematic effects
- [ ] Text reveals working
- [ ] Fade-ups working
- [ ] Card animations working
- [ ] FAQ accordion working
- [ ] Reduced motion respected

### Code Quality
- [ ] Valid HTML
- [ ] Valid CSS
- [ ] Valid JS
- [ ] No console errors
- [ ] No broken links

---

*Plan created: 2026-08-02*
*Status: Awaiting approval*
