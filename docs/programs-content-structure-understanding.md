# Programs — Content Structure Understanding
**Maverick Business Academy London**
Prepared: 2026-10-05 · Status: research / understanding only (no new content created)

---

## 1. Tech stack & where program content lives

| Layer | Implementation |
|---|---|
| Framework | Laravel 12 (PHP), Blade templates |
| Admin / CMS | Filament v3 admin panel (`/admin`) |
| DB | Configured as `sqlite` in `.env.example` (`DB_CONNECTION=sqlite`) |
| Content model | **Relational DB rows + JSON columns** — not markdown, not an external CMS |
| Seed/import path | PHP seeders in `database/seeders/`, bulk data in `database/seeders/data/*.php` (heredoc JSON) |

There is **no markdown/MDX content folder** for programs. Every program is one row in the `programs` table. Long-form repeatable blocks (modules, careers, benefits…) are stored as **JSON arrays on that same row** — deliberately, "no extra tables" (see migration `2026_08_14_054038_add_program_detail_content_to_programs_table.php`).

### Key files
```
app/Models/Program.php                                  # fillable, casts, accessors, section-nav logic
app/Models/ProgramCategory.php                          # category (Bachelors / MBA / MSc / ...)
app/Models/UniversityPartner.php                        # awarding university (single source of truth)
app/Filament/Resources/ProgramResource.php              # THE content template (756 lines, admin form)
app/Http/Controllers/ProgramController.php              # index() listing, show() detail
resources/views/pages/programs/index.blade.php          # listing page (flat grid + category filter)
resources/views/pages/programs/detail.blade.php         # detail page (870 lines, ~16 sections)
database/migrations/2026_07_20_063844_create_programs_table.php
database/migrations/2026_08_14_054038_add_program_detail_content_to_programs_table.php
database/seeders/data/master_programs.php               # 50 master's programmes (bulk JSON)
database/seeders/BscPsychologyProgramSeeder.php         # richest single-programme example
docs/listing.pdf                                        # the full target programme catalogue
```

---

## 2. Data model

### `programs` table
**Scalar columns**
`id`, `program_category_id` (FK), `university_partner_id` (FK), `title`, `slug` (unique),
`duration`, `level`, `short_description` (text), `description` (longText/HTML),
`image_url`, `image_url_asset_id`, `brochure_url`, `gcc_heading`,
`is_featured` (bool), `is_active` (bool), `sort_order` (int), timestamps.

**JSON columns** (all cast to `array`)
`highlights`, `recognition`, `snapshot`, `benefits`, `learning`, `careers`, `structure`,
`support`, `gcc_reasons`, `accreditation_groups`, `testimonials`, `fees`, `reviews`.

**Relations**
- `programCategory` → belongsTo `ProgramCategory`
- `universityPartner` → belongsTo `UniversityPartner` (**1 program = 1 university**)
- `faqs` → morphMany `Faq` (`question`, `answer`, `sort_order`, `is_active`)
- `seo` → morphOne `SeoMetadata` (meta title/description/canonical/OG/Twitter/schema)

### `program_categories` table
`name`, `slug`, `icon`, `description`, `is_active`, `sort_order`.
Auto-fallback: a program saved without a category is forced into **"Uncategorized"** (`Program::booted()`).

### `university_partners` table
`name`, `slug`, `country`, `country_code`, `logo_url`, `website_url`, `description`,
`campus_image`, `recognition_logos`, `sort_order`, `is_active`.
→ University name, blurb, image and **accreditation logos are NOT written on the program**; they are inherited from the linked partner (`Program::getUniversityObjectAttribute()`). A merge migration (`2026_09_22_000002`) moved program-level accreditation groups into the university record.

