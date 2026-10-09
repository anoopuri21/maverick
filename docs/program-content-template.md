# Program Content Template
**Maverick Business Academy London** · derived from the live Filament form (`app/Filament/Resources/ProgramResource.php`), the `programs` schema, `detail.blade.php`, and measured against the 53 programmes already live.

Length guidance is **measured**, not invented: `min / median / max` are real statistics from the 50 seeded master's programmes in `database/seeders/data/master_programs.php` plus the BSc Psychology programme.

Legend — **Req**: ● required · ○ recommended · ◌ optional
**Format**: `Text` single-line · `Plain` plain textarea · `HTML` rich editor (`<p>` wrapped) · `Repeater` list of rows

---

## TAB 1 — Basic Information

| # | Field | DB key | Req | Format | Length / rules |
|---|---|---|---|---|---|
| 1.1 | Category | `program_category_id` | ● | Select | Existing: Bachelors, MBA, Executive MBA, MSc, LLM. New values need a ProgramCategory row first. Blank → auto "Uncategorized". |
| 1.2 | Programme Title | `title` | ● | Text | **2–7 words / 10–50 chars** (median 5 words, 33 chars). Exact award + specialisation, e.g. "MBA in Business Management". No university name inside the title. |
| 1.3 | Slug | `slug` | ● | Text | Auto-generated from title; lowercase-hyphen, unique. Pattern used for duplicates: `{title}-{university}`. Powers `/programs/{slug}`. |
| 1.4 | University Partner | `university_partner_id` | ● | Select | Pick an existing partner. **Do not retype university copy** — name/description/image/logos are inherited. |
| 1.5 | Duration | `duration` | ● | Text | **9–34 chars.** Natural phrasing: "12 to 15 months", "20–24 Months", or the compliance phrasing "Subject to the approved academic pathway and entry route". |
| 1.6 | Level | `level` | ● | Text | 1–3 words badge: `MBA`, `MSc`, `BSc`, `Executive MBA`, `LLM`, `BBA`. |
| 1.7 | Sort Order | `sort_order` | ○ | Integer | Lower = first. Masters block currently uses 100+. |
| 1.8 | Featured on Homepage | `is_featured` | ◌ | Toggle | Default off. |
| 1.9 | Active | `is_active` | ● | Toggle | Must be ON to appear publicly. |

---

## TAB 2 — Hero & Media

| # | Field | DB key | Req | Format | Length / rules |
|---|---|---|---|---|---|
| 2.1 | Short Description (hero lead + listing card) | `short_description` | ● | Plain, **max 300 chars (hard)** | **33–55 words / 197–280 chars** (median 41 words, 250 chars). 2–3 sentences. Hook + what it builds + duration/flexibility. Plain text only — no HTML. |
| 2.2 | Programme Overview | `description` | ● | HTML | **2–3 paragraphs, 110–195 words total** (median 139). Para 1 = what the coursework covers; Para 2 = format/who it suits; Para 3 = who awards it + the practical payoff. One `<p>` per paragraph, 35–65 words each. |
| 2.3 | Hero Image URL | `image_url` | ○ | URL / MediaPicker | **800×540px**. Falls back to a default if empty. |
| 2.4 | Brochure URL | `brochure_url` | ◌ | URL | When set, a Download Brochure button appears in the hero. |

---

## TAB 3 — Programme Sections (page order)

### 3.1 Quick Highlights — `highlights` ● (hero tick-list)
Repeater `{ label, value }` · **exactly 6 rows**
- `label`: 1–2 words. Conventional set: **Awarded by · Duration · Study mode · Format · Assessment · Funding**
- `value`: 2–5 words (max ~40 chars). e.g. `Girne American University`, `12 to 15 months`, `Online study`, `Part-time friendly`, `Research project`, `Scholarship available`

### 3.2 Programme Snapshot — `snapshot` ● (bento grid)
Repeater `{ label, value }` · **6–7 rows**
- `label`: 1–2 words. Conventional set: **Degree Award · Awarding University · Specialisation · Duration · Assessments · Study Mode · Credits**
- `value`: 2–12 words. One exception: the final **"Pathway note"** row is a compliance paragraph of **25–45 words** stating that duration/credits/fees are confirmed in writing before payment.
- ⚠️ This block is the only home for entry requirements / credits / assessment — there are no dedicated fields for them.

