# Runbook — Doctorate & Diploma programmes ko DB me seed karna

**Status:** code ready · **62 programmes** generated in the data files · sandbox me PHP
nahi hai, isliye seeder **staging pe chalega**. Validator dependency-free hai aur yahan
pass ho chuka hai.

---

## Files

| File | Role |
|---|---|
| `output/programs/**/*.md` | **Single source of truth.** 62 approved content pages |
| `tools/build_catalog_data.py` | Markdown → PHP data file generator (multi-category) |
| `database/seeders/data/doctorate_programs.php` | **GENERATED** — hand-edit mat karo |
| `database/seeders/data/diploma_programs.php` | **GENERATED** — hand-edit mat karo |
| `tools/validate_catalog_data.py` | Pre-flight checks, PHP ke bina |
| `tools/qa_gate.py` · `tools/dedupe_scan.py` | Content gates (style, length, uniqueness) |
| `database/seeders/CatalogProgramsSeeder.php` | The seeder — dono data files load karta hai |
| `database/seeders/DatabaseSeeder.php` | Registered after `MasterProgramsSeeder` |

**Flow:** content edit → `qa_gate` → `dedupe_scan` → `build` → `validate` → `seed`.
Beech ka koi step skip mat karo.

---

## Kya seed hota hai

| | Count |
|---|---|
| Programmes | **62** — 13 doctorate + 49 diploma |
| Categories | 2 — `doctorate`, `diploma` (`firstOrCreate`) |
| University partners | 4 — `rushford-business-school` (8), `girne-american-university` (5), `gatehouse-awards` (4), `qualifi` (45) |
| Levels present | DBA · PhD · Level 3 Diploma · Level 5 Extended Diploma · Level 7 Diploma · **Level 7 International Diploma** |
| Per programme | 1 Program row (13 JSON columns) + 1 SeoMetadata row + 5–6 Faq rows |

Qualifi aur Gatehouse **`UniversityPartner` records** hain — koi naya `AwardingBody`
table nahi banaya gaya. Rushford ke 5 DBAs wale pattern se bilkul consistent.

---

## Commands

```bash
# 1. Content gates (exit 0 dono me zaroori)
python3 tools/qa_gate.py
python3 tools/dedupe_scan.py        # STYLISTIC duplicates 0 hone chahiye

# 2. Rebuild the data files from approved content
python3 tools/build_catalog_data.py

# 3. Pre-flight (exit 0 = safe, 1 = do not seed)
python3 tools/validate_catalog_data.py

# 4. Backup FIRST — non-negotiable on production
mysqldump -u USER -p DBNAME > backup-$(date +%F-%H%M).sql

# 5. Seed (staging)
php artisan db:seed --class=CatalogProgramsSeeder

# 6. Idempotency proof — run it a SECOND time
php artisan db:seed --class=CatalogProgramsSeeder
#    Expected: har line "Updated:", koi "Created:" nahi. Row count same.

# 7. Production
php artisan db:seed --class=CatalogProgramsSeeder --force
```

### Row-count check (step 5 ke pehle aur baad)
```sql
SELECT c.slug, COUNT(*) FROM programs p
JOIN program_categories c ON c.id = p.program_category_id
GROUP BY c.slug;
-- expected after seeding: doctorate 13, diploma 49 (+ jo master seeder ne daala tha)
```

---

## ⚠️ "0 programs" — activation step bhoolna mat

Saare 62 rows **`is_active = false`** ke saath create hote hain. Ye deliberate hai:
visual QA ke baad hi public karo. Agar seeding ke baad frontend/admin listing **0
programmes** dikhaye, wajah lagbhag hamesha yahi hai.

`is_active` sirf **create** pe set hota hai — admin panel ka toggle re-seed me reset
**nahi** hota. Yahi `is_featured`, `testimonials`, `reviews` pe bhi lagu hai.

**Activate karne ka sahi tareeka — category se, level se nahi:**

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

Activation ke baad **public cache clear karna zaroori hai**, warna listing purani rahegi:

```bash
php artisan cache:forget programs.listing.v2    # PublicContentCache key, TTL 86400
php artisan cache:clear                          # ya poora
```

---

## Verification checklist (step 5 ke baad)

- [ ] Admin → Programs me 62 naye rows (13 doctorate + 49 diploma)
- [ ] Category filter me "Doctorate" aur "Diploma" dono options
- [ ] University filter me Qualifi, Gatehouse Awards, Rushford, Girne dikhe
- [ ] Ek programme kholo → saare 5 tabs bhare hue
- [ ] FAQs tab me entries hain (`MasterProgramsSeeder` ye nahi karta tha)
- [ ] SEO tab me meta title/description bhara hai
- [ ] Activation + cache clear ke baad frontend listing pe rows dikhe
- [ ] `/programs/level-7-diploma-in-law-qualifi` jaisa ek diploma page khule
- [ ] Second run pe row count same raha

---

## Rollback

Sab kuch slug se identify hota hai, aur dono data files me slugs maujood hain:

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

Categories aur university partners chhod do — harmless hain.

---

## Naya ya updated content add karne ka tareeka

1. Content file `output/programs/{category}/{university-slug}/{slug}.md` pe daalo
2. `python3 tools/qa_gate.py` aur `python3 tools/dedupe_scan.py`
3. `python3 tools/build_catalog_data.py`
4. `python3 tools/validate_catalog_data.py`
5. Seeder dobara chalao — purane rows update, naye create

Naya awarding body/university chahiye toh `tools/build_catalog_data.py` ke `BUILDS` me
uski entry add karo; seeder usko `firstOrCreate` kar lega.

---

## Known gaps (seeding block nahi karte)

| Gap | Asar |
|---|---|
| GAP-SOURCE-RBS-01 | Koi accreditation claim nahi — `recognition` / `accreditation_groups` khaali |
| GAP-SOURCE-RBS-02 | Fees sirf labels, amounts nahi |
| GAP-SOURCE-QF-01 / 03 | Qualifi koi fee ya duration publish nahi karta — `duration` "Centre-set" hai |
| GAP-SOURCE-QF-04 | Career destination data kahin nahi; `careers` prose-only pages pe validator warning deta hai, error nahi |
| 4 GAU + kuch Qualifi rows | `careers` list khaali (prose block use hua hai) — warning only |
| `image_url` | Set nahi — detail page default image pe fallback karega |
| 7 tracker rows | Research Incomplete — jaan-boojh kar seed nahi kiye gaye |
