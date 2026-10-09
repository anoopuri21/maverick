# Phase 8 — Completion & Handover Report

**Date:** 2026-10-09 · **Branch:** `arena/01a10b8e-maverick`
**Status:** **Content complete.** 62 of 69 tracker rows are written, QA-passed and
generated into the seeder data files. The remaining 7 are blocked at source and are
documented below with evidence. Seeding itself runs on staging (no PHP in this sandbox).

---

## 1. What was delivered

| | Count |
|---|---|
| Tracker rows in scope (Doctorate + Diploma only) | 69 |
| **Production-ready content pages** | **62** |
| Rows blocked at source (Research Incomplete) | 7 |
| Research dossiers written | 50 |
| Declared source gaps carried on the pages | 103 distinct GAP IDs |
| QA gate | **PASS 62/62** |
| Duplicate-phrase scan | **0 stylistic duplicates** (109 factual shingles, all permitted) |
| Catalogue validator | **PASSED — safe to seed** |

### By awarding institution

| Institution | Category | Pages | Sort range |
|---|---|---|---|
| Rushford Business School | Doctorate (DBA) | 8 | 201–208 |
| Girne American University | Doctorate (PhD) | 5 | 209–213 |
| Gatehouse Awards | Diploma (Level 7) | 4 | 214–217 |
| Qualifi Ltd | Diploma (Level 3) | 8 | 218–225 |
| Qualifi Ltd | Diploma (Level 5 Extended) | 14 | 226–239 |
| Qualifi Ltd | Diploma (Level 7) | 23 | 240–262 |
| **Total** | | **62** | |

Every programme is attached to a **university partner record that already exists** —
`rushford-business-school`, `girne-american-university`, `gatehouse-awards`, `qualifi` —
exactly as the first five Rushford DBAs were. No new tables were introduced.

---

## 2. Acceptance criteria — how each was met

| Criterion | Evidence |
|---|---|
| **Official sources only** | Every page cites its dossier, and every dossier lists the official URLs it was built from. Qualifi content comes only from `qualifi.net`, Gatehouse only from `gatehouseawards.org`, Rushford only from `rushford.ch`, GAU only from `gau.edu.tr`. Aggregators and reseller sites were rejected on sight. Each QAN was cross-checked against `qualifi.net/qualifications/`. |
| **Attach to existing universities** | `university_slug` on every page resolves to one of the four partner records; the seeder uses `firstOrCreate` and never duplicates. |
| **Humanised, layman language** | Separate humanizer pass per page; no awarding-body jargon left unexplained; a style gate blocks 24 AI-tell phrases and 11 Americanisms. |
| **SEO-optimised** | Per-page `meta_title` (45–78 chars) and `meta_description` (120–161 chars), primary plus secondary keyword sets recorded in front-matter, keywords worked into prose rather than stuffed. |
| **No duplicated words or phrases** | `tools/dedupe_scan.py` compares 8-word shingles across all 62 pages on every batch. Final state: **0 stylistic duplicates**. Only factual strings (official titles, QANs, regulator names) repeat. |
| **Exactly the tracker list** | 62 written + 7 blocked = 69. Nothing invented, nothing added. |
| **Production-ready** | Generated PHP data files validate clean; seeder is idempotent on `slug`; runbook covers backup, seed, re-seed proof, activation and rollback. |

---

## 3. Editorial policy actually applied

The Qualifi and Gatehouse sources are thin and, in places, defective. Rather than paper
over that, every page states what the awarding body has and has not published. Standing
rules used throughout:

- Qualifi awards and regulates but does not teach — **duration and fee always come from
  the approved centre**, and the pages say so.
- `careers` opens with an explicit "no destination or salary data is published" note,
  then lists only the awarding body's own routes.
- **Master's / MSc / LLM progression claims are always hedged**: quoted exactly, then
  followed by a statement that the diploma awards no degree, that no university is named,
  and that admission and credit are the receiving institution's decision alone.
- **Professional-practice disclaimers** wherever a title implies a profession — SRA / BSB
  / CILEX on the law pages, CIPD on HR, ACCA / CIMA / ICAEW / ICAS / CIPFA / AAT on
  accounting, QTS / ITT / PGCE / DfE on education, BPS / HCPC on psychology, NEBOSH /
  IOSH on safety, NCSC / CREST / CompTIA / (ISC)² / ISACA and vendor credentials on cyber.