### Important behaviours
- **Auto-slug** from title (`Str::slug`), unique-enforced via `EnsuresUniqueSlug`.
- **Section auto-hide**: `Program::getSectionNavAttribute()` builds the left scroll-spy; a section renders *only if its data is non-empty*. So leaving a block empty silently removes it from the public page.
- **Listing cache**: `PublicContentCache::PROGRAMS_LISTING` — listing is cached, must be busted after bulk inserts.
- Listing only shows programs with `is_active = true` **and** a non-empty public slug (`hasPublicSlug()` scope).

---

## 3. Sample live program (inspected end-to-end)

**Primary sample:** `BSc in Psychology` — slug `bsc-psychology`, category `bachelors`, university `Girne American University` (`database/seeders/BscPsychologyProgramSeeder.php`, rendered by `detail.blade.php`).
**Cross-checked against:** `MBA in Business Management` and the other 49 rows in `database/seeders/data/master_programs.php`.

### Actual fields/sections present on the live page (page order)

| # | Section (public) | Field(s) | Notes |
|---|---|---|---|
| 1 | Hero | `title`, `level` (badge), `duration` (meta), `short_description`, `image_url`, `brochure_url`, `highlights` | highlights render as a tick list |
| 2 | Sticky bar / scroll-spy | derived from `section_nav` | auto |
| 3 | Everything at a Glance | `snapshot` (bento tiles) | first 6 also in hero card |
| 4 | About this Programme (Overview) | `description` (rich HTML) | |
| 5 | Why Choose This Programme | `benefits` [icon, title, desc] | |
| 6 | What You'll Learn | `learning` [item] | |
| 7 | Where This Degree Can Take You (Careers) | `careers` [title] | tag cloud |
| 8 | Your Journey / Programme Structure | `structure` [stage → modules → key points] | accordion |
| 9 | About the University | from `universityPartner` (name, description, image) | **not editable on program** |
| 10 | Accreditation & Recognition | `universityPartner.recognition_logos` | **not editable on program** |
| 11 | Why Study Through Maverick | `support` [item] | |
| 12 | Why GCC Professionals Choose This Course | `gcc_heading` + `gcc_reasons` [icon, title, text] | |
| 13 | Student Success Stories | `testimonials` [name, role, country, category, video, thumb] | YouTube thumb auto |
| 14 | Student Reviews | `reviews` [name, avatar, rating, review] | Google-style cards |
| 15 | Fees & Scholarships | `fees` [title] — **labels only, no numbers** | chips link to enquiry |
| 16 | FAQ | `faqs` relation [question, answer] | |
| 17 | Enquiry form + Final CTA | global chrome settings | fixed |

### Fields that do **NOT** exist (don't assume them)
❌ Entry Requirements · ❌ Intake dates / application deadline · ❌ Numeric fee/price field ·
❌ Credits/ECTS as its own column (only inside `snapshot`) · ❌ Accreditation on the program row ·
❌ Campus/location field · ❌ Language of instruction · ❌ Faculty list.

Entry requirements, credits, assessment, study mode etc. are all expressed as **`snapshot` label/value rows** — that's the escape hatch the current content uses.

---

## 4. `docs/listing.pdf` — format analysis

- **5 pages, plain tabular Word-style export.** No design, no descriptions — purely a catalogue of titles.
- **Structure = 2-level grouping, NOT a flat list:**
  `University (numbered 1–5)` → `Award-type block (heading)` → `numbered table: "No. | Course / Specialization"`.
- Universities are numbered headings with country: e.g. `1. Rushford Business School (RBS), Switzerland`, `2. Girne American University (GAU), North Cyprus`, `3. University of the West of Scotland, UK (UWS)`, `4. University for the Creative Arts, UK (UCA)`, `5. University of Wolverhampton, UK (UOW)`.
- **Exception:** the Diploma blocks at the end (Gatehouse + Qualifi) are listed **without a parent university** — they sit under awarding-body headings only (`Gatehouse Level 7 Diploma Programs`, `Level 3 / Level 5 / Level 7 Qualifi Diploma Specializations`). ⚠️ This is a mapping gap: the DB requires a `university_partner_id`-style owner, and Gatehouse / Qualifi are not yet `UniversityPartner` records.
- **Doctorate exception:** listed as `Doctoral Research Topic Specializations (PhD/DBA/EPD)` under Rushford — i.e. **13 research topics × 3 award types**, not 13 finished programme titles. Needs a product decision (see §6).
- Only the title string exists per row — **no duration, no overview, no fees, no modules**. Everything else in the template has to be written.

