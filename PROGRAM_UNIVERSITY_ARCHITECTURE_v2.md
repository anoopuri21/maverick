# Program ⇄ University — SIMPLIFIED Architecture (v2)

Branch: `dev/overall-development`
After your clarification: **1 university = 1 program record.** No many-to-many pivot needed. Simple `belongsTo`.

---

## 1. The simplification (why it's easy now)

**Rule:** Har program ek specific university ka offering hai. Ek `programs` row = ek university ka ek program.

- 2 universities ke programs **alag hain** → 2 alag `programs` rows (each with its own `university_partner_id`).
- 2 universities ke program ka **naam same** hai → dono alag rows, but slugs unique banane ke liye university initials append (`-gau`, `-xyz`).

**No pivot table. No many-to-many.** Sirf ek FK.

---

## 2. Data model (simple)

```
program_categories (id, name, slug, icon, is_active)
        │ 1
        │
        N
programs (id, program_category_id FK, university_partner_id FK ← NEW,
          title, slug [unique], duration, level,
          short_description, description, image_url, brochure_url,
          is_featured, is_active, sort_order,
          ...13 JSON content columns)
        │ N
        │
        1
university_partners (id, name, slug/initials, country, city, logo_url,
                     website_url, description, recognition, is_hub, is_active)
```

### Relationships
```php
// Program
public function programCategory(): BelongsTo
public function universityPartner(): BelongsTo(UniversityPartner::class)  // NEW

// UniversityPartner
public function programs(): HasMany(Program::class)   // NEW (reverse)

// ProgramCategory
public function programs(): HasMany
```

### Dropped
- `programs.partner_university` (string) — **drop** (ab FK se).
- `programs.university` (JSON) — **drop** (ab `university_partners` se).
- `university_partners.programs` (freeform JSON) — **drop**.
- No pivot table.

---

## 3. URL / slug (the only "special" part)

Program slug must be unique. If a program name would collide with another university's program:

**Simple rule:** Store `slug` = base program slug. When saving, auto-unique:
- First university: `bsc-business-management`
- Second university (same base): `bsc-business-management-gau`  ← append university initials

URL = `/programs/{slug}` (unchanged route). The slug already encodes the university.

**Controller:** `Program::whereSlug($slug)->with('universityPartner')->firstOrFail()` — detail page reads `$program->universityPartner`.

---

## 4. Why this is "simple" (what you asked)

**Enter once, reuse everywhere:**
- UniversityPartner admin: name, country, logo, description — **ek baar**.
- Program admin: `Select` dropdown "University Partner" → pick one. **No typing the name.**
- Navbar, detail, listing → `program->universityPartner` / `university->programs` derive. No hardcode.

---

## 5. Admin UX (Filament)

### ProgramResource
```php
// Replace partner_university TextInput + university JSON repeater with:
Select::make('university_partner_id')
    ->relationship('universityPartner', 'name')
    ->searchable()
    ->preload()
    ->required()
    ->label('University Partner');
```

### UniversityPartnerResource
- Remove the freeform `programs` Repeater.
- (Optional) A `RelationManager` "Programs" tab showing `hasMany` programs of that university.

---

## 6. Navbar dynamic (single source)

```php
// App\Support\NavMenu::programs()  → cached
[
  ['category'=>'Diplomas','slug'=>'diplomas','icon'=>'...','programs'=>[
     ['title'=>'Diploma in Business Management','slug'=>'...','university'=>'Dubai University'],
  ]],
  ...
]
```
Both desktop mega-menu + mobile drawer iterate this. Removes ~80 hardcoded links. University name shown per program automatically.

---

## 7. Migration & seeding

1. `university_partners` → ensure `slug`/`initials` column.
2. `programs` → add `university_partner_id` FK (nullable → then backfill).
3. Backfill:
   - For each program, take old `partner_university` string → find/create `UniversityPartner` → set `university_partner_id`.
   - Merge old `university` JSON (description) into partner.
4. Enforce unique slugs (append initials where collision).
5. Drop `partner_university`, `university`, `university_partners.programs`.
6. Seeder: categories + partners + programs (each with its university).

---

## 8. Phases

| Phase | Work |
|-------|------|
| **P1** | Add `university_partner_id` FK to programs + relations on both models |
| **P2** | Data migration (string/JSON → FK), slug-uniquify, drop old columns, seeder |
| **P3** | Admin dropdowns (Program select university; University list programs) |
| **P4** | Dynamic navbar (single source, remove hardcoded) |
| **P5** | Detail page reads `universityPartner` (already works — minor) |
| **P6** | QA: 404, overflow, tests |

---

## 9. Confirmations
1. ✅ URL = `/programs/{slug}` where slug may include `-initials` on collision — correct?
2. **1 program = 1 university** always? (No program with zero universities? Probably always required.)
3. A university can have **many** programs (hasMany) — correct? (e.g. GAU offers BSc + MBA + DBA)
4. Drop old `partner_university` + `university` JSON + freeform `programs` after migrate — OK?
5. Scope: start P1–P4 now, P5 detail follow-up? Or all?
