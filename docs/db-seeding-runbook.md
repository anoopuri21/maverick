# Runbook — Doctorate, Diploma and Bachelors catalogue seeding

**Status (2026-10-14):** 67 approved catalogue pages: the existing 62 Doctorate/Diploma
pages plus **five source-verified GAU BSc pages**. Four other requested Bachelors
remain Research Incomplete and are **not** in the generated data. No PHP, Composer
or vendor directory is available here; the operator runs the seed on staging.
The dependency-free validator has passed, with declared career/snapshot warnings.

---

## Files

| File | Role |
|---|---|
| `output/programs/**/*.md` | **Single source of truth** for 67 approved catalogue pages |
| `tools/build_catalog_data.py` | Markdown → PHP data generator for three categories |
| `database/seeders/data/doctorate_programs.php` | **GENERATED** — never hand-edit |
| `database/seeders/data/diploma_programs.php` | **GENERATED** — never hand-edit |
| `database/seeders/data/bachelors_programs.php` | **GENERATED** — five inactive GAU BSc rows only |
| `tools/validate_catalog_data.py` | Pre-flight checks without PHP |
| `tools/qa_gate.py` · `tools/dedupe_scan.py` | Content gates (style, length, uniqueness) |
| `database/seeders/CatalogProgramsSeeder.php` | Loads all three files, by unique programme slug |
| `database/seeders/DatabaseSeeder.php` | Registers legacy GAU/Rushford/masters seeders before this catalogue seeder |

**Flow:** content edit → `qa_gate` → `dedupe_scan` → `build` → `validate` → `seed`.
Beech ka koi step skip mat karo.

---

## Kya seed hota hai

| | Count |
|---|---|
| Programmes in this seeder | **67** — 13 Doctorate + 49 Diploma + 5 BSc |
| Categories | `doctorate` and `diploma` use existing seeder logic; `bachelors` **must already exist** |
| Partners referenced | Rushford (8), existing GAU (10), Gatehouse (4), Qualifi (45); **no new Bachelor partner** |
| Levels present | BSc · DBA · PhD · Level 3 Diploma · Level 5 Extended Diploma · Level 7 Diploma · **Level 7 International Diploma** |
| Per programme | 1 Program row + 1 SeoMetadata row + FAQs; arrays from approved page fields |

Existing Doctorate/Diploma behaviour is unchanged. For `bachelors_programs.php`, the
catalogue seeder looks up `bachelors` and `girne-american-university` **without
creating either**; if either is absent it throws and rolls back that file's
transaction. `ProgramSeeder` creates them in the full `DatabaseSeeder` sequence.
The Bachelors data file does not declare UWS because no exact UWS award qualified.

---

## Commands

**Staging prerequisite:** the existing `bachelors` category and
`girne-american-university` partner must already be present. Before seeding,
check them without creating replacements:

```sql
SELECT slug FROM program_categories WHERE slug = 'bachelors';
SELECT slug FROM university_partners WHERE slug = 'girne-american-university';
-- Both queries must return one row. Stop if either is absent.
```

```bash
# 1. Content gates (exit 0 for both; expected QA 67/67, STYLISTIC 0)
python3 tools/qa_gate.py
python3 tools/dedupe_scan.py        # STYLISTIC duplicates 0 hone chahiye

# 2. Rebuild three generated files from approved markdown
python3 tools/build_catalog_data.py

# 3. Pre-flight (exit 0 = safe to attempt staging; warnings are disclosed gaps)
python3 tools/validate_catalog_data.py

# 4. Take an environment-appropriate database backup before any seed
mysqldump -u USER -p DBNAME > backup-$(date +%F-%H%M).sql

# 5. On STAGING, after checking the category/partner prerequisites above:
php artisan db:seed --class=CatalogProgramsSeeder

# 6. Idempotency: run again and check no programme count rises
php artisan db:seed --class=CatalogProgramsSeeder
#    Expected: the five new rows say "Updated:" on the second run.

# 7. Production ONLY after staging visual and editorial sign-off:
php artisan db:seed --class=CatalogProgramsSeeder --force
```

### Row-count check (step 5 ke pehle aur baad)
```sql
SELECT c.slug, COUNT(*) FROM programs p
JOIN program_categories c ON c.id = p.program_category_id
GROUP BY c.slug;
-- Expected when legacy seeders were already run: doctorate 13, diploma 49,
-- bachelors 14 (7 Rushford BBA + 2 existing GAU BSc + 5 new GAU BSc).
-- Total across all categories: 126 if all 121 prior rows were present.
-- These are expectations, NOT a database observation; compare with your baseline.
```

---

## ⚠️ "0 programs" — activation step bhoolna mat

All five new GAU BSc rows are created with **`is_active = false`**. The earlier
62 catalogue rows also start inactive. Already-live GAU BSc Business Management,
BSc Psychology and Rushford BBAs are **not changed** by this seed. Inactive
rows are editable in Filament but absent from the public listing until approved.

`is_active` sirf **create** pe set hota hai — admin panel ka toggle re-seed me reset
**nahi** hota. Yahi `is_featured`, `testimonials`, `reviews` pe bhi lagu hai.

**For the old Doctorate/Diploma cohort only**, the previous category-based
activation example remains below. **Do not apply it to `bachelors`**; that
category already holds nine older rows, some active. Activate only the five
reviewed slugs after individual page checks (see the new block below).

```php
// php artisan tinker
$ids = App\Models\ProgramCategory::whereIn('slug', ['doctorate', 'diploma'])->pluck('id');
App\Models\Program::whereIn('program_category_id', $ids)->update(['is_active' => true]);
```

Level se filter karna ho toh **chhe ke chhe** values chahiye, warna rows chhoot jayengi:

