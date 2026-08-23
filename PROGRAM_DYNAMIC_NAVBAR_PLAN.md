# Making Programs Dynamic — Enterprise-Grade Plan

Branch: `dev/overall-development`
Goal: navbar programs become **dynamic** (admin-driven, from real data), and the **Program ⇄ UniversityPartner** relationship becomes a proper many-to-many model so a single program can be offered by multiple universities and shown correctly in navbar + detail.

---

## PART A — What the 2 current admin options do

### 1. `Program Categories` (ProgramCategoryResource)
- A simple taxonomy: `name`, `slug`, `icon`, `description`, `is_active`.
- One `ProgramCategory` has **many** `Program`s (`hasMany`).
- Used to group programs (e.g. "Diplomas", "Bachelor's Degrees", "Master's", "Doctorate", "Executive", "Corporate", "Certifications").
- **Role:** navigation grouping / filter label. Purely a container.

### 2. `Programs` (ProgramResource)
- The actual program record: `title`, `slug`, `duration`, `level`, `short_description`, `description`, image, brochure, featured/active flags.
- **Belongs to ONE category** (`program_category_id`).
- Currently stores the university as:
  - `partner_university` — a **plain string** (single text field)
  - `university` — a **JSON array** (first row shown as "About the University" on detail)
- Rich JSON content per program (highlights, snapshot, benefits, structure, etc.)

### Current relationship (the core limitation)
```
ProgramCategory  1 ──── N   Program  (program_category_id)
Program  ── string: partner_university
Program  ── JSON:   university[]
UniversityPartner  (independent)  ── JSON: programs[] {name,url}   ← freeform, NOT linked
```
**There is NO relationship between Program and UniversityPartner.** They are two separate universes with duplicated/freeform data. This is the root of everything you described.

---

## PART B — Should Program & ProgramCategory stay separate?

**Yes, keep them separate** — this is correct and scalable:
- `ProgramCategory` = **taxonomy** (grouping/filter). One-to-many with Program. Perfectly fine.
- `Program` = the program **content/offering**.
- They should stay separate because a category is a stable label while programs change.

**BUT** — the real missing piece is a **first-class `UniversityPartner` entity** linked to programs, replacing the `partner_university` string and the freeform `programs` JSON on UniversityPartner.

---

## PART C — Recommended data model (enterprise-grade)

### New pivot: `program_university_partner`
```sql
program_university_partner (
  id,
  program_id          FK → programs,
  university_partner_id FK → university_partners,
  display_name        nullable  -- optional per-offering name override
  fee_basis           nullable  -- optional per-university fee note
  url_slug            nullable  -- optional per-offering URL
  sort_order,
  is_active,
  timestamps
)
```

### Relationships
```
ProgramCategory  1 ──── N   Program
Program  N ──── N   UniversityPartner   (through program_university_partner)
```

### Model changes
- **Program:** `belongsToMany(UniversityPartner::class)` (+ the existing `programCategory`, `seo`, `faqs`).
- **UniversityPartner:** `belongsToMany(Program::class)` — **remove** the old freeform `programs` JSON column (or keep deprecated for migration).
- **ProgramCategory:** unchanged.

---

## PART D — Solving your 4 scenarios

### Scenario 1: Multiple universities, same program
A single `Program` row links to N `UniversityPartner` via the pivot. No duplicate program rows needed.

### Scenario 2: Same program at universities in different countries
Same Program row, linked to a UAE university AND a UK university. Each university is its own `UniversityPartner` row (with country/city). Navbar shows the program once with both university names.

### Scenario 3: Program detail shows 1+ university partners
The detail page "About the University" section renders the linked `UniversityPartner`s from `program->universityPartners` (instead of the hardcoded string / single JSON row). Shows all linked partners.

### Scenario 4 (the tricky one): Navbar + detail per-university identity
Requirement: *navbar shows same program with both university names; clicking a specific university's program opens detail showing ONLY that university's program name.*