### 3.3 Why Choose This Programme — `benefits` ● (pillar cards)
Repeater `{ icon, title, desc }` · **exactly 6 rows**
- `icon`: Lucide name from the 19-item preset (`users`, `book-open`, `globe`, `trending-up`, `laptop`, `sparkles`, `shield`, `award`, `graduation-cap`, `briefcase`, `target`, `lightbulb`, `heart-handshake`, `clock`, `map-pin`, `monitor`, `route`, `badge-check`, `landmark`) or any custom Lucide name.
- `title`: **3–6 words**, verb-led. e.g. "Develop Leadership Skills".
- `desc`: HTML, **single `<p>`, 8–35 words (median 17)**. One sentence.

### 3.4 What You'll Learn — `learning` ● (outcomes)
Repeater `{ item }` · **8–10 rows**
- `item`: **3–16 words (median 9)**, plain text, starts with a verb — Understand / Analyse / Apply / Evaluate / Explore / Communicate. No trailing full stop.

### 3.5 Career Opportunities — `careers` ● (tag cloud)
Repeater `{ title }` · **8–20 rows** (masters use 10; BSc Psychology uses 20)
- `title`: **1–6 words (median 3)** — a job title only. No descriptions, no salaries.

### 3.6 Programme Structure — `structure` ● (accordion)
Nested repeater `{ title, subtitle, modules[ { title, overview, desc, list[{point}] } ] }`
- **Stages: 1–4** (median 3). `title` = thematic, **2–5 words** ("Business Foundations", "Psychology specialization"). Helper text says **avoid "Year 1/Year 2"** as the stage title — year counts aren't shown publicly. Use `subtitle` for "Core modules" / "Years 1 and 2".
- **Modules per stage: 3–20** (median 3; 8–12 typical for a full degree).
  - `module.title` ● — **2–6 words**, the official module name. *Only the title renders publicly.*
  - `module.overview` ◌ — HTML, 1 `<p>`, 15–30 words. Stored, not displayed in the list view.
  - `module.desc` ◌ — HTML alternate description, stored for future use.
  - `module.list[].point` ◌ — 2–4 words per point, 2–4 points.

### 3.7 Awarding University — (read-only on the program)
No writable fields. Name, description and image come from the linked `UniversityPartner`. If university copy needs to change, edit **University Partners**, not the program.

### 3.8 Why Study Through Maverick — `support` ● (perk grid)
Repeater `{ item }` · **exactly 6 rows**
- `item`: **2–4 words**, noun phrase, Title case. Standard set: *Dedicated academic support · Flexible learning · Assessment support · Affordable instalments · Career guidance · Documentation assistance*.

### 3.9 Why GCC Professionals Choose This Course — `gcc_heading` + `gcc_reasons` ○
- `gcc_heading` ◌ Text — blank falls back to "Why GCC professionals choose this course?". Live content uses "Why GCC Students Choose This Programme".
- `gcc_reasons` Repeater `{ icon, title, text }` · **6–8 rows**
  - `title`: **4–8 words**, specific claim ("The mental health field is growing fast").
  - `text`: plain textarea, **8–46 words (median 21)**, 1–2 sentences. Cite the source inline when a market stat is used (e.g. "per Ken Research"). At least one row should be an honest-limits / regulatory caveat.
  - `icon`: Lucide name, same preset list.

### 3.10 Student Success Stories — `testimonials` ◌ (video slider)
Repeater `{ name, role, country, category, video, thumb }`
- `name` 2–3 words · `role` 2–3 words ("MBA Graduate") · `country` 1–2 words ("UAE") · `category` badge, UPPERCASE 1 word (`STUDENT` / `GRADUATE`)
- `video`: YouTube URL — thumbnail auto-generates. `thumb` only if a custom image is needed.
- Leave empty until real approved footage exists; the section auto-hides.

### 3.11 Fees & Scholarships — `fees` ● (chips → enquiry)
Repeater `{ title }` · **exactly 5 rows** · **labels only, never amounts** (current policy)
- Observed sets: `Registration Fee · Initial Payment · Monthly Instalments · Scholarship Availability · Offer Validity`, or `Programme Fees · Scholarships · Payment Options · Written Breakdown · Intake Offer`.
- `title`: **1–5 words** (a few live rows run to a full sentence, e.g. "No hidden charges; everything documented before payment" — acceptable but keep under ~10 words).

