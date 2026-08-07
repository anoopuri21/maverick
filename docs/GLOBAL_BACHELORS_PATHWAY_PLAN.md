# Global Bachelor's Pathway — Redesign Plan

## 📋 SOP Section Sequence (Client Requirement)

| # | SOP Section | Current Page Status |
|---|-------------|---------------------|
| 1 | Hero | ✅ Exists - needs design update |
| 2 | What is the Maverick Bachelor's Pathway Programme? | ✅ Exists as "Overview" |
| 3 | Why Choose This Pathway Programme? | ✅ Exists as "Why Pathway" |
| 4 | Explore Europe with Your Choices | ⚠️ Missing - need to add |
| 5 | Program Pathway Structure | ✅ Exists as "Stages" |
| 6 | Study Destinations (Hungary, Romania, Moldova) | ✅ Exists as "Destinations" |
| 7 | Cost & Time Advantage | ✅ Exists as "Cost" |
| 8 | Programs Offered (Pathway Areas) | ✅ Exists as "Areas" |
| 9 | Partner University Progression Options | ✅ Exists as "Partners" |
| 10 | Admission Requirements | ✅ Exists as "Admission" |
| 11 | Documents Required | ✅ Exists as "Docs" |
| 12 | Final CTA | ✅ Exists |

---

## 🎯 Section Sequence Fix

### Current Sequence (WRONG)
```
1. Hero
2. Overview
3. Stages
4. Why Pathway
5. Destinations
6. Cost
7. Areas
8. Partners
9. Admission
10. Docs
11. Final CTA
```

### SOP Sequence (CORRECT)
```
1.  Hero
2.  What is the Pathway Programme? (Overview)
3.  Why Choose This Pathway? (Why Pathway)
4.  Explore Europe with Your Choices (NEW)
5.  Program Pathway Structure (Stages)
6.  Study Destinations (Destinations)
7.  Cost & Time Advantage (Cost)
8.  Programs Offered (Areas)
9.  Partner University Progression (Partners)
10. Admission Requirements (Admission)
11. Documents Required (Docs)
12. Final CTA
```

---

## 🎨 Design Changes Required

### 1. Hero Section
**Current:** Basic overlay hero
**Target:** Cinematic hero matching Our Story design
- Left-aligned text
- Ken Burns background effect
- Noise texture
- Floating shapes
- Particles
- Scanline
- Corner brackets

### 2. Add "Explore Europe" Section (NEW)
Three cards for Hungary, Romania, Moldova with:
- Flag/country icon
- Country name
- Pathway type (Premium/Affordable)
- Brief description

### 3. Typography Alignment
Ensure all headings use:
- Font: PP Neue Montreal
- Sizes: Match design system
- Colors: Navy + Red accent

---

## 📁 Files to Modify

| File | Changes |
|------|---------|
| `resources/views/pages/global-bachelors-pathway.blade.php` | Rewrite with correct sequence |
| `public/css/pages/global-bachelors-pathway.css` | Update hero + add new section styles |

---

## 🎬 Animation Requirements

| Element | Animation | Library |
|---------|-----------|---------|
| Hero | Ken Burns, particles, scanline | CSS |
| Section reveals | Fade-up on scroll | GSAP ScrollTrigger |
| Cards | Stagger fade-up | GSAP |
| Stats | Counter animation | GSAP |

---

## ✅ Verification Checklist

- [ ] Section sequence matches SOP
- [ ] Hero matches Our Story design
- [ ] All content matches SOP
- [ ] Typography consistent
- [ ] Animations working
- [ ] Responsive design
- [ ] No broken elements

---

*Plan created: 2026-08-04*
*Status: Awaiting approval*