**How to model this cleanly:** The `display_name` + `url_slug` on the pivot enables **per-offering URLs**:
- Navbar renders one row per `(program × university)` offering: e.g. "BBA — University of London", "BBA — Dubai University".
- Each offering's `url_slug` → `/programs/{program-slug}` (default) OR a unique per-offering route `/programs/{program-slug}?u={partner-id}` or `/university/{partner-slug}/programs/{program-slug}`.
- On detail, resolve the `?u=` (or URL segment) → set the active university context → page shows that university's name/title, while the core program content stays shared.

**Recommended routing (cleanest for SEO):**
```
/programs/{program:slug}/{university:slug?}
```
- No partner segment → generic (or primary/first university).
- With partner segment → detail scoped to that university (title/name/branding from that partner; shared program content).

This keeps ONE program content row (single source of truth) while giving each university its own scoped detail page + navbar entry.

---

## PART E — Navbar dynamic (no more hardcoded ~80 links)

Replace the hardcoded mega-menu + mobile drawer with data-driven rendering:

1. **Top-level category tabs** → from `ProgramCategory` (name, slug, icon). E.g. Diplomas, Bachelor's, Master's, Doctorate, Executive, Corporate, Certifications. (Keep only active categories, sorted.)
2. **Program links per category** → `Program::where(category_id=..)->active()`.
3. **Each program entry shows its linked universities** (via pivot) as sub-labels/rows.
4. **"View all" links** → `route('programs.index', ['category' => $cat->slug])` (dynamic).

### Single source of truth
Build ONE partial that renders the program list (used by both desktop mega-menu and mobile drawer), fed by a controller/service method, e.g. `NavMenu::programs()` returning:
```php
[
  'categories' => [ {name, slug, icon, programs: [ {title, slug, universities:[{name,slug}] } ]} ],
]
```
Both desktop + mobile iterate this array → **eliminates the ~80 duplicated hardcoded URLs.**

---

## PART F — Admin UX (Filament)

### ProgramResource
- Replace `partner_university` text field with a proper **relationship field**:
  `BelongsToManyMultiSelect` / `CheckboxList` of `UniversityPartner` (linked via pivot).
- Keep `university` JSON only as the "About" long-form content (or migrate it into UniversityPartner.description and drop the JSON).

### UniversityPartnerResource
- Replace the freeform `programs` JSON repeater with a **relationship field** linking to Programs (reverse of above).
- Keep name, country, city, lat/lng, is_hub, logo, website, recognition.

### Pivot admin (optional advanced)
- A dedicated "Program × University" pivot resource for per-offering fields (display_name, url_slug, fee, sort, active).

---

## PART G — Data migration / seeding

- Create `program_university_partner` table.
- Migrate existing data:
  - For each Program with `partner_university` string → match/create a `UniversityPartner` and link.
  - For each UniversityPartner's freeform `programs[]` → match Programs by name/slug and link.
- Update ProgramSeeder to seed categories + programs + university partners + pivot links.

---

## PART H — Implementation phases (recommended order)

| Phase | Work | Scope |
|-------|------|-------|
| **P1** | Pivot table + relations on Program & UniversityPartner | Backend schema |
| **P2** | Data migration (existing partner_university/JSON → pivot) + seeder | Data |
| **P3** | Update admin resources (Program ↔ University links) | Admin UX |
| **P4** | Dynamic navbar (category + program + university rows) from single source; remove hardcoded links | Navbar |
| **P5** | Detail page: render linked university partners; per-university route context | Detail page |
| **P6** | Verify: 404 check, no overflow, tests green | QA |

---

## Open decisions for you to confirm
1. **URL scheme:** per-university detail route as `/programs/{program-slug}/{university-slug}` (clean SEO) — OK? Or query param `?u=`?
2. **Default when no university specified** — show first/primary linked university, or generic?
3. **`university` JSON on Program** — migrate fully into `UniversityPartner.description` and drop, or keep as supplemental "About" long-form?
4. **Pivot per-offering fields** needed now (display_name, url_slug, fee) or keep minimal (just the link) first?
5. **Scope of this build:** full P1–P6, or start with P1–P4 (backend + admin + dynamic navbar) and do detail-page per-university (P5) in a follow-up?
