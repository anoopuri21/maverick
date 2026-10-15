# Runbook — Doctorate, Diploma and Bachelors catalogue seeding

**Status (2026-10-15):** 67 approved catalogue pages: 13 Doctorate + 49 Diploma
+ **five source-verified GAU BSc**. Eleven listed subjects remain unseeded:
**seven earlier Doctorate** and **four requested Bachelors** (Research Incomplete).
The repository also has 59 legacy programme seeds, giving **126 seed definitions**
(125 listing subjects mapped, one of them provisional, plus an extra legacy MBA).
These are code counts, **not observed staging DB counts**. No PHP, Composer, vendor
folder or staging DB is available here; the operator runs the seed on staging.
**2026-10-15 copy refresh:** the existing-only class now updates all 67 catalogue
rows **and the 55 in-scope Master/BBA legacy rows if already present**. The
extra UWS MBA and UCA Global MBA are excluded entirely; the two older GAU BScs
are also outside this refresh. Do not use the full bootstrap to update copy.

---

## Files

| File | Role |
|---|---|
| `output/programs/**/*.md` | **Single source of truth** for 67 approved catalogue pages |
| `tools/build_catalog_data.py` | Markdown → PHP data generator for three categories |
| `database/seeders/data/doctorate_programs.php` | **GENERATED** — never hand-edit |
| `database/seeders/data/diploma_programs.php` | **GENERATED** — never hand-edit |
| `database/seeders/data/bachelors_programs.php` | **GENERATED** — five inactive GAU BSc rows only |
| `tools/validate_catalog_data.py` | Offline pre-flight; fails for missing/empty file or active new row |
| `tools/qa_gate.py` · `tools/dedupe_scan.py` | Content gates (style, length, uniqueness) |
| `database/seeders/BachelorsProgramsSeeder.php` | Five GAU BSc rows only, existing references only |
| `database/seeders/ExistingCatalogProgramsSeeder.php` | **Recommended for the one-command copy refresh**: 67 catalogue + up to 55 *existing* Master/BBA rows; excludes two unapproved MBA rows |
| `database/seeders/CatalogProgramsSeeder.php` | Shared logic; legacy bootstrap **can create** Doctorate/Diploma references |
| `database/seeders/DatabaseSeeder.php` | Full bootstrap includes legacy seeders; **do not run for this rollout** |

**Flow:** content edit → `qa_gate` → `dedupe_scan` → `build` → `validate` → `seed`.
Beech ka koi step skip mat karo.

---

## Kya seed hota hai

| | Count |
|---|---|
| Bachelors-only command | **5** GAU BSc rows; no legacy rows touched |
| Existing-only copy refresh | **67** catalogue rows (13 Doctorate + 49 Diploma + 5 BSc) + up to **55 existing** Master/BBA rows (48 Master + 7 BBA); **2 extra MBAs excluded** |
| Required pre-existing categories | Five-only: `bachelors`; copy refresh: `bachelors`, `doctorate`, `diploma`; existing legacy rows retain their category |
| Required pre-existing partners | Five-only: GAU; copy refresh: GAU, Rushford, Gatehouse Awards, Qualifi; existing legacy rows retain their partner |
| Related data | Catalogue: programme, SEO and numbered FAQs; present legacy: copy and SEO only (no legacy FAQ creation) |

**Neither safe command creates a category, university/partner, or table.** The
copy-refresh command never inserts missing Master/BBA rows; it reports their
slugs. Missing *catalogue* references, incomplete data, ambiguous FAQs, or a
present row linked to the wrong category/partner **or title/level identity**
cause an exception and a full transaction rollback. Never silently map a
programme to another award/provider.
The refresh does replace authored copy, meta title/description and catalogue
FAQ question/answer text, so **back up and compare admin-edited copy on staging**.
It preserves programme activation, featured status, ordering, identities, media,
testimonials/reviews, SEO canonical URLs and existing FAQ activation.
`CatalogProgramsSeeder` still supports legacy first-time bootstrapping for the
old Doctorate/Diploma cohort and **may create references**; do not run it or
`DatabaseSeeder` for an existing-only rollout. The Bachelor file excludes UWS.

---

## Commands — deploy, check, then seed on STAGING

1. Deploy the reviewed branch **including** the updated existing seeder and
   current catalogue/Master/BBA data. Use the Laravel project directory on the
   server, with migrations and `vendor/` installed. Do not run bare
   `php artisan db:seed` (full `DatabaseSeeder`) for this copy refresh.
2. Before deploying, run the content and data checks on the source tree:

   ```bash
   python3 tools/qa_gate.py
   python3 tools/dedupe_scan.py           # must show 0 STYLISTIC
   python3 tools/build_catalog_data.py
   python3 tools/validate_catalog_data.py # must PASS; DB is checked separately
   python3 -m unittest discover -s tools/tests -v
   ```

