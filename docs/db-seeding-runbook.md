# Runbook — Doctorate & Diploma programmes ko DB me seed karna

**Status:** code ready, 5 programmes loaded in the data file, **not yet run** (sandbox me PHP nahi hai)

---

## Files

| File | Role |
|---|---|
| `output/programs/doctorate/**/*.md` | **Single source of truth.** Approved content |
| `tools/build_catalog_data.py` | Markdown → PHP data file generator |
| `database/seeders/data/doctorate_programs.php` | **GENERATED** — hand-edit mat karo |
| `tools/validate_catalog_data.py` | Pre-flight checks, PHP ke bina |
| `database/seeders/CatalogProgramsSeeder.php` | The seeder |
| `database/seeders/DatabaseSeeder.php` | Registered after `MasterProgramsSeeder` |

**Flow:** content edit karo → `build` chalao → `validate` chalao → `seed` chalao. Beech ka koi step skip mat karo.

---

## Commands

```bash
# 1. Rebuild the data file from approved content
python3 tools/build_catalog_data.py

# 2. Pre-flight (exit 0 = safe, 1 = do not seed)
python3 tools/validate_catalog_data.py

# 3. Backup FIRST — non-negotiable on production
mysqldump -u USER -p DBNAME > backup-$(date +%F-%H%M).sql

# 4. Seed (staging)
php artisan db:seed --class=CatalogProgramsSeeder

# 5. Idempotency proof — run it a SECOND time
php artisan db:seed --class=CatalogProgramsSeeder
#    Expected: every line says "Updated:", none say "Created:".
#    Row count must not change.

# 6. Production
php artisan db:seed --class=CatalogProgramsSeeder --force
```

### Row-count check (before and after step 5)
```sql
SELECT c.slug, COUNT(*) FROM programs p
JOIN program_categories c ON c.id = p.program_category_id
GROUP BY c.slug;
```

---

## Kya expect karna hai (first run)

```
Loading doctorate_programs.php
  Created: DBA in Supply Chain Management
  Created: DBA in Quality Management
  Created: DBA in Operational Management
  Created: DBA in Marketing
  Created: DBA in Human Resource Management
```

Seeder ye banata hai:
- **1 ProgramCategory** — `doctorate` (`firstOrCreate`, isliye koi migration nahi chahiye)
- **0 new UniversityPartner** — `rushford-business-school` already exists
- **5 Program rows** with all 13 JSON content columns
- **5 SeoMetadata rows** (morph)
- **26 Faq rows** (morph: 6+5+5+5+5)

---

## Verification checklist (step 5 ke baad)

- [ ] Admin panel → Programs me 5 naye rows dikhe
- [ ] Category filter me "Doctorate" option aaya
- [ ] Ek program kholo → saare 5 tabs bhare hue hain
- [ ] FAQs tab me entries hain (ye `MasterProgramsSeeder` nahi karta tha)
- [ ] SEO tab me meta title/description bhara hai
- [ ] Frontend `/programs/dba-in-marketing-rushford-business-school` khulta hai
- [ ] Detail page pe GCC heading **"Professionals"** padhta hai, "Students" nahi
- [ ] Second run pe row count same raha

---

## `is_active` — jaan-boojh kar `false`

Saare 5 `is_active: false` ke saath aate hain, matlab **frontend pe public nahi dikhenge**. Ye deliberate hai: content verify karne ke baad admin panel se manually ON karo.

`is_active` sirf **create** pe set hota hai. Admin panel me toggle karoge toh agla re-seed usko reset **nahi** karega. Yahi baat `is_featured`, `testimonials`, `reviews` pe bhi lagu hai.

---

## Rollback

Sab kuch slug se identify hota hai:

```php
// php artisan tinker
$slugs = ['dba-in-supply-chain-management-rushford-business-school',
          'dba-in-quality-management-rushford-business-school',
          'dba-in-operational-management-rushford-business-school',
          'dba-in-marketing-rushford-business-school',
          'dba-in-human-resource-management-rushford-business-school'];

App\Models\Program::whereIn('slug', $slugs)->each(function ($p) {
    $p->faqs()->delete();
    $p->seo()->delete();
    $p->delete();
});
```

Category chhod do — harmless hai aur agle batch me dobara chahiye hogi.

---

## Naya content add karne ka tareeka

1. Content file `output/programs/doctorate/<university>/<slug>.md` pe daalo (same format)
2. `python3 tools/build_catalog_data.py`
3. `python3 tools/validate_catalog_data.py`
4. Seeder dobara chalao — purane rows update honge, naye create

Diplomas ke liye: `tools/build_catalog_data.py` me `CATEGORIES`/`UNIVERSITIES` me Qualifi + Gatehouse add karo, `diploma_programs.php` generate karo, aur `CatalogProgramsSeeder::SOURCES` me uski line uncomment kar do.

---

## Known gaps (seeding block nahi karte)

| Gap | Asar |
|---|---|
| GAP-SOURCE-RBS-01 | Koi accreditation claim nahi — `recognition` / `accreditation_groups` khaali |
| GAP-SOURCE-RBS-02 | Fees sirf labels, amounts nahi |
| GAP-SOURCE-RBS-04 | Careers 3–7 roles (template 8–20 chahta hai) — validator warning deta hai, error nahi |
| GAP-SCHEMA (diploma) | Qualifi + Gatehouse abhi `UniversityPartner` nahi hain |
| `image_url` | Set nahi — detail page default image pe fallback karega |
