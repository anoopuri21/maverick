# Animation Optimization Mega Plan

## 📊 Current State Analysis

### File: `public/assets/js/animations.js`
- **Total Lines:** 3,277
- **Functions:** 23
- **Desktop/Mobile Duplication:** 5 instances of `ScrollTrigger.matchMedia`
- **Repeated Patterns:**
  - Text-reveal animation: 24 times
  - Fade-up animation: 58 times
  - Section-label animation: 29 times

---

## 🔍 Problem Identified

### Massive Code Duplication

Most sections have nearly identical animation code repeated 2-3 times:

```javascript
// This pattern appears 24 times with minor variations
gsap.fromTo(headingLines,
  { y: "110%" },
  { y: "0%", duration: 0.9, stagger: 0.12, ease: "power3.out",
    scrollTrigger: { trigger: "#section", start: "top 75%", toggleActions: "play none none none" }
  }
);

// This pattern appears 58 times
gsap.fromTo(element,
  { opacity: 0, y: 30 },
  { opacity: 1, y: 0, duration: 0.8, ease: "power2.out",
    scrollTrigger: { trigger: ".element", start: "top 80%" }
  }
);
```

### Desktop vs Mobile Duplication

5 sections have `ScrollTrigger.matchMedia()` with nearly identical code:
- Numbers Section
- Who We Are
- What We Do
- How We Do It
- Featured Programs

**Only differences:** Duration, Y offset, stagger delay

---

## ✅ Optimization Strategy

### Phase 1: Extract Reusable Utilities (No Behavior Change)

Create utility functions that encapsulate common patterns:

```javascript
// Utility 1: Text Reveal Animation
function animateTextReveal(selector, options = {}) {
  const { trigger, start = "top 75%", stagger = 0.12, duration = 0.9 } = options;
  const elements = document.querySelectorAll(selector);
  if (!elements.length) return;
  
  gsap.fromTo(elements,
    { y: "110%" },
    { y: "0%", duration, stagger, ease: "power3.out",
      scrollTrigger: { trigger, start, toggleActions: "play none none none" }
    }
  );
}

// Utility 2: Fade Up Animation
function animateFadeUp(selector, options = {}) {
  const { trigger, start = "top 80%", y = 30, duration = 0.8, stagger = 0 } = options;
  const elements = document.querySelectorAll(selector);
  if (!elements.length) return;
  
  gsap.fromTo(elements,
    { opacity: 0, y },
    { opacity: 1, y: 0, duration, stagger, ease: "power2.out",
      scrollTrigger: { trigger, start, toggleActions: "play none none none" }
    }
  );
}

// Utility 3: Section Label Animation
function animateSectionLabel(sectionSelector, options = {}) {
  const label = document.querySelector(`${sectionSelector} .section-label`);
  if (!label) return;
  
  gsap.fromTo(label,
    { opacity: 0, y: 16 },
    { opacity: 1, y: 0, duration: 0.6, ease: "power2.out",
      scrollTrigger: { trigger: sectionSelector, start: "top 75%", ...options }
    }
  );
}

// Utility 4: Responsive Config (eliminates matchMedia duplication)
function getResponsiveConfig(desktopConfig, mobileConfig) {
  return window.innerWidth <= 768 ? mobileConfig : desktopConfig;
}
```

---

### Phase 2: Refactor Each Section (Preserve Exact Behavior)

#### 2.1 Numbers Section (~250 lines → ~80 lines)

**Before:**
```javascript
function initNumbersAnimations() {
  // 250 lines with duplicated desktop/mobile code
  ScrollTrigger.matchMedia({
    "(min-width: 769px)": function () { /* 100 lines */ },
    "(max-width: 768px)": function () { /* 100 lines */ }
  });
}
```

