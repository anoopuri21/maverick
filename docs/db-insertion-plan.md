# Plan — Doctorate & Diploma programmes ko DB + Admin Panel me insert karna

**Date:** 2026-10-05 · **Scope:** 69 programmes (20 Doctorate + 49 Diploma)
**Approach:** data-file–driven idempotent seeder, exactly like the existing `MasterProgramsSeeder`

---

## 0. Pehle do sach (plan inhi pe tika hai)

**1. Content abhi ready nahi hai.**

| | Count |
|---|---|
| Content files exist (all `Needs Revision`) | **5** |
| Research done, content pending | 3 |
| Blocked (no official source) | 5 |
| Research pending | **56** |
| **Total** | **69** |

Matlab aaj 69 me se **5** insert ho sakte hain. Isliye plan ka design rule hai: **pipeline ek baar banao, content aate hi bar-bar chalao.** Big-bang import ka intezaar mat karo.

**2. Achhi khabar — `doctorate` category ke liye migration ki zaroorat NAHI hai.**

Pichli QA report me maine ise "developer action" bola tha. Code dubara padhne pe woh **galat** nikla. `MasterProgramsSeeder` line 23 categories ko aise banata hai:

```php
ProgramCategory::firstOrCreate(['slug' => $category['slug']], [...])
```

Toh data file ke `categories` array me do entries add karna kaafi hai — seeder khud bana dega. **QA-FAIL-01 ab 2-line fix hai, migration nahi.**

---

## 1. Architecture — naya kuch invent nahi karna

Project me pattern already set hai. Usi ko copy karo:

```
database/seeders/
├── data/
│   ├── master_programs.php        ← existing (50 programmes) — chhuna mat
│   ├── doctorate_programs.php     ← NAYA
│   └── diploma_programs.php       ← NAYA
├── MasterProgramsSeeder.php       ← existing — chhuna mat
└── CatalogProgramsSeeder.php      ← NAYA (dono data files ko handle karega)
```

**Kyun alag file, existing me append nahi?** Blast radius. `master_programs.php` 50 live programmes feed karta hai. Usme edit karoge toh ek typo saari live pages gira dega. Naya file = naya risk zero.

**Kyun ek seeder, do nahi?** Logic dono ke liye identical hai. Sirf data alag hai.

---

## 2. Schema blockers — insert se pehle hal karna hai

| # | Blocker | Fix | Effort |
|---|---|---|---|
| **B1** | `doctorate` ProgramCategory missing | data file ke `categories` array me entry | 2 min |
| **B2** | `diploma` ProgramCategory missing | wahi | 2 min |
| **B3** | **Qualifi (45 diplomas) aur Gatehouse (4) UniversityPartner nahi hain** | `universities` array me add karo — seeder `firstOrCreate` kar dega | 10 min |
| **B4** | `university_partner_id` + `is_active` content files me missing | data file me `university_slug` key se aata hai | auto |

**B3 pe ek decision aapka chahiye.** Qualifi aur Gatehouse **awarding bodies** hain, universities nahi. Teen options:

- **(a) UniversityPartner table me daal do** — sabse simple, schema change zero, 49 diplomas aaj unblock. "University Partner" label thoda loose ho jata hai. ✅ *Recommended*
- (b) Nayi `AwardingBody` table + migration + Filament resource + blade changes — clean, par 2–3 din ka dev kaam
- (c) Diplomas ko ek dummy partner ke neeche daalo — sasta, par detail page pe galat branding dikhega

Main **(a)** recommend karta hoon. `UniversityPartner` me already `name`, `country`, `country_code` hain — Qualifi ke liye `country: United Kingdom` bilkul sahi baithta hai.

---

## 3. Data file ka shape (existing contract se copy)

Seeder in exact keys ko expect karta hai. Content file se yahi fields nikalni hain:

```php
return [
    'categories' => [
        ['slug' => 'doctorate', 'name' => 'Doctorate', 'sort_order' => 200],
        ['slug' => 'diploma',   'name' => 'Diploma',   'sort_order' => 300],
    ],
    'universities' => [
        ['slug' => 'rushford-business-school', 'name' => 'Rushford Business School',
         'country' => 'Switzerland', 'country_code' => 'CH', 'sort_order' => 2],
        // Qualifi / Gatehouse yahan (B3 approve hone ke baad)
    ],
    'programs' => [[
        'slug'            => 'dba-in-marketing-rushford-business-school',
        'title'           => 'DBA in Marketing',
        'level'           => 'DBA',
        'category_slug'   => 'doctorate',
        'university_slug' => 'rushford-business-school',
        'duration'        => '36 Months',
        'sort_order'      => 201,
        'hero'            => '...',              // short_description
        'overview'        => ['para1','para2'],  // seeder <p> wrap karega
        'highlights'      => [['Awarded by','Rushford Business School'], ...],  // 6
        'snapshot'        => [['Degree Award','DBA'], ...],                     // 7
        'benefits'        => [['Title','Desc','icon-name'], ...],               // 6
        'learning'        => ['item', ...],                                     // 8
        'careers'         => ['Role', ...],
        'structure'       => [['title'=>..,'subtitle'=>..,'modules'=>[...]], ...],
        'support'         => ['item', ...],                                     // 6
        'gcc'             => ['...'],
        'fees'            => ['label', ...],                                    // 5
        'faqs'            => [['Q','<p>A</p>',1], ...],   // ← NAYA, niche dekho
        'meta_title'      => '...',
        'meta_description'=> '...',
    ]],
];
```

