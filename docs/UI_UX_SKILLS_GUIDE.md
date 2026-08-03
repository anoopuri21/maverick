# UI/UX Skills & Tools — Maverick Project

## 📋 Overview

This document lists all recommended UI/UX skills and tools for the Maverick Business Academy project. These are **best practices** for creating premium, cinematic web experiences.

---

## 🎯 Skills Currently In Use

| Skill/Tool | Version | Purpose | Status |
|------------|---------|---------|--------|
| **GSAP** | 3.12.5 | Animation engine | ✅ Active |
| **ScrollTrigger** | 3.12.5 | Scroll-based animations | ✅ Active |
| **Lenis** | 1.0.42 | Smooth scroll | ✅ Active |
| **Lucide Icons** | 0.468.0 | Icon library | ✅ Active |
| **Alpine.js** | — | UI state management | ✅ Active |
| **Custom CSS** | — | Styling (BEM) | ✅ Active |

---

## 🚀 Recommended Skills to Add

### Tier 1: Essential (High Impact, Low Effort)

#### 1. **GSAP SplitText** (Now Free)
```
Purpose: Animate individual characters, words, or lines
Use Case: Hero headings with staggered text reveal
Impact: Premium cinematic feel
```

**Example Usage:**
```javascript
// Split heading into words
const split = new SplitText(".hero__title", { type: "words" });

// Animate words staggered
gsap.from(split.words, {
  opacity: 0,
  y: 50,
  stagger: 0.08,
  duration: 0.8,
  ease: "power3.out"
});
```

**Where to Use:**
- Hero sections on all pages
- Section headings
- Call-to-action text

---

#### 2. **GSAP MorphSVG** (Now Free)
```
Purpose: Smooth SVG shape morphing
Use Case: Logo animations, icon transitions, decorative elements
Impact: Unique visual storytelling
```

**Example Usage:**
```javascript
// Morph one SVG shape to another
gsap.to("#circle", {
  morphSVG: "#star",
  duration: 1,
  ease: "power2.inOut"
});
```

**Where to Use:**
- Logo animation on page load
- Section transition shapes
- Decorative background elements

---

#### 3. **GSAP DrawSVG** (Now Free)
```
Purpose: Animate SVG stroke drawing
Use Case: Line animations, icon reveals, path animations
Impact: Elegant reveal effects
```

**Example Usage:**
```javascript
// Draw SVG path
gsap.from(".icon path", {
  drawSVG: "0%",
  duration: 1.5,
  ease: "power2.inOut"
});
```

**Where to Use:**
- Icon animations
- Decorative lines
- Timeline connectors

---

### Tier 2: Enhanced Experience (Medium Impact)

#### 4. **Swiper.js**
```
Purpose: Touch-friendly sliders/carousels
Use Case: Testimonial sliders, image galleries, card carousels
Impact: Better mobile experience
```

**Example Usage:**
```javascript
const swiper = new Swiper('.swiper', {
  slidesPerView: 3,
  spaceBetween: 30,
  pagination: { el: '.swiper-pagination' },
  navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
  breakpoints: {
    768: { slidesPerView: 2 },
    480: { slidesPerView: 1 }
  }
});
```

**Where to Use:**
- Testimonial carousels
- Image galleries
- Card sliders

---

#### 5. **LottieFiles / DotLottie**
```
Purpose: Render After Effects animations
Use Case: Loading animations, micro-interactions, illustrations
Impact: Professional motion design
```

**Example Usage:**
```javascript
import { DotLottie } from '@lottiefiles/dotlottie-web';

const player = new DotLottie({
  canvas: document.getElementById('lottie-canvas'),
  src: 'animation.json',
  autoplay: true,
  loop: true
});
```

**Where to Use:**
- Page loading animations
- Success/error states
- Decorative illustrations

---

#### 6. **Rive**
```
Purpose: Interactive state-machine animations
Use Case: Interactive characters, animated icons, onboarding
Impact: Engaging user interaction
```

**Where to Use:**
- Interactive mascots
- Animated navigation icons
- Onboarding flows

---

### Tier 3: Advanced (High Impact, Higher Effort)

#### 7. **Three.js / WebGL**
```
Purpose: 3D graphics and immersive experiences
Use Case: 3D product viewers, particle effects, immersive heroes
Impact: Wow factor, differentiation
```

**Where to Use:**
- Hero section particle effects
- 3D globe for university partners
- Interactive program visualizations

---

#### 8. **Barba.js**
```
Purpose: Smooth page transitions
Use Case: AJAX page transitions, loading animations
Impact: App-like experience
```

**Example Usage:**
```javascript
barba.init({
  transitions: [{
    name: 'fade',
    leave(data) {
      return gsap.to(data.current.container, { opacity: 0 });
    },
    enter(data) {
      return gsap.from(data.next.container, { opacity: 0 });
    }
  }]
});
```

**Where to Use:**
- All page transitions
- Loading states

---

#### 9. **Theatre.js**
```
Purpose: Visual animation editor
Use Case: Complex scroll sequences, choreographed animations
Impact: Designer-friendly animation creation
```

**Where to Use:**
- Complex hero animations
- Multi-section scroll stories
- Product showcases

---