**After:**
```javascript
function initNumbersAnimations() {
  const section = document.querySelector("#numbers");
  if (!section) return;
  
  if (prefersReducedMotion) {
    // Single reduced motion handler
    setReducedMotion(["#numbers .text-reveal-inner", "#numbers .fade-up"]);
    return;
  }
  
  const cfg = getResponsiveConfig(
    { y: -40, duration: 0.7, stagger: 0.1 },
    { y: -20, duration: 0.6, stagger: 0.06 }
  );
  
  animateTextReveal("#numbers .numbers__heading-line .text-reveal-inner", {
    trigger: "#numbers"
  });
  animateSectionLabel("#numbers");
  animateFadeUp("#numbers .numbers__card", { stagger: cfg.stagger, y: cfg.y });
  // Counter logic stays as-is (unique)
}
```

---

#### 2.2 Who We Are Section (~270 lines → ~60 lines)

**Before:** 270 lines with desktop/mobile duplication

**After:**
```javascript
function initWWAAnimations() {
  if (!elementExists("#who-we-are")) return;
  if (prefersReducedMotion) { setReducedMotion([...]); return; }
  
  const cfg = getResponsiveConfig(
    { imageY: -40, bodyY: 30, scrub: 1.5 },
    { imageY: -20, bodyY: 20, scrub: 1 }
  );
  
  animateSectionLabel("#who-we-are");
  animateTextReveal(".wwa__heading-line .text-reveal-inner");
  animateFadeUp(".wwa__body", { trigger: ".wwa__body", y: cfg.bodyY });
  animateFadeUp(".wwa__stats", { trigger: ".wwa__stats", y: cfg.bodyY });
  animateFadeUp(".wwa__cta", { trigger: ".wwa__cta", y: 24 });
  
  // Image parallax (unique)
  gsap.to(".wwa__image", {
    y: cfg.imageY, ease: "none",
    scrollTrigger: { trigger: ".wwa__image-col", start: "top bottom", end: "bottom top", scrub: cfg.scrub }
  });
}
```

---

#### 2.3 What We Do Section (~290 lines → ~70 lines)

Same pattern extraction as above.

---

#### 2.4 How We Do It Section (~270 lines → ~80 lines)

Same pattern extraction, keeping unique step-number and connector animations.

---

#### 2.5 Alumni Section (~160 lines → ~50 lines)

---

#### 2.6 Programs Section (~150 lines → ~60 lines)

Keep horizontal scroll logic (unique), extract common fade-ups.

---

#### 2.7 Why Maverick Section (~150 lines → ~40 lines)

---

#### 2.8 Opportunities Section (~200 lines → ~60 lines)

---

#### 2.9 Partners Section (~100 lines → ~40 lines)

---

#### 2.10 Insights Section (~100 lines → ~35 lines)

---

#### 2.11 Events Section (~100 lines → ~35 lines)

---

#### 2.12 Testimonials Section (~100 lines → ~35 lines)

---

#### 2.13 Final CTA Section (~150 lines → ~45 lines)

---

#### 2.14 Footer Section (~100 lines → ~40 lines)

---

### Phase 3: Extract Shared Utilities

```javascript
// =========================================================
// SHARED ANIMATION UTILITIES
// =========================================================

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function setReducedMotion(selectors) {
  gsap.set(selectors, { clearProps: "all", opacity: 1 });
  gsap.set(selectors.filter(s => s.includes("text-reveal")), { y: "0%" });
}

function getResponsiveConfig(desktop, mobile) {
  return window.innerWidth <= 768 ? mobile : desktop;
}

function animateTextReveal(selector, options = {}) {
  // ... reusable implementation
}

function animateFadeUp(selector, options = {}) {
  // ... reusable implementation
}

function animateSectionLabel(sectionSelector) {
  // ... reusable implementation
}

function animateCards(selector, options = {}) {
  // ... reusable implementation for card grids
}
```

---

## 📊 Expected Results

### Line Count Reduction

| Section | Before | After | Savings |
|---------|--------|-------|---------|
| Utilities (new) | 0 | +80 | — |
| Numbers | 250 | 80 | -170 |
| Who We Are | 270 | 60 | -210 |
| What We Do | 290 | 70 | -220 |
| How We Do It | 270 | 80 | -190 |
| Alumni | 160 | 50 | -110 |
| Programs | 150 | 60 | -90 |
| Why Maverick | 150 | 40 | -110 |
| Opportunities | 200 | 60 | -140 |
| Partners | 100 | 40 | -60 |
| Insights | 100 | 35 | -65 |
| Events | 100 | 35 | -65 |
| Testimonials | 100 | 35 | -65 |
| Final CTA | 150 | 45 | -105 |
| Footer | 100 | 40 | -60 |
| **TOTAL** | **3,277** | **~1,300** | **~1,977 lines** |