### 3.12 Student Reviews — `reviews` ◌ (Google-style cards)
Repeater `{ name, avatar, rating, review }`
- `name` 2–3 words · `avatar` optional URL/MediaPicker (initials fallback) · `rating` 1–5 select, default 5
- `review`: HTML, **25–60 words**, first person.

---

## TAB 4 — FAQs (`faqs` relation) ○
Repeater · **5–8 items recommended**
- `question` ● Text — **6–14 words**, ends with `?`, written as a real applicant question.
- `answer` ● HTML — **30–80 words**, 1–2 `<p>`. Direct answer first sentence.
- `sort_order` integer · `is_active` toggle (default on).

---

## TAB 5 — SEO (`SeoMetadata` morph) ●

| Field | Req | Length / rules |
|---|---|---|
| `meta_title` | ● | **45–78 chars** (median 61). Pattern: `{Programme} Online \| {University}` or `{Programme} \| Maverick Business Academy London`. |
| `meta_description` | ● | **120–161 chars** (median 139). Lead with duration + award + 3–4 keyword themes. |
| `meta_keywords` | ◌ | Comma-separated. |
| `canonical_url` | ◌ | Blank = current URL. |
| `robots` | ◌ | Select, default index/follow. |
| `og_title` | ◌ | max **60 chars** (enforced) |
| `og_description` | ◌ | max **200 chars** (enforced) |
| `og_image` / `og_image_url_input` | ◌ | Media library takes priority over URL. |
| `og_type` | ◌ | Select. |
| `twitter_card` | ◌ | Select. |
| `twitter_title` | ◌ | max **70 chars** (enforced) |
| `twitter_description` | ◌ | max **200 chars** (enforced) |
| `twitter_image` / URL | ◌ | |
| `schema_json`, `custom_head_scripts`, `custom_body_scripts` | ◌ | Raw blocks, rendered only when non-empty. |

---

## Totals per programme page

| Block | Original words |
|---|---|
| Short description | 35–55 |
| Overview | 110–195 |
| Highlights + Snapshot | 60–110 |
| Benefits (6) | 60–150 |
| Learning outcomes (8–10) | 70–110 |
| Careers (8–20) | 25–60 |
| Structure (stages + modules) | 40–120 |
| Support (6) | 15–20 |
| GCC reasons (6–8) | 100–200 |
| Fees (5) | 10–25 |
| FAQs (5–8) | 200–500 |
| SEO | 40–60 |
| **Total** | **≈ 700–1,500 words (typical ≈ 900)** |

---

## House style (observed in live copy)
1. **British English** — programme, specialisation, analyse, instalments, organisation.
2. **Second person, plain, unhyped.** Short declarative sentences; an occasional deliberate fragment.
3. **No unverifiable claims.** Market stats are always attributed inline ("per Ken Research").
4. **Honest caveats are a feature** — e.g. "The bachelor's is a foundation, not a licence."
5. **No numeric fees on the page.** Everything is "confirmed in writing before payment".
6. **Never retype university copy** on the programme — it lives on the UniversityPartner.
7. **Empty = hidden.** A blank block silently removes the section *and* its scroll-spy dot. Don't ship placeholder text; leave it blank instead.
8. **No HTML in** `short_description`, any repeater `TextInput`, or `gcc_reasons.text`. HTML only in `description`, `benefits.desc`, `module.overview/desc`, `reviews.review`, `faqs.answer`.

---

## Pre-publish checklist
- [ ] `title`, `slug`, `level`, `duration` set · slug unique and lowercase-hyphen
- [ ] `university_partner_id` linked (partner exists with description + recognition logos)
- [ ] `category` set to a real category (not Uncategorized)
- [ ] Hero image 800×540 present
- [ ] 6 highlights · 6–7 snapshot (incl. Pathway note) · 6 benefits · 8–10 outcomes · 8+ careers
- [ ] Structure stages don't use "Year 1/Year 2" as the stage `title`
- [ ] 6 support points · 6–8 GCC reasons · 5 fee labels (no amounts)
- [ ] 5–8 FAQs active
- [ ] SEO meta title ≤ 78 chars, description 120–160 chars
- [ ] `is_active` ON · `sort_order` set
- [ ] Programme listing cache busted after bulk import (`PublicContentCache::PROGRAMS_LISTING`)