3. Back up staging using its DB engine's established procedure and record the
   before counts. If the server has PHP, lint the changed seeders first:

   ```bash
   php -l database/seeders/CatalogProgramsSeeder.php
   php -l database/seeders/BachelorsProgramsSeeder.php
   php -l database/seeders/ExistingCatalogProgramsSeeder.php
   php -l database/seeders/data/bachelors_programs.php
   php -l database/seeders/data/master_programs.php
   php -l database/seeders/data/rushford_bba.php
   # STAGING/test environment only (not the live DB):
   php artisan test --filter=CatalogSeedingSafetyTest
   ```

4. **Alternative: five new Bachelors only** (skip this step for the requested
   one-command legacy + catalogue refresh; choose **either** step 4 **or** step 5,
   not both): check that both SQL queries return one row,
   with the expected existing IDs. If either is absent, **stop** — do not
   create or substitute a different partner/category.

   ```sql
   SELECT id, slug FROM program_categories WHERE slug = 'bachelors';
   SELECT id, slug FROM university_partners WHERE slug = 'girne-american-university';
   ```

   Then in the Laravel project directory run:

   ```bash
   php artisan db:seed --class=BachelorsProgramsSeeder
   # Run the SAME command again on staging; it should update, not duplicate.
   php artisan db:seed --class=BachelorsProgramsSeeder
   ```

5. **For the requested one-command copy refresh**, use the existing-only
   class after inspecting a staging DB backup and the current content. This
   works whether the 67 catalogue rows already exist or need first insertion.
   First check **all** of these slugs are already present (three category rows
   and four partner rows). Also snapshot the activation/featured flags, sort
   orders, SEO canonical URLs, catalogue FAQ IDs/activation, and both excluded
   MBA rows. Stop if any live row has the wrong category, partner, title or
   level for its source slug:

   ```sql
   SELECT id, slug FROM program_categories
   WHERE slug IN ('bachelors', 'doctorate', 'diploma');
   SELECT id, slug FROM university_partners
   WHERE slug IN ('girne-american-university', 'rushford-business-school',
                  'gatehouse-awards', 'qualifi');
   ```

   ```bash
   php artisan db:seed --class=ExistingCatalogProgramsSeeder
   # Repeat after checking counts to prove slug idempotency.
   ```

   The command inserts no legacy rows: it updates the **present** 48 in-scope
   Master + 7 BBA rows, reports any missing slugs, and skips `global-mba` (UCA)
   and `mba-in-international-business-university-of-the-west-of-scotland` (UWS)
   without reading or writing their DB records. If a catalogue reference or
   an existing row's identity fails preflight, the whole transaction rolls
   back; do not invent a replacement reference. Confirm the changed GAU BSc
   displays `4 years`, counts are stable on re-run and public cache is fresh.
6. **Production only after the staging test suite, before/after comparison,
   visual and editorial sign-off and a production DB backup**: deploy exactly
   the verified code/data and run **one** command in the project directory:

   ```bash
   php artisan db:seed --class=ExistingCatalogProgramsSeeder --force
   ```

   This is a deployment instruction, **not** evidence of a live run. If you
   only want the BSc subset later, use the Bachelors-only class with `--force`
   instead; it does not update legacy copy.

### After-seed checks (compare with your before snapshot)

```sql
SELECT c.slug, COUNT(*) FROM programs p
JOIN program_categories c ON c.id = p.program_category_id
GROUP BY c.slug;
-- Only if the 121 legacy + earlier catalogue rows were already in your DB:
-- doctorate 13, diploma 49, bachelors 14 (7 Rushford + 2 old GAU + 5 new GAU).
-- Overall 126 is an expectation, not an observed staging/production count.

SELECT p.slug, p.is_active, p.is_featured, c.slug AS category, u.slug AS partner
FROM programs p
JOIN program_categories c ON c.id = p.program_category_id
JOIN university_partners u ON u.id = p.university_partner_id
WHERE p.slug IN (
  'bsc-tourism-and-hospitality-management-girne-american-university',
  'bsc-international-business-management-girne-american-university',
  'bsc-marketing-girne-american-university',
  'bsc-economics-girne-american-university',
  'bsc-management-information-systems-girne-american-university'
);
-- Exactly 5 rows. Check existing activation/feature values against the
-- BEFORE snapshot (only newly inserted rows default to 0/0).
-- Check program_categories/university_partners counts and IDs have NOT changed.

SELECT slug, duration, short_description, is_active, is_featured, sort_order, updated_at
FROM programs
WHERE slug IN (
  'bsc-economics-girne-american-university',
  'global-mba',
  'mba-in-international-business-university-of-the-west-of-scotland'
);
-- Economics should have duration '4 years'; both excluded MBA rows must be
-- identical to their BEFORE snapshots, including SEO/FAQ records if present.
```