### Ek gap jo MasterProgramsSeeder me hai
`MasterProgramsSeeder` **FAQs seed hi nahi karta** — par humare har draft me 5–6 FAQs hain. Naye seeder me `ProgramSeeder` line 63 wala idempotent pattern daalna hai:

```php
foreach ($row['faqs'] as [$question, $answer, $sort]) {
    $program->faqs()->updateOrCreate(
        ['question' => $question],
        ['answer' => $answer, 'sort_order' => $sort, 'is_active' => true]
    );
}
```

Ek aur chhoti baat: `MasterProgramsSeeder` me `gcc_heading` hardcoded hai *"Why GCC Students Choose This Programme"*, jabki humare drafts me *"...Professionals..."* hai. Naye seeder me ise `$row` se lo, hardcode mat karo.

---

## 4. Idempotency — sabse zaroori best practice

Seeder ko **baar-baar chalane pe safe** hona chahiye, kyunki content batches me aayega.

| Entity | Method | Behaviour |
|---|---|---|
| ProgramCategory | `firstOrCreate(['slug'])` | ✅ duplicate nahi |
| UniversityPartner | `firstOrCreate(['slug'])` | ✅ |
| Program | `firstOrNew(['slug'])` + `fill()` + `save()` | ✅ **slug = identity key** |
| SeoMetadata | `seo()->updateOrCreate([], [...])` | ✅ |
| Faq | `faqs()->updateOrCreate(['question'])` | ✅ |

**Golden rule: slug kabhi mat badlo.** Slug hi join key hai research → draft → approved → DB row → live URL. Slug badla = duplicate row ban jayegi, purani orphan ho jayegi.

`is_featured`, `testimonials`, `reviews` sirf **create** pe set karo (`if ($creating)`) — warna admin panel me ki gayi manual editing har re-seed pe mit jayegi. Existing seeder yahi karta hai; isko preserve karna hai.

---

## 5. Execution — 6 steps

| Step | Kaam | Kaun | Output |
|---|---|---|---|
| **1** | B3 decide karo (Qualifi/Gatehouse) | **Aap** | go-ahead |
| **2** | QA fixes: 7 seniority nouns strip + 5 files clean | Main | 5 files `PASS` |
| **3** | `CatalogProgramsSeeder.php` + `doctorate_programs.php` likho, **sirf 5 programmes** se | Main | code ready |
| **4** | **Staging pe chalao**, verify karo | **Aap** (sandbox me PHP nahi hai) | 5 rows live |
| **5** | Visual check: detail page, admin panel, SEO, FAQs | Aap + main | sign-off |
| **6** | Baaki content aate hi data file me append karke dubara chalao | Dono | incremental |

### Step 4 ke commands
```bash
php artisan db:seed --class=CatalogProgramsSeeder     # staging
php artisan db:seed --class=CatalogProgramsSeeder     # dubara — idempotency proof
php artisan db:seed --class=CatalogProgramsSeeder --force   # production
```
Dusri baar chalane pe row count **badalna nahi chahiye**. Yeh hi asli test hai.

> ⚠️ Is sandbox me `php`, `composer` aur `vendor/` nahi hain — main seeder **likh sakta hoon, chala nahi sakta.** Isliye step 4 aapke environment pe hai. Iski bharpayi ke liye step 3 me main ek Python validator bhi doonga jo data file ko structurally check kare (field counts, slug uniqueness, required keys, char limits) — PHP ke bina bhi 90% galtiyan wahin pakdi jayengi.

---

## 6. Rollout order

```
5 Rushford DBA (ready)
   └─> 3 Rushford DBA (content likhna hai; S.No 7 & 8 me zero career roles)
         └─> 7 GAU PhD (research pending; S.No 19 "PhD in Law" launch hi nahi hua → skip)
               └─> 49 Diplomas (Qualifi 45 + Gatehouse 4 — B3 pe depend)
```

Pehla batch **5 ka** rakho, 49 ka nahi. Ek choti batch poore pipeline ki galtiyan saste me dikha deti hai.

---

## 7. Safety net

1. **Backup:** `mysqldump` production se pehle. Non-negotiable.
2. **Staging first:** production pe seeder kabhi blind mat chalao.
3. **`is_active`:** pehle batch ko `false` rakho, content verify karne ke baad admin panel se ON karo. Aadha-adhura page kabhi public nahi dikhega.
4. **Git:** data file aur seeder commit karo; `--force` wali production run ka output log me rakho.
5. **Rollback:** sab kuch slug se identify hota hai, toh
   `Program::whereIn('slug', [...])->delete()` saaf rollback de deta hai.

---

## 8. Kya is plan me jaan-boojh kar NAHI hai

- **Nayi migration** — zaroorat hi nahi padi (B1/B2 `firstOrCreate` se, B3 option (a) se).
- **Filament resource me badlav** — `ProgramResource` already in saare JSON fields ko handle karta hai. Seed hone ke baad admin panel me programmes **apne aap editable** ho jayenge. Koi UI kaam nahi.
- **CSV/Excel bulk importer** — 69 rows ke liye over-engineering. Data file + git versioning behtar hai: reviewable, revertable, aur code-review me dikhta hai.
- **`master_programs.php` me chhedchhad** — 50 live programmes ko haath nahi lagana.

---

## 9. Aapse chahiye — 2 decisions

1. **B3:** Qualifi + Gatehouse ko `UniversityPartner` bana doon? *(option (a), recommended)*
2. **Step 2–3 shuru karu?** QA fixes + seeder + validator, 5 programmes se.

Dono "haan" mile toh code aaj ready kar doonga; aapko sirf staging pe seeder chalana hoga.
