# Timeline Section Redesign Plan — Our Story Page

## 📋 Current State Analysis

### Existing Implementation
- **Location:** `resources/views/pages/our-story.blade.php` (Section 6)
- **CSS:** `public/assets/css/our-story.css`
- **JS:** `public/assets/js/components/timeline-pinned.js`
- **Type:** Horizontal pinned scroll (desktop) / Vertical cards (mobile)

### Current Issues
- Background is plain/empty
- No connection line on scroll
- No animated geometric shapes
- Timeline feels static

---

## 🎨 Design Vision (UI/UX Pro Max Skill)

### Pattern: Scroll-Triggered Storytelling
- **Style:** Parallax Storytelling + Motion-Driven
- **Animation:** GSAP ScrollTrigger for connection line
- **Background:** Floating geometric shapes

---

## 🖼️ Redesigned Timeline Layout

### Desktop (Horizontal Pinned Scroll)

```
┌──────────────────────────────────────────────────────────────────┐
│  [Background: Animated geometric floating shapes]                │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  CONNECTION LINE (draws on scroll)                         │  │
│  │  ═══════════●══════════●══════════●══════════●══════════  │  │
│  │            2018       2019       2020       2021           │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐  │
│  │ [Photo]  │    │ [Photo]  │    │ [Photo]  │    │ [Photo]  │  │
│  │   2018   │    │   2019   │    │   2020   │    │   2021   │  │
│  │  Title   │    │  Title   │    │  Title   │    │  Title   │  │
│  │  Desc    │    │  Desc    │    │  Desc    │    │  Desc    │  │
│  └──────────┘    └──────────┘    └──────────┘    └──────────┘  │
│                                                                  │
│  [Floating shapes: circles, triangles, squares]                  │
│  [Particles: small dots floating]                                │
│  [Noise texture overlay]                                         │
│                                                                  │
│  ← Scroll horizontally to explore →                              │
└──────────────────────────────────────────────────────────────────┘
```

### Mobile (Vertical Cards)

```
┌─────────────────────────────────────────┐
│  [Background: Animated shapes]          │
│                                         │
│  Our Journey                            │
│  ══════════════════════════════════════  │
│                                         │
│  ●─── 2018 ──────────────────────────── │
│  │    [Photo]                           │
│  │    Title                             │
│  │    Description                       │
│  │                                      │
│  ●─── 2019 ──────────────────────────── │
│  │    [Photo]                           │
│  │    Title                             │
│  │    Description                       │
│  │                                      │
│  ●─── 2020 ──────────────────────────── │
│  │    [Photo]                           │
│  │    Title                             │
│  │    Description                       │
│                                         │
│  [Floating shapes]                      │
└─────────────────────────────────────────┘
```

---

## 🎬 Animation Specifications

### 1. Connection Line (Draw on Scroll)

**Behavior:** Line draws progressively as user scrolls through timeline

**GSAP Implementation:**
```javascript
// SVG line that draws on scroll
gsap.to(".timeline-connection-line", {
  strokeDashoffset: 0,
  scrollTrigger: {
    trigger: "#journey",
    start: "top top",
    end: "bottom bottom",
    scrub: 1
  }
});
```

**CSS:**
```css
.timeline-connection-line {
  stroke-dasharray: 1000;
  stroke-dashoffset: 1000;
  stroke: var(--os-red);
  stroke-width: 2;
  fill: none;
}
```

---

### 2. Animated Geometric Shapes (Background)

**Shapes to Include:**
- Circles (different sizes)
- Triangles
- Squares/Rotated squares
- Lines
- Dots

**Animation Types:**
- Floating (up/down movement)
- Rotation (slow spin)
- Scale (pulse effect)
- Opacity (fade in/out)

**CSS Implementation:**
```css
.os-journey__bg-shape {
  position: absolute;
  pointer-events: none;
  animation: journeyFloat 15s ease-in-out infinite;
}

.os-journey__bg-shape--circle {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 1px solid rgba(220, 38, 38, 0.1);
}

.os-journey__bg-shape--triangle {
  width: 0;
  height: 0;
  border-left: 50px solid transparent;
  border-right: 50px solid transparent;
  border-bottom: 87px solid rgba(26, 43, 122, 0.05);
}

.os-journey__bg-shape--square {
  width: 60px;
  height: 60px;
  border: 1px solid rgba(26, 43, 122, 0.08);
  transform: rotate(45deg);
}

@keyframes journeyFloat {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  33% { transform: translateY(-20px) rotate(3deg); }
  66% { transform: translateY(10px) rotate(-2deg); }
}
```

---

### 3. Timeline Dots (Connection Points)

**Behavior:** Dots pulse when connection line reaches them

**CSS:**
```css
.os-journey__dot {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: var(--os-red);
  position: relative;
}

.os-journey__dot::before {
  content: "";
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  border: 2px solid var(--os-red);
  animation: dotPulse 2s ease-in-out infinite;
}

@keyframes dotPulse {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.3); opacity: 0; }
}
```

---

## 📁 Files to Modify

### 1. Blade Template
**File:** `resources/views/pages/our-story.blade.php`