- Where published learning outcomes are **missing, duplicated or contaminated**, the page
  carries a visible preamble saying so and the list is rebuilt from published subjects —
  never silently invented. Applied on S.No 56, 60, 61, 62, 63, 64, 65 and 69.
- Source typos and defective lists are either corrected **with the correction disclosed**,
  or reported unresolved. Never silently rewritten.
- Documents linked but not read (centre specifications, 2019 brochures, the IIRSM
  leaflet) are named as unread, and **no recognition is claimed from them**.

---

## 4. The 7 blocked rows — re-verified 2026-10-09

All seven were re-checked against official sources on the date of this report. None has
changed status.

| S.No | Programme | Institution | Finding |
|---|---|---|---|
| 9 | Educational Management & Leadership | Rushford | The official DBA listing shows only a **DEd delivered "via collaborations with other partners"**, partner unnamed, with no programme page. |
| 10 | Data Analytics | Rushford | Official page lists **Data Science** as a partner-delivered PhD/DBA specialisation, partner unnamed, no page. A "DBA in Data Science" exists on a third-party eCampus portal, which is not an official source and uses a different institution name. |
| 11 | Hospitality, Travel & Tourism | Rushford | Absent from the official DBA listing entirely. |
| 12 | Logistics & Supply Chain Management | Rushford | Duplicate of S.No 1 (DBA in Supply Chain Management). No separate programme exists; creating a second page would duplicate content. |
| 13 | Global Leadership & Strategy | Rushford | Absent from the official DBA listing entirely. |
| 17 | PhD in Psychology | GAU | The official page exists in the graduate school's department list but carries **no programme detail** — title only, no curriculum, entry or fee data. |
| 19 | PhD in Law | GAU | Annotated **"(To be launched)"** on the official site; not yet running. |

**Recommendation:** leave all seven out of the database. Re-check rows 17 and 19 next
intake, since both are GAU pages that could be populated at any time. Rows 9–13 need a
decision from Rushford, not more searching — the official site simply does not publish
them.

---

## 5. How to ship it

Full detail is in **`docs/db-seeding-runbook.md`**. The short version:

```bash
python3 tools/qa_gate.py && python3 tools/dedupe_scan.py
python3 tools/build_catalog_data.py
python3 tools/validate_catalog_data.py
mysqldump -u USER -p DBNAME > backup-$(date +%F-%H%M).sql
php artisan db:seed --class=CatalogProgramsSeeder     # then run it a 2nd time
```

**Then activate, or the site will show nothing.** All rows are created with
`is_active = false` on purpose. Activate by category (not by level — the two
`Level 7 International Diploma` rows get missed otherwise) and clear the
`programs.listing.v2` cache afterwards. Both commands are in the runbook.

---

## 6. Tooling left behind

| Tool | Purpose |
|---|---|
| `tools/qa_gate.py` | Per-page style and length gate. Fails the build on AI tells, Americanisms, or out-of-range description, hero and SEO lengths. |
| `tools/dedupe_scan.py` | 8-word shingle scan across every page; separates factual repetition from stylistic duplication. |
| `tools/build_catalog_data.py` | Markdown → PHP generator, multi-category. The only way the seeder data files should ever change. |
| `tools/validate_catalog_data.py` | Pre-flight structural check, no PHP required. |
| `tools/build_index.py` | Rebuilds `output/programs/INDEX.md`. |

Run them in that order after any content change. The generated files under
`database/seeders/data/` are never hand-edited.

---

## 7. Known non-blocking gaps

- `careers` is prose-only on several pages (4 GAU rows and the awards where the awarding
  body publishes no route list). The validator warns; it is not an error.
- `image_url` is unset throughout — detail pages fall back to the default image.
- No fee amounts anywhere: neither Qualifi nor Gatehouse publishes prices, and Rushford's
  figures were not available from an official page in a citable form.
- `recognition` and `accreditation_groups` are intentionally empty. Nothing was claimed
  that an official source did not state.
