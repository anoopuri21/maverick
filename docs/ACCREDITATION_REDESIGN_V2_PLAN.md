# Accreditation Page Redesign Plan v2

## 📋 Client Requirements

1. **Hero Section** — Match Our Story page hero design
2. **Typography** — Consistent with homepage design system
3. **Accreditations Cards** — Image + Title only (light border, medium logo, title)
4. **Background** — Geometric bouncing shapes to fill empty space
5. **Awards Section** — Slider with logo + title + small badge (top-left)

---

## 🎨 UI/UX Pro Max Skill Recommendations

### Design Pattern: Minimal + Geometric
- **Style:** Flat Design + Memphis accents
- **Colors:** Navy (#1a2b7a), Red (#dc2626), White
- **Typography:** PP Neue Montreal (headings), Poppins (body)
- **Effects:** Floating geometric shapes, smooth transitions

---

## 📐 Redesign Specifications

### 1. Hero Section (Match Our Story)

```
┌─────────────────────────────────────────────────────────┐
│ [Background: Dark gradient + noise texture]             │
│ [Floating geometric shapes]                             │
│ [Particles]                                             │
│ [Corner brackets]                                       │
│                                                         │
│   ACCREDITATIONS & RECOGNITIONS                        │
│   ─────────────────────────────                        │
│                                                         │
│   Globally Recognised,                                  │
│   Locally Trusted                                       │
│                                                         │
│   Description text...                                   │
│                                                         │
│   [Scroll to explore ↓]                                │
└─────────────────────────────────────────────────────────┘
```

### 2. Accreditations Cards (Simplified)

```
┌─────────────────────────────────────────────────────────┐
│                                                         │
│   ┌─────┐   ┌─────┐   ┌─────┐   ┌─────┐              │
│   │     │   │     │   │     │   │     │              │
│   │LOGO │   │LOGO │   │LOGO │   │LOGO │  ← Slider   │
│   │     │   │     │   │     │   │     │              │
│   └─────┘   └─────┘   └─────┘   └─────┘              │
│   Title     Title      Title     Title                │
│                                                         │
│   [Geometric shapes floating in background]            │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

**Card Design:**
- Light rounded border (1px solid #e5e7eb)
- Medium size logo (80x80px)
- Title below logo
- Minimal, clean design
- Hover: subtle lift + shadow

### 3. Awards Section

```
┌─────────────────────────────────────────────────────────┐
│                                                         │
│   ┌─────┐   ┌─────┐   ┌─────┐   ┌─────┐              │
│   │BADGE│   │BADGE│   │BADGE│   │BADGE│              │
│   │ ┌─┐ │   │ ┌─┐ │   │ ┌─┐ │   │ ┌─┐ │              │
│   │ │ │ │   │ │ │ │   │ │ │ │   │ │ │ │  ← Slider   │
│   │ │ │ │   │ │ │ │   │ │ │ │   │ │ │ │              │
│   │ └─┘ │   │ └─┘ │   │ └─┘ │   │ └─┘ │              │
│   │Title│   │Title│   │Title│   │Title│              │
│   └─────┘   └─────┘   └─────┘   └─────┘              │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

**Card Design:**
- Medium size image/logo (120x80px)
- Title below image
- Small badge on top-left corner
- Clean, minimal design

### 4. Geometric Background Shapes

```
Shape Types:
- Circles (different sizes, floating)
- Triangles (rotated)
- Squares (rotated 45deg)
- Dots (scattered)

Animation:
- Floating (up/down movement)
- Slow rotation
- Different speeds per shape
```

---

## 📁 Files to Modify

| File | Changes |
|------|---------|
| `resources/views/pages/accreditations.blade.php` | Complete rewrite |
| `public/css/pages/accreditations-new.css` | New styles |

---

## ✅ Testing Checklist

- [ ] Hero matches Our Story design
- [ ] Typography consistent with homepage
- [ ] Accreditations show logo + title only
- [ ] Geometric shapes animate
- [ ] Awards show badge + logo + title
- [ ] All sliders work (drag + auto-slide)
- [ ] Responsive on all devices
- [ ] No console errors
- [ ] Animations smooth

---

*Plan created: 2026-08-03*