**Changes:**
- Add background shapes container
- Add SVG connection line
- Add timeline dots at connection points
- Enhance slide structure

---

### 2. CSS Styles
**File:** `public/assets/css/our-story.css`

**Changes:**
- Add background shape styles
- Add connection line styles
- Add timeline dot styles
- Add floating animation keyframes
- Enhance slide card styles

---

### 3. JavaScript Animations
**File:** `public/assets/js/components/timeline-pinned.js`

**Changes:**
- Add connection line scroll animation
- Add dot pulse animation on scroll
- Add background shape floating animation
- Enhance existing pinned scroll logic

---

## 🎨 Design System Compliance

### Colors (Existing)
```css
--os-navy: #1a2b7a;
--os-navy-dark: #142260;
--os-red: #dc2626;
```

### Typography (Existing)
```css
--font-display: "PP Neue Montreal", sans-serif;
--font-body: "Poppins", sans-serif;
```

### Animations
```css
--transition: 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
```

---

## 📐 Geometric Shapes Specification

### Shape 1: Large Circle (Top Left)
```css
{
  position: top: 10%; left: 5%;
  size: 150px;
  border: 1px solid rgba(220, 38, 38, 0.08);
  animation: float 20s ease-in-out infinite;
}
```

### Shape 2: Small Circle (Bottom Right)
```css
{
  position: bottom: 15%; right: 10%;
  size: 80px;
  border: 1px solid rgba(26, 43, 122, 0.1);
  animation: float 15s ease-in-out infinite reverse;
}
```

### Shape 3: Triangle (Top Right)
```css
{
  position: top: 20%; right: 15%;
  size: 60px;
  color: rgba(26, 43, 122, 0.05);
  animation: float 18s ease-in-out infinite 3s;
}
```

### Shape 4: Rotated Square (Bottom Left)
```css
{
  position: bottom: 25%; left: 8%;
  size: 50px;
  border: 1px solid rgba(220, 38, 38, 0.06);
  transform: rotate(45deg);
  animation: float 22s ease-in-out infinite;
}
```

### Shape 5: Dots Pattern (Scattered)
```css
{
  position: random;
  size: 4px;
  background: rgba(26, 43, 122, 0.15);
  animation: float 10s ease-in-out infinite;
}
```

---

## 🔧 Technical Implementation

### Step 1: Add Background Shapes to Blade

```blade
{{-- Background Geometric Shapes --}}
<div class="os-journey__bg-shapes" aria-hidden="true">
  <div class="os-journey__bg-shape os-journey__bg-shape--circle os-journey__bg-shape--1"></div>
  <div class="os-journey__bg-shape os-journey__bg-shape--circle os-journey__bg-shape--2"></div>
  <div class="os-journey__bg-shape os-journey__bg-shape--triangle os-journey__bg-shape--3"></div>
  <div class="os-journey__bg-shape os-journey__bg-shape--square os-journey__bg-shape--4"></div>
  <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--5"></div>
  <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--6"></div>
  <div class="os-journey__bg-shape os-journey__bg-shape--dot os-journey__bg-shape--7"></div>
</div>
```

### Step 2: Add Connection Line SVG

```blade
{{-- Connection Line (Desktop only) --}}
<svg class="os-journey__connection-line" viewBox="0 0 1000 2" preserveAspectRatio="none">
  <line class="os-journey__line-path" x1="0" y1="1" x2="1000" y2="1" />
</svg>
```

### Step 3: Add Timeline Dots

```blade
{{-- Timeline Dots --}}
<div class="os-journey__dots">
  @foreach($timelines as $index => $item)
    <div class="os-journey__dot" data-dot="{{ $index }}"></div>
  @endforeach
</div>
```

### Step 4: GSAP Connection Line Animation

```javascript
// Draw connection line on scroll
const linePath = document.querySelector('.os-journey__line-path');
if (linePath) {
  const lineLength = linePath.getTotalLength();
  gsap.set(linePath, {
    strokeDasharray: lineLength,
    strokeDashoffset: lineLength
  });
  
  gsap.to(linePath, {
    strokeDashoffset: 0,
    scrollTrigger: {
      trigger: '#journey',
      start: 'top top',
      end: 'bottom bottom',
      scrub: 1
    }
  });
}
```

---

## 📊 Expected Results

### Before Redesign
- ❌ Plain background
- ❌ No connection line
- ❌ Static feel
- ❌ Empty spaces

### After Redesign
- ✅ Animated geometric shapes background
- ✅ Connection line draws on scroll
- ✅ Cinematic, immersive feel
- ✅ Floating shapes fill empty spaces
- ✅ Pulsing timeline dots
- ✅ Enhanced visual storytelling

---

## 📋 Implementation Checklist

- [ ] Add background shapes to blade template
- [ ] Add connection line SVG
- [ ] Add timeline dots
- [ ] Add CSS for shapes and animations
- [ ] Add CSS for connection line
- [ ] Update timeline-pinned.js with new animations
- [ ] Test responsive behavior
- [ ] Test reduced motion support
- [ ] Verify performance

---

*Plan created: 2026-08-03*
*Status: Awaiting approval*