### Tier 4: Performance & Optimization

#### 10. **imagesLoaded**
```
Purpose: Detect when images are loaded
Use Case: Prevent layout shifts, trigger animations after load
Impact: Better CLS score
```

**Where to Use:**
- Gallery sections
- Image-heavy pages

---

#### 11. **Lozad.js**
```
Purpose: Lightweight lazy loading
Use Case: Images, iframes, videos
Impact: Better LCP score
```

**Where to Use:**
- All images below the fold
- Video embeds
- Iframes

---

#### 12. **Fitty**
```
Purpose: Auto-fit text to container
Use Case: Responsive headings, stats numbers
Impact: Better typography
```

**Where to Use:**
- Hero headings
- Counter numbers
- Card titles

---

## 🎨 CSS/Design Skills

### 13. **CSS Container Queries**
```
Purpose: Responsive design based on container size
Use Case: Component-level responsiveness
Impact: Better component architecture
```

**Where to Use:**
- Card components
- Sidebar widgets
- Modular sections

---

### 14. **CSS Scroll-Driven Animations**
```
Purpose: CSS-only scroll animations
Use Case: Simple parallax, fade effects
Impact: No JS overhead
```

**Where to Use:**
- Simple fade-up effects
- Background parallax
- Progress indicators

---

### 15. **CSS View Transitions API**
``Purpose: Native page transitions
Use Case: Smooth page changes
Impact: App-like feel``

**Where to Use:**
- Page navigation
- Content switching

---

## 📐 Architecture Skills

### 16. **Intersection Observer API**
```
Purpose: Detect element visibility
Use Case: Lazy loading, scroll animations, tracking
Impact: Performance optimization
```

**Status:** ✅ Already in use (reveal-observer.js)

---

### 17. **Web Animations API (WAAPI)**
```
Purpose: Browser-native animation API
Use Case: Simple animations without GSAP
Impact: Smaller bundle size
```

**Where to Use:**
- Micro-interactions
- Hover effects
- Simple transitions

---

### 18. **RequestAnimationFrame (RAF)**
```
Purpose: Smooth animation loop
Use Case: Custom animations, scroll effects
Impact: 60fps performance
```

**Status:** ✅ Already in use (via GSAP/Lenis)

---

## 🛠️ Tool Skills

### 19. **Vite**
```
Purpose: Build tool and dev server
Use Case: Asset compilation, HMR
Impact: Faster development
```

**Status:** ✅ Installed (not actively used for frontend)

---

### 20. **PostCSS**
```
Purpose: CSS processing
Use Case: Autoprefixer, CSS nesting, custom properties
Impact: Browser compatibility
```

---

### 21. **Stylelint**
```
Purpose: CSS linting
Use Case: Enforce BEM naming, catch errors
Impact: Code quality
```

---

## 📋 Recommended Implementation Order

| Priority | Skill | Effort | Impact | When |
|----------|-------|--------|--------|------|
| 🔴 P1 | GSAP SplitText | Low | High | Next sprint |
| 🔴 P1 | GSAP MorphSVG | Low | High | Next sprint |
| 🔴 P1 | GSAP DrawSVG | Low | High | Next sprint |
| 🟡 P2 | Swiper.js | Medium | Medium | When building carousels |
| 🟡 P2 | LottieFiles | Medium | Medium | When adding illustrations |
| 🟢 P3 | Barba.js | High | High | Major redesign |
| 🟢 P3 | Three.js | High | High | Hero section upgrade |
| 🟢 P3 | Rive | Medium | Medium | Interactive elements |

---

## 📚 Learning Resources

### GSAP (Now 100% Free)
- **Docs:** https://gsap.com/docs/
- **ScrollTrigger:** https://gsap.com/docs/v3/Plugins/ScrollTrigger/
- **SplitText:** https://gsap.com/docs/v3/Plugins/SplitText/
- **Video Tutorials:** https://gsap.com/learning/

### Lenis
- **Docs:** https://github.com/darkroomengineering/lenis
- **Examples:** https://lenis.darkroom.engineering/

### Swiper.js
- **Docs:** https://swiperjs.com/get-started
- **Examples:** https://swiperjs.com/demos

### LottieFiles
- **Docs:** https://lottiefiles.github.io/lottie-docs/
- **Player:** https://github.com/LottieFiles/dotlottie-web

---

## 🎯 Project-Specific Recommendations

### For Maverick Business Academy:

1. **Hero Sections:** Use GSAP SplitText for heading animations
2. **Testimonials:** Use Swiper.js for touch-friendly carousels
3. **University Partners Map:** Consider Three.js for interactive 3D globe
4. **Page Transitions:** Consider Barba.js for app-like feel
5. **Loading States:** Use LottieFiles for branded animations
6. **Decorative Elements:** Use GSAP MorphSVG for shape transitions

---

## 📝 How to Include Skills in Future Chats

When starting a new chat, include this in your message:

```
Project: Maverick Business Academy
Tech Stack: Laravel + Blade + Custom CSS (BEM)
Animation: GSAP + ScrollTrigger + Lenis
Icons: Lucide
Skills Available: [list skills from this document]
```

---

*Document created: 2026-08-02*
*Status: Awaiting approval for implementation*