```php
App\Models\Program::whereIn('level', [
    'DBA',
    'PhD',
    'Level 3 Diploma',
    'Level 5 Extended Diploma',
    'Level 7 Diploma',
    'Level 7 International Diploma',   // 2 rows — ye list se sabse zyada chhootta hai
])->update(['is_active' => true]);
```

**Only after individual BSc staging review**, activate the five generated
Bachelors by their unique slugs. Do not use `where('level', 'BSc')` or
`where('program_category_id', $bachelorsId)` — those would also select the
older GAU Bachelor rows:

```php
// php artisan tinker — inspect the five slugs and their university first
$bscData = require database_path('seeders/data/bachelors_programs.php');
$bscSlugs = collect($bscData['programs'])->pluck('slug')->all();
App\Models\Program::whereIn('slug', $bscSlugs)->whereHas(
    'universityPartner', fn ($q) => $q->where('slug', 'girne-american-university')
)->update(['is_active' => true]);
```

Activation ke baad **public cache clear karna zaroori hai**, warna listing purani rahegi:

```bash
php artisan cache:forget programs.listing.v2    # PublicContentCache key, TTL 86400
php artisan cache:clear                          # ya poora
```

---

## Verification checklist (step 5 ke baad)

- [ ] All previously seeded rows retained; if full baseline was present, total rises from **121 to 126** (not 126 newly created).
- [ ] Category `bachelors` already exists; GAU partner ID stays the same; **no new partner/category** appears for this batch.
- [ ] Five new GAU BSc slugs, sorts **70, 71, 73, 76, 77**, have `is_active = false`; existing BSc Business Management, BSc Psychology and seven Rushford BBAs are untouched.
- [ ] Open each new page in admin and inspect all five tabs: six highlights, seven snapshot items, 6 FAQs, SEO title/description, structure stages and working source-limit copy.
- [ ] Economics visibly discloses the missing Year 4 course grid; Tourism, International Business Management and Economics do **not** show invented career tags.
- [ ] Marketing and MIS career chips are described as **examples, not guaranteed jobs**; hotel training/internships are not promised placements.
- [ ] Current fees, entry, teaching location and any scholarships are confirmed by GAU separately; no blank fee field is treated as free study.
- [ ] After approval of each intended page, targeted activation plus cache clear makes only those five publicly visible. Verify `/programs/{new-bsc-slug}` and mobile/desktop layout.
- [ ] Second seed run leaves row count unchanged; no accidental update to the legacy master/Rushford/GAU pages.

---

## Rollback

**For only the five new Bachelors**, deactivate their slugs first and clear the
listing cache. If the operator must remove them after reviewing dependent data,
use this targeted set, never a BSc level/category sweep:

```php
// php artisan tinker
$bscData = require database_path('seeders/data/bachelors_programs.php');
$bscSlugs = collect($bscData['programs'])->pluck('slug')->all();
App\Models\Program::whereIn('slug', $bscSlugs)->each(function ($p) {
    $p->faqs()->delete();
    $p->seo()->delete();
    $p->delete();
});
```

The following **older example deletes the 62 Doctorate/Diploma rows**. Do not
use it as the rollback for this Bachelor batch:

```php
// php artisan tinker
$slugs = collect([
    require database_path('seeders/data/doctorate_programs.php'),
    require database_path('seeders/data/diploma_programs.php'),
])->flatMap(fn ($file) => collect($file['programs'])->pluck('slug'));

App\Models\Program::whereIn('slug', $slugs)->each(function ($p) {
    $p->faqs()->delete();
    $p->seo()->delete();
    $p->delete();
});
```

Neither rollback deletes the existing `bachelors` category nor the GAU partner.
Clear `programs.listing.v2` afterwards. Re-seeding recreates deleted slugs
inactive; if permanently withdrawing the batch, revert the deployment of
the generated Bachelors file and seeder wiring rather than hand-editing data.

---

## Naya ya updated content add karne ka tareeka

1. Content file `output/programs/{category}/{university-slug}/{slug}.md` pe daalo
2. `python3 tools/qa_gate.py` aur `python3 tools/dedupe_scan.py`
3. `python3 tools/build_catalog_data.py`
4. `python3 tools/validate_catalog_data.py`
5. Seeder dobara chalao — purane rows update, naye create

For additional Bachelors, verify the **exact award and attributable curriculum**
first, then add a dossier and page through stations 01–06. Never change an
existing GAU/legacy slug or silently add a UWS award. The Bachelors seeder
requires an existing partner and category; other cohorts retain their prior
`firstOrCreate` behaviour.

---

## Known gaps (seeding block nahi karte)

| Gap | Asar |
|---|---|
| GAP-SOURCE-RBS-01 | Koi accreditation claim nahi — `recognition` / `accreditation_groups` khaali |
| GAP-SOURCE-RBS-02 | Fees sirf labels, amounts nahi |
| GAP-SOURCE-QF-01 / 03 | Qualifi koi fee ya duration publish nahi karta — `duration` "Centre-set" hai |
| GAP-SOURCE-QF-04 | Career destination data kahin nahi; `careers` prose-only pages pe validator warning deta hai, error nahi |
| Tourism, International Business Management, Economics + older GAU/Qualifi | `careers` intentionally empty where no role titles are published; visible overview/snapshot/FAQ carry the evidence limit. Validator warns, not errors. |
| Economics | Four-year BSc but GAU course grid only shows Years 1–3. The gap is disclosed; no final-year modules invented. |
| New Bachelor image URL | Unset; detail view uses its existing fallback. Verify appearance on staging. |
| 11 tracker rows | 7 older + 4 requested Bachelors marked Research Incomplete; deliberately not seeded. See reconciliation dossier. |
