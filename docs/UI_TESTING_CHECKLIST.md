# UI Testing Checklist — Pre-Commit Verification

## 📋 Overview

This checklist must be completed **before every git commit/push**.

**Rule:** If ANY test fails → DO NOT commit. Fix and re-test.

---

## 🚀 Pre-Flight Checklist

### Step 1: Build Application
```bash
# Install dependencies
composer install
npm install

# Build assets
npm run build

# Start development server
php artisan serve
```

### Step 2: Start Testing Environment
```bash
# In separate terminal
npm run dev
```

---

## ✅ Verification Checklist

### 1. Application Health
- [ ] No PHP errors in terminal
- [ ] No Vite build errors
- [ ] Application starts on http://localhost:8000
- [ ] All routes respond (200 status)
- [ ] Admin panel accessible at /admin

### 2. Browser Console (Open DevTools → Console)
**FAIL if ANY of these appear:**
- [ ] ❌ No TypeError
- [ ] ❌ No ReferenceError
- [ ] ❌ No SyntaxError
- [ ] ❌ No Failed imports
- [ ] ❌ No Missing modules
- [ ] ❌ No GSAP errors
- [ ] ❌ No Swiper errors
- [ ] ❌ No Alpine errors
- [ ] ❌ No Livewire errors
- [ ] ❌ No Filament JS errors

### 3. Network Tab (DevTools → Network)
**FAIL if ANY of these appear:**
- [ ] ❌ No 404 errors
- [ ] ❌ No 500 errors
- [ ] ❌ No failed assets
- [ ] ❌ No missing fonts
- [ ] ❌ No missing images
- [ ] ❌ No failed AJAX requests

### 4. Layout Verification
**Check each page:**
- [ ] Page loads correctly
- [ ] No broken layout
- [ ] No overlapping content
- [ ] No unexpected overflow
- [ ] No horizontal scrolling
- [ ] No invisible sections

---

## 📱 Responsive Testing

### Desktop (1440px)
- [ ] All content visible
- [ ] No overflow
- [ ] Navigation works
- [ ] Images load

### Laptop (1024px)
- [ ] All content visible
- [ ] No overflow
- [ ] Responsive adjustments work

### Tablet (768px)
- [ ] Mobile navigation works
- [ ] Cards stack properly
- [ ] Images resize

### Mobile (375px)
- [ ] Mobile navigation works
- [ ] All content accessible
- [ ] Touch interactions work
- [ ] No horizontal scroll

---

## 🎬 Animation Verification

### GSAP Animations
- [ ] Timelines execute
- [ ] Animations complete
- [ ] Elements become visible
- [ ] Transforms finish correctly
- [ ] Opacity reaches expected values
- [ ] Pinned sections work
- [ ] ScrollTrigger behaves correctly

### Scroll Reveal
- [ ] Reveal starts on scroll
- [ ] Reveal finishes correctly
- [ ] Text becomes visible
- [ ] Stagger animation works
- [ ] Trigger positions are correct

### Slider/Carousel
- [ ] Autoplay works
- [ ] Autoplay timing correct
- [ ] Draggable works
- [ ] Swipe works (mobile)
- [ ] Next/Previous buttons work
- [ ] Pagination works
- [ ] Infinite loop works
- [ ] Touch interaction works

### Hover Effects
- [ ] Hover animation works
- [ ] Color transitions work
- [ ] Scaling works
- [ ] Button states work

### Sticky Sections
- [ ] Sticky positioning works
- [ ] Overlap behavior correct
- [ ] Pin duration correct
- [ ] Release timing correct

### Counters
- [ ] Animation starts on scroll
- [ ] Reaches final value
- [ ] No skipped frames

---

## 🎨 Visual Verification

### Pages to Test
- [ ] Homepage (/)
- [ ] Our Story (/our-story)
- [ ] Dual MBA (/dual-mba-online)
- [ ] Accreditations (/accreditations)
- [ ] Global Partners (/global-university-partners)
- [ ] CSR (/csr-community-impact)
- [ ] Contact (/contact)
- [ ] Edutainment (/educational-tours-edutainment)
- [ ] Admin (/admin)

### For Each Page:
- [ ] Hero section loads correctly
- [ ] All sections visible
- [ ] Images load properly
- [ ] Animations work
- [ ] Links work
- [ ] Forms work (if any)

---

## 🔍 Specific Component Checks

### Our Story Page
- [ ] Hero cinematic effects work
- [ ] Timeline horizontal scroll works
- [ ] Timeline connection line draws
- [ ] Gallery draggable works
- [ ] Gallery auto-slide works
- [ ] Testimonials slider works
- [ ] Lightbox opens/closes

### Dual MBA Page
- [ ] Hero section loads
- [ ] All 16 sections visible
- [ ] FAQ accordion works
- [ ] Animations work

### Edutainment Page
- [ ] Hero section loads
- [ ] All 12 sections visible
- [ ] FAQ accordion works
- [ ] Animations work

---

## 📊 Performance Check

### Lighthouse Scores (Chrome DevTools → Lighthouse)
- [ ] Performance ≥ 90
- [ ] Accessibility ≥ 95
- [ ] Best Practices ≥ 95
- [ ] SEO ≥ 95

---

## 🔐 Accessibility Check

- [ ] All images have alt text
- [ ] Form inputs have labels
- [ ] Color is not the only indicator
- [ ] prefers-reduced-motion respected
- [ ] Keyboard navigation works
- [ ] Focus states visible

---

## 🧹 Pre-Commit Checklist

### Files to NOT Commit
- [ ] No Playwright artifacts
- [ ] No screenshots
- [ ] No test results
- [ ] No coverage reports
- [ ] No temporary files
- [ ] No log files

### Git Status Check
```bash
git status
```
- [ ] Only code changes staged
- [ ] No testing artifacts staged

---

## 🚦 Final Decision

### ✅ SAFE TO COMMIT/PUSH
All checks above pass:
- No console errors
- No network failures
- Layout correct
- Animations work
- Responsive design works
- Performance acceptable

### ❌ DO NOT COMMIT/PUSH
Any check fails:
- Console errors present
- Network failures
- Broken layout
- Animation failures
- Responsive issues
- Performance issues

---

## 📝 Quick Reference Commands

```bash
# Check for PHP errors
php artisan serve

# Check for JS errors
npm run dev

# Run Lighthouse
# Open Chrome DevTools → Lighthouse → Analyze

# Check responsive
# Open Chrome DevTools → Toggle Device Toolbar (Ctrl+Shift+M)

# Check console
# Open Chrome DevTools → Console (F12)
```

---

*Last Updated: 2026-08-03*
*Status: Active — Follow before every commit*
