# Partner Logo System Redesign & Accreditation Page Fix

## 📋 Problem Analysis

### Current State
| Item | Status |
|------|--------|
| **PartnerLogo Model** | Has `type` field with only 2 options: `alumni`, `accreditation` |
| **Filament Resource** | Simple dropdown, no tabs |
| **Accreditation Page** | Hardcoded data, not connected to Filament |
| **Homepage** | Uses `alumni` and `accreditation` types |

### Required State
| Item | Status |
|------|--------|
| **PartnerLogo Model** | 4 types: `alumni`, `accreditation`, `recognition`, `award` |
| **Filament Resource** | Tab system for each type |
| **Accreditation Page** | Connected to Filament, dynamic data |
| **Homepage** | Uses `accreditation` + `recognition` types |

---

## 🎯 Solution Design

### Type System Design

```
PartnerLogo Types:
├── alumni        → Homepage Alumni section
├── accreditation → Homepage + Accreditation page (Partnerships)
├── recognition   → Homepage + Accreditation page (Partnerships)
└── award         → Accreditation page (Awards & Recognition)
```

### Page Display Logic

| Page | Section | Types Used |
|------|---------|------------|
| **Homepage** | "Accreditations & Recognition" | `accreditation` + `recognition` |
| **Accreditation** | "Accreditations & Partnerships" | `accreditation` + `recognition` |
| **Accreditation** | "Awards & Recognition" | `award` + `recognition` |
| **Our Story** | Alumni Slider | `alumni` |

---

## 📋 Implementation Plan

### Phase 1: Database Migration
- Add `award` and `recognition` to type options
- No schema change needed (type is string field)

### Phase 2: Update Filament Resource
- Add tab system for each type
- Add filters by type
- Improve form with better UX

### Phase 3: Update Controllers
- Update PageController to query new types
- Create AccreditationController for accreditation page

### Phase 4: Update Accreditation Page
- Connect to database
- Pull data by type
- Display in proper sections

---

## 📁 Files to Modify

| File | Changes |
|------|---------|
| `app/Filament/Resources/PartnerLogoResource.php` | Add tabs, update type options |
| `app/Http/Controllers/PageController.php` | Update queries for new types |
| `app/Http/Controllers/AccreditationController.php` | New controller for accreditation page |
| `resources/views/pages/accreditations.blade.php` | Connect to database |
| `routes/web.php` | Update accreditation route |

---

*Plan created: 2026-08-03*
