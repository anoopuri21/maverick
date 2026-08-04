# Hero Section Alignment Fix — Plan

## 📋 Problem Analysis

### Current State

| Page | Alignment | CSS Class |
|------|-----------|-----------|
| Our Story | **Left-aligned** | `os-hero` |
| Accreditations | Center-aligned | `cinematic-hero` |
| Other Pages | Center-aligned | `cinematic-hero` |

### Target State (Match Our Story)
- **Text alignment:** Left-aligned
- **Padding:** Same as Our Story
- **Font sizes:** Same as Our Story
- **Heading style:** One-line aligned heading

---

## 🎯 Our Story Hero Design Specs

```css
/* Content */
padding-top: calc(80px + 60px);
padding-bottom: 80px;
text-align: left;  /* KEY: Left-aligned */

/* Title */
font-size: clamp(48px, 8vw, 110px);
font-weight: 800;
line-height: 1;
letter-spacing: -0.03em;

/* Description */
font-size: clamp(16px, 1.5vw, 19px);
line-height: 1.7;

/* Mobile */
text-align: left;
padding-top: 20px;
padding-bottom: 20px;
font-size: clamp(36px, 10vw, 60px);
```

---

## 📋 Implementation Plan

### Step 1: Update `cinematic-hero.css`
Change from center-aligned to left-aligned

### Step 2: Update Accreditations page
Apply new styles

### Step 3: Update other pages
Apply same changes

### Step 4: Create Blog & News pages
Add same hero design

### Step 5: Test all pages

---

*Plan ready for approval*
