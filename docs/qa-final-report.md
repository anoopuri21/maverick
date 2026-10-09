# QA / Fact-Checker — Final Audit Report

**Date:** 2026-10-05 · **Auditor:** QA/Fact-Checker Agent (station 05)
**Scope:** 100% of approved output (5 of 5 files) — not a 15–20% sample
**Verdict:** ⛔ **CONDITIONAL FAIL — 5 of 5 files tagged `Needs Revision`. Do not sign off yet.**

---

## 1. Headline numbers

| Metric | Count |
|---|---|
| Total programmes in tracker | **69** |
| Research Complete | 8 |
| Research Incomplete (blocked) | 5 |
| Research Pending | 56 |
| Content files produced | **5** |
| Files audited | **5 (100%)** |
| Fully completed, sign-off ready | **0** |
| Needs Revision | **5** |
| Research gaps / missing data | **61** (5 blocked + 56 pending) |

---

## 2. What passed

| Check | Result |
|---|---|
| **Fact accuracy** — 12 core data points per file (36 months, 180 ECTS, both dissertation word ranges, 5,000-word proposal, IELTS 5.5 / TOEFL 58 / TOEIC 555 / PTE 50 / Duolingo 90, 8-year RPE, 5-year pathway split) | ✅ **60/60 matched** to dossier |
| **No unsourced claims** — content cross-checked against dossiers | ✅ zero |
| **No forbidden claims** — ACBSP / IACBE / AACSB / UN PRME / fee amounts / "EPD" / rankings | ✅ zero |
| **AI-pattern scan** — 20 tells | ✅ **0 hits** across all 5 |
| **Sentence variety** — stdev 18.0–22.2 words, avg 17.8–19.7 | ✅ human-like |
| **Repetitive openings** — consecutive same-word sentence starts | ✅ 0–2 per file |
| **British English** | ✅ 0 Americanisms (official module names excluded) |
| **Grammar** — duplicate words, space-before-punctuation, unbalanced brackets, lowercase sentence starts | ✅ all 0 |
| **Hard limits** — short_description ≤300 chars & 33–55 words, meta_title 45–78, meta_description 120–161, title 2–7 words & 10–50 chars, no university name in title | ✅ 35/35 |
| **Field counts** — highlights 6, snapshot 7, benefits 6, learning 8, gcc 6, support 6, fees 5, faqs 5–6 | ✅ all in range |
| **Slug uniqueness** vs 50 live programmes | ✅ no collision |

The writing itself is sound. **Every failure below is structural or a wording-precision issue — none is a factual error.**

---

## 3. Failures

### 🔴 QA-FAIL-01 — `category: doctorate` does not exist *(blocker, all 5 files)*

`database/seeders/data/master_programs.php` defines exactly four categories:

```
mba · executive-mba · msc · llm
```

There is no `doctorate` category. `MasterProgramsSeeder.php` line 54 resolves
`$categories[$row['category_slug']]->id` — an undefined key would throw on import.
The only "Doctorate" string in the data file is prose ("Doctorate-ready structure"), not a category record.

**Fix:** create a `ProgramCategory` row `{slug: doctorate, name: Doctorate, sort_order: …}` before import. Developer action. Tracked as **GAP-SCHEMA-03**, raised in Phase 3 and still open.

### 🔴 QA-FAIL-02 — two mandatory (●) fields absent *(all 5 files)*

| Field | Status |
|---|---|
| `1.4 university_partner_id` | ❌ not recorded — **but resolvable now:** partner slug `rushford-business-school` already exists |
| `1.9 is_active` | ❌ not recorded — must be ON for the page to appear publicly |

Low effort, but by the template these are required and currently missing.

### 🟠 QA-DRIFT-03 — seniority nouns added to career roles *(4 of 5 files)*

Rushford's pages name **functions**, not job titles. Converting "…management" → "…Manager" is fair. Appending **Lead / Specialist / Practitioner** asserts a seniority level the source never states.