---

## Admin visibility is not public activation

All five new GAU BSc rows start **`is_active = false`**. Older Doctorate/Diploma
rows also started inactive on creation. Filament's Programs resource lists
inactive rows in the admin panel; the public listing/detail routes filter them
out (detail URL gives 404) until a separate approval and activation. The safe
Bachelors-only seeder does **not** touch older Doctorate/Diploma, GAU, BBA or
Master programmes. The copy-refresh class updates the 62 older catalogue rows
and up to 55 present in-scope legacy Master/BBA rows, but **not** the two older
GAU BScs or the excluded UWS/UCA MBAs. Neither class changes existing
`is_active`, `is_featured`, `sort_order`, `testimonials` or `reviews` values.

**Do not activate a whole category or level**: they include other programmes
that may not have received staging sign-off. Only after individual BSc visual
review, activate the five generated Bachelors by their unique slugs:

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

## Verification checklist

- [ ] Compare before/after: count increases only for missing catalogue rows, not legacy. If the full 121-row baseline was present and the five BSc rows were not, total rises to **126** (not 126 newly created). Actual staging/live counts may differ.
- [ ] Category and partner counts/IDs unchanged; no new categories or universities.
- [ ] Five GAU BSc slugs show `4 years`; newly inserted rows start inactive/non-featured with sorts **70, 71, 73, 76, 77**. For existing rows, compare activation, featured and admin sort settings with the before snapshot.
- [ ] For the copy refresh, inspect older 62 Doctorate/Diploma rows and present 48 Master + 7 BBA rows for revised copy and SEO. The two older GAU BScs, UWS extra MBA and UCA Global MBA must be unchanged. Missing legacy rows are logged, never inserted.
- [ ] Compare catalogue FAQs: same IDs/count where questions changed, activation unchanged; canonical URLs and manual media/testimonials/reviews unchanged. The seeder clears public programme caches on success.
- [ ] In Filament admin, inspect all five tabs, six highlights, seven snapshot items, six FAQs, SEO, structure and source-limit copy. Public routes must remain hidden while inactive.
- [ ] Economics discloses the missing Year 4 grid; Tourism, International Business Management and Economics show no invented career tags. Marketing/MIS career examples and hotel/internship opportunities are not job or placement guarantees.
- [ ] Confirm fees, entry, teaching location and scholarships with GAU; a blank fee field is not free study. After individual approval, activate only reviewed slugs and clear public listing cache.
- [ ] Repeat the SAME seed command; programme count must not rise. Confirm no new categories or partners.

---

## Rollback

**For a copy refresh of existing rows, restore the pre-run database backup or
specific audited row snapshots.** Do not use the delete examples below to
reverse a copy change: they remove entire programmes and their related SEO/FAQ
records. Stop and investigate a mismatch rather than running the full bootstrap.

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
5. If an approved new slug changes the fixed counts (13/49/5), update and
   review `expectedProgramCounts()` in the appropriate existing seeder before
   staging; a count mismatch deliberately stops the run.
6. Run the appropriate **existing-reference-only** seeder again on staging;
   a new catalogue slug creates a row, an existing slug updates its copy but
   retains activation and identity. A missing legacy slug is only reported.

For additional Bachelors, verify the **exact award and attributable curriculum**
first, then add a dossier and page through stations 01–06. Never change an
existing GAU/legacy slug or silently add a UWS award. Both safe seed commands
require existing categories/partners; only the separate legacy bootstrap
`CatalogProgramsSeeder` retains `firstOrCreate` for Doctorate/Diploma.

---

## Known gaps (seeding block nahi karte)

| Gap | Asar |
|---|---|
| GAP-SOURCE-RBS-01 | Koi accreditation claim nahi — `recognition` / `accreditation_groups` khaali |
| GAP-SOURCE-RBS-02 | Fees sirf labels, amounts nahi |
| GAP-SOURCE-QF-01 / 03 | Qualifi koi fee ya duration publish nahi karta — `duration` "Centre-set" hai |
| GAP-SOURCE-QF-04 | Career destination data kahin nahi; `careers` prose-only pages pe validator warning deta hai, error nahi |
| Tourism, International Business Management, Economics + older GAU/Qualifi | `careers` intentionally empty where no role titles are published; visible overview/snapshot/FAQ carry the evidence limit. Validator warns, not errors. |
| Economics | 4-year BSc but GAU course grid only shows Years 1–3. The gap is disclosed; no final-year modules invented. |
| New Bachelor image URL | Unset; detail view uses its existing fallback. Verify appearance on staging. |
| 11 tracker rows | 7 older + 4 requested Bachelors marked Research Incomplete; deliberately not seeded. See reconciliation dossier. |