### Counts in the PDF

| Group | Parent | Rows |
|---|---|---|
| BBA Undergraduate | Rushford (RBS) | 7 |
| MBA Postgraduate | Rushford | 12 |
| MSc Postgraduate | Rushford | 9 |
| **Doctoral topics (PhD/DBA/EPD)** | Rushford | **13 topics** |
| BSc Programs | GAU | 10 |
| MBA Programs | GAU | 6 |
| Executive MBA | GAU | 14 |
| BA (Hons) Global Business | UWS | 1 |
| Global MBA (+ Rushford) | UCA | 1 |
| Master of Laws | UOW | 1 |
| **Gatehouse Level 7 Diploma** | Gatehouse | **4** |
| **Qualifi Level 3 Diploma** | Qualifi | **8** |
| **Qualifi Level 5 Extended Diploma** | Qualifi | **14** |
| **Qualifi Level 7 Diploma** | Qualifi | **23** |
| **TOTAL** | | **123 rows** |

**Doctorate ≈ 13** (→ up to **39** programme pages if PhD/DBA/EPD are split).
**Diploma = 49** (4 Gatehouse + 45 Qualifi across L3/L5/L7).
**Doctorate + Diploma together ≈ 62 rows (up to 88 pages if doctorates are split by award).**

---

## 5. What is already in the system vs the PDF

| Category slug | Source | Programs |
|---|---|---|
| `bachelors` | ProgramSeeder, BscPsychology, RushfordBba | 3 |
| `mba` | master_programs.php | 20 |
| `executive-mba` | master_programs.php | 16 |
| `msc` | master_programs.php | 13 |
| `llm` | master_programs.php | 1 |
| **Total seeded** | | **≈53** |

**Gaps vs listing.pdf:**
- ❌ No `doctorate` (or `phd` / `dba` / `epd`) ProgramCategory exists.
- ❌ No `diploma` ProgramCategory exists (and no Level 3/5/7 sub-grouping concept).
- ❌ `Gatehouse` and `Qualifi` do not exist as `UniversityPartner` records — only GAU, Rushford, UCA, UWS, UOW (5).
- ❌ Listing page has only a **flat grid + single-level category filter** — no university grouping, no level sub-filter. 53 cards already; +62 doctorate/diploma cards will make it ~115 and the single-row filter bar will need rethinking.

---

## 6. Open questions to decide before any content is written

1. **Doctorate granularity** — one page per *topic* (13), or topic × award type (39: PhD/DBA/EPD)? Or 3 parent programme pages with the 13 topics as a specialisations list?
2. **Diploma owner** — create `Gatehouse Qualifications` and `Qualifi` as `UniversityPartner` rows (they're awarding bodies, not universities), or make `university_partner_id` nullable for diplomas?
3. **Category taxonomy** — one `Diploma` category with level inside the `level` field, or separate `Level 3 / Level 5 / Level 7 Diploma` categories? (Categories are currently a flat, single-level filter.)
4. **Listing UX** — does the listing page need a second filter dimension (university / award level) before ~115 cards land on it?
5. **Fees** — the `fees` block is labels-only today (no amounts). Keep that policy for doctorate/diploma?
6. **Per-page volume** — a full program page is ~700–900 words of original copy (see template). 62 pages ≈ 45k–55k words. Confirm scope/phasing.

---

## 7. Deliverables from this task

- `docs/program-content-template.md` — the field-by-field writing template (names, word counts, format).
- `docs/programs-content-structure-understanding.md` — this document.

*No program content has been created or modified.*