| File | Source wording | Written as | Verdict |
|---|---|---|---|
| Supply Chain | "Procurement" | Procurement **Lead** | ⚠ drift |
| Quality | "Process improvement" | Process Improvement **Lead** | ⚠ drift |
| Quality | "Six Sigma" | Six Sigma **Practitioner** | ⚠ drift |
| Quality | "Lean Management" | Lean Management **Specialist** | ⚠ drift |
| Marketing | "digital marketing" | Digital Marketing **Lead** | ⚠ drift |
| HR | "Talent management" | Talent Management **Lead** | ⚠ drift |
| HR | "organizational development" | Organisational Development **Lead** | ⚠ drift |
| **Operational Management** | — | — | ✅ **clean, all 3 traceable** |
| **Marketing** CMO / VP Marketing / Marketing Consultant | verbatim on page | unchanged | ✅ |

**Fix:** drop the appended noun — "Procurement", "Process Improvement", "Six Sigma", "Lean Management", "Digital Marketing", "Talent Management", "Organisational Development". Five-minute edit, no re-research. British spelling of "Organisational" is correct and stays.

---

## 4. Per-file scorecard

| S.No | Programme | Facts | AI-scan | Grammar | Structure | Careers | Status |
|---|---|---|---|---|---|---|---|
| 1 | Supply Chain Management | ✅ | ✅ | ✅ | ❌ 01,02 | ⚠ 1 drift | Needs Revision |
| 2 | Quality Management | ✅ | ✅ | ✅ | ❌ 01,02 | ⚠ 3 drifts | Needs Revision |
| 3 | Operational Management | ✅ | ✅ | ✅ | ❌ 01,02 | ✅ | Needs Revision |
| 4 | Marketing | ✅ | ✅ | ✅ | ❌ 01,02 | ⚠ 1 drift | Needs Revision |
| 5 | Human Resource Management | ✅ | ✅ | ✅ | ❌ 01,02 | ⚠ 2 drifts | Needs Revision |

Closest to sign-off: **S.No 3 Operational Management** — only the two shared structural fixes.

---

## 5. Research gaps / missing data — 61 programmes

### Blocked (5) — all Rushford Business School (RBS), Switzerland
| S.No | Programme | Reason |
|---|---|---|
| 9 | Educational Management & Leadership | Not an RBS specialisation. Only an unnamed-partner DEd. Overlaps GAU S.No 18 |
| 10 | Data Analytics | Official term is "Data Science"; partner institution never named |
| 11 | Hospitality, Travel & Tourism | No RBS doctoral programme exists. Likely GAU S.No 20 |
| 12 | Logistics & Supply Chain Management | **Duplicate of S.No 1** — merge, do not build a second page |
| 13 | Global Leadership & Strategy | Not a specialisation; overlaps S.No 7 |

### Researched but undrafted (3) — Rushford Business School (RBS), Switzerland
| S.No | Programme | Note |
|---|---|---|
| 6 | Healthcare Management | 3 named roles — draftable now |
| 7 | International Business | ⚠ **zero** named career roles |
| 8 | Financial Management | ⚠ **zero** named career roles |

### Pending research (56)
S.No 14–20 Girne American University (**S.No 19 PhD in Law is marked "(To be launched)" on gau.edu.tr → will be Incomplete**) · S.No 21–69 remaining universities.

### Open data gaps on all Rushford pages
| ID | Missing |
|---|---|
| GAP-SOURCE-RBS-01 | Accreditation beyond eduQua (online division) + QS Stars 5★ |
| GAP-SOURCE-RBS-02 | Tuition fees — JS calculator, not scrapeable |
| GAP-SOURCE-RBS-04 | Career lists 3–7 roles vs template's 8–20 |
| GAP-POLICY-01 | Fee-label policy (Maverick decision, not research) |
| GAP-SCHEMA-03 | `doctorate` ProgramCategory does not exist |

---

## 6. Recommendation

Three fixes clear all five files:

1. **Create the `doctorate` ProgramCategory** — developer action, unblocks every doctorate page in the project.
2. **Add `university_partner_id: rushford-business-school` and `is_active: true`** to all 5 — agent action, immediate.
3. **Strip 7 appended seniority nouns** from career lists — agent action, immediate.

Items 2 and 3 I can do straight away on your word. Item 1 needs a developer or your approval to add the migration/seeder row.

**Sign-off recommendation: HOLD.** The content is factually clean and reads well; the blockers are schema and precision, not quality.