**Reduction:** ~60% smaller file

---

## 🔒 Safety Measures

### What Will NOT Change
- ✅ Hero entrance animation (complex, unique)
- ✅ WIM pinned section (complex, unique)
- ✅ Programs horizontal scroll (unique)
- ✅ Infinite slider logic (shared utility)
- ✅ Counter animation (shared utility)
- ✅ FAQ accordion (shared utility)
- ✅ All animation timings (same durations)
- ✅ All animation easings (same curves)
- ✅ All trigger points (same scroll positions)
- ✅ Reduced motion support (same behavior)
- ✅ Mobile-specific values (same configs)

### What WILL Change
- ✅ Code structure (extracted utilities)
- ✅ Elimination of duplication
- ✅ Consistent configuration approach
- ✅ Better maintainability

---

## 🧪 Testing Checklist

After each refactor, verify:

### Visual Tests
- [ ] Hero entrance plays correctly
- [ ] Numbers counter animates
- [ ] WIM pinned scroll works
- [ ] All text reveals work
- [ ] All fade-ups work
- [ ] All card animations work
- [ ] Programs horizontal scroll works
- [ ] FAQ accordion works
- [ ] All sliders work

### Responsive Tests
- [ ] Desktop (>1024px) animations correct
- [ ] Tablet (768-1024px) animations correct
- [ ] Mobile (<768px) animations correct

### Performance Tests
- [ ] No console errors
- [ ] No animation jank
- [ ] Reduced motion respected
- [ ] Page load not affected

### Cross-Browser Tests
- [ ] Chrome works
- [ ] Firefox works
- [ ] Safari works
- [ ] Mobile browsers work

---

## 📋 Implementation Order

### Step 1: Create Utilities File
Create `public/assets/js/animations-utils.js` with shared functions.

### Step 2: Refactor Simplest Sections First
1. Insights (~100 lines → ~35)
2. Events (~100 lines → ~35)
3. Testimonials (~100 lines → ~35)
4. Final CTA (~150 lines → ~45)

### Step 3: Refactor Medium Sections
5. Partners (~100 lines → ~40)
6. Alumni (~160 lines → ~50)
7. Why Maverick (~150 lines → ~40)
8. Footer (~100 lines → ~40)

### Step 4: Refactor Complex Sections
9. Opportunities (~200 lines → ~60)
10. Programs (~150 lines → ~60)
11. Who We Are (~270 lines → ~60)
12. What We Do (~290 lines → ~70)
13. How We Do It (~270 lines → ~80)
14. Numbers (~250 lines → ~80)

### Step 5: Keep Untouched
- Hero animations (complex, unique)
- WIM animations (complex, unique)
- Infinite slider utility (already clean)
- Logo slider section (already clean)

### Step 6: Final Verification
- Run all visual tests
- Run all responsive tests
- Run performance tests
- Commit and push

---

## ⚠️ Risk Mitigation

### Before Starting
- [ ] Create new branch `imp/optimize-animation`
- [ ] Document current behavior with screenshots
- [ ] Note any known issues

### During Implementation
- [ ] Refactor one section at a time
- [ ] Test after each section
- [ ] Commit after each successful refactor
- [ ] Keep original code in comments until verified

### After Completion
- [ ] Full regression test
- [ ] Performance comparison
- [ ] Code review
- [ ] Merge to dev

---

## 📝 Notes

- This is a **refactoring task**, not a feature task
- **No visual changes** should occur
- **No behavior changes** should occur
- **No new bugs** should be introduced
- Focus on **code quality** and **maintainability**

---

*Plan created: 2026-08-02*
*Status: Ready for implementation*
*Estimated effort: 3-4 hours*
*Estimated reduction: ~60% (3,277 → ~1,300 lines)*
