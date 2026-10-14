# Catalogue reconciliation — Bachelors source review

**Reconstructed 2026-10-14.** This file was not in the checkout at the start of this work; these counts are a **source-code / PDF reconciliation**, not an observation of the staging database. Evidence: `docs/listing.pdf` (five pages), `docs/program-tracker.csv` (S.No 1–78), legacy `ProgramSeeder`, `BscPsychologyProgramSeeder` and `RushfordBbaProgramSeeder`, and `database/seeders/data/{master_programs,doctorate_programs,diploma_programs,bachelors_programs}.php`, plus the dossiers under `docs/research/`. No live database was queried and no PHP seeder was executed here.

## Source inventory, without inflating the listing

| PDF block | Listed rows | Represented after this batch* | Source-limited/unseeded |
|---|---:|---:|---:|
| Rushford BBA | 7 | 7 (legacy `RushfordBbaProgramSeeder`) | 0 |
| Rushford MBA + MSc | 21 | 21 (`master_programs.php`) | 0 |
| Rushford doctoral topics | 13 | 8 (`doctorate_programs.php`) | 5 (tracker 9–13) |
| GAU BSc | 10 | 2 legacy (`ProgramSeeder`, `BscPsychologyProgramSeeder`) + **5 new** | **3** (tracker 72, 74, 75) |
| GAU MBA + Executive MBA + MSc | 26 | 26 (`master_programs.php`) | 0 |
| GAU PhD | 7 | 5 (`doctorate_programs.php`) | 2 (tracker 17, 19) |
| UWS BA (Hons) in Global Business | 1 | 0 | **1** (tracker 78) |
| UCA Global MBA + Rushford Business School | 1 | 1 **provisional mapping** to UCA `Global MBA` in the master file | Award/partnership wording unverified; see below |
| University of Wolverhampton Master of Laws | 1 | 1 (`master_programs.php`, titled Master of Laws (LLM)) | 0 |
| Gatehouse diplomas | 4 | 4 (`diploma_programs.php`) | 0 |
| Qualifi diplomas | 45 | 45 (`diploma_programs.php`) | 0 |
| **Total** | **136** | **125 mapped, including one provisional UCA mapping** | **11 unseeded**, plus the UCA naming question |

\* Counts are expected from repository seed definitions if all legacy seeders have already run. They are **not** claims about actual DB row counts or activation state. GAU BSc Business Management and Psychology are only counted, not rebuilt or re-fact-checked. Previous Doctorate/Diploma rows are counted from their existing generated files, not re-researched in this batch.

The master file has **50** rows. Forty-nine map by subject to listed Masters blocks, **including UCA's provisional Global MBA mapping**; its UWS `MBA in International Business` is **outside** the PDF (the UWS block lists a BA, not an MBA). Existing other sources add seven Rushford BBAs, two GAU BScs and 62 Doctorate/Diploma rows: **121 expected seeded rows before this batch**, 120 mapped to listed subjects plus the extra UWS MBA. Five approved BSc rows bring expected seeded programmes to **126** and expected listing-subject coverage to **125**; 136 − 125 = **11 unseeded listing rows**. If strict exact-award evidence is required, UCA remains provisional rather than an unconditional 125th match. **Do not remove or retitle** either existing MBA without user approval.

## The nine requested rows — one decision per listing entry

| Tracker S.No | Exact PDF title | Research/content | Seeder decision and evidence |
|---:|---|---|---|
| 70 | BSc Tourism and Hospitality Management | Complete / approved | GAU's [programme page](https://www.gau.edu.tr/en/prospective/program/tourism-and-hospitality-management-bsc) names a four-year English BSc and courses; its Career Opportunities tab is empty. Approved inactive with sourced learning coverage and no invented career tags. |
| 71 | BSc International Business Management | Complete / approved | [Prospective](https://www.gau.edu.tr/en/prospective/program/international-business-management-bsc) and [faculty](https://www.gau.edu.tr/en/faculty-of-business-economics/department_of_international_business_management.html) support the award, but conflict on **132 versus 120 local credits** (the faculty separately says 240 ECTS). This is disclosed; no online delivery claim. |
| 72 | BSc Accounting and Finance Management | **Incomplete** | Current 2026–2027 [YKS table](https://www.gau.edu.tr/aday/page/yks-2022-taban-ve-taban-puan-tablosu) names English-taught Accounting and Finance Management. The [live Turkish syllabus](https://www.gau.edu.tr/kibrisaday/program/muhasebe) calls its own department Accounting and degree `Lisans`, without spelling out a BSc **under the full listing title**. Its relationship to S.No 75 is unknown; no page/seed. |
| 73 | BSc Marketing | Complete / approved | GAU's [Marketing BSc](https://www.gau.edu.tr/en/prospective/program/marketing-bsc) publishes four years, core/elective subjects, internship unit and possible careers; [faculty](https://www.gau.edu.tr/en/faculty-of-business-economics/department_of_marketing.html) awards BSc. Career examples are not hiring promises. |
| 74 | BSc Human Resources Management | **Incomplete** | Current [YKS table](https://www.gau.edu.tr/aday/page/yks-2022-taban-ve-taban-puan-tablosu) lists the English-taught HR route; [English BSc link](https://www.gau.edu.tr/en/prospective/program/human-resources-management-bsc) has a title but no curriculum/degree panel. Do not borrow a Business Management or MBA HR module; no page/seed. |
| 75 | BSc Accounting | **Incomplete** | [Business faculty department](https://www.gau.edu.tr/en/faculty-of-business-economics/department_of_accounting.html) names **BSc Accounting (240 ECTS)**, but its [prospective BSc URL](https://www.gau.edu.tr/en/prospective/program/accounting-bsc) now says page not found and current YKS table has no separately named Accounting intake. Do not ascribe row 72's Accounting-department grid to a second independently verified award; no page/seed. |
| 76 | BSc Economics | Complete / approved with visible gap | [Prospectus](https://www.gau.edu.tr/en/prospective/program/economics-bsc) says **four years** yet the grid stops at **Year 3**. [Faculty](https://www.gau.edu.tr/en/faculty-of-business-economics/department_of_economics.html) confirms BSc; the page discloses the missing final-year modules and empty careers tab, with no fabricated units. |
| 77 | BSc Management Information Systems | Complete / approved | [GAU page](https://www.gau.edu.tr/en/prospective/program/management-information-systems-bsc) names the four-year English BSc, full grid, Internship and Graduation Project, plus potential career examples. No placement, vendor credential or online-study guarantee. |
| 78 | BA (Hons) in Global Business | **Incomplete** | UWS [TNE partner page](https://www.uws.ac.uk/international/partner-with-us/) (updated 9 June 2026) lists **BA Global Business (Level 9)** at ICBS in Sri Lanka, **not** BA (Hons) Global Business. UWS's London [BA (Hons) International Business](https://www.uws.ac.uk/study/undergraduate/undergraduate-course-search/international-business-with-pathways/) is a different title. No page/seed. |

Dossiers: `docs/research/girne-american-university_bsc-*.md`, `docs/research/girne-american-university_BACHELORS-SHARED.md` and `docs/research/university-of-the-west-of-scotland_ba-hons-global-business.md`. All nine rows are recorded in `docs/program-tracker.csv`; only the five supported titles appear in `output/programs/bachelors/girne-american-university/` and generated `bachelors_programs.php`.

## Reconciliation of counts and audit trail

- Tracker S.No **1–78**: **67** content Completed (62 earlier + five new) and **11** Research Incomplete (seven earlier + four above); **0 Pending**. Existing legacy MBA/BBA/BSc rows do not belong to that tracker.
- Generated catalogue files contain **67** programmes across `doctorate`, `diploma` and `bachelors`: 13 + 49 + 5. The new five have sort orders **70, 71, 73, 76, 77**, `is_active: false`, and existing `bachelors`/GAU bindings. The seeder requires those existing records; it does not create a new Bachelor partner/category.
- `python3 tools/qa_gate.py`: **PASS 67/67**; `python3 tools/dedupe_scan.py`: **0 STYLISTIC** across all 67 pages (109 factual overlaps); `python3 tools/validate_catalog_data.py`: **VALIDATION PASSED**, with 44 non-blocking warnings (three new empty career lists, the rest previous source gaps).
- **No PHP, Composer, vendor or database runtime here.** Staging operator must back up, run `CatalogProgramsSeeder`, check five inactive GAU rows, run it again to verify idempotency, review page/layout/caveats, and activate only approved new slugs. Detailed commands and rollback: `docs/db-seeding-runbook.md`.

## Separate decisions requiring the user's permission

1. `master_programs.php` already seeds UWS **MBA in International Business**, not listed in the PDF. It is not part of this Bachelor task. **Do not delete, reclassify or rewrite it without a separate instruction.**
2. PDF says **“Global MBA + Rushford Business School, Switzerland”** under UCA; existing master row is simply **“Global MBA”** under UCA. Whether the extra Rushford phrase is marketing, delivery or an award relationship is not established here. **Do not retitle, duplicate or silently describe a joint award. Ask before acting.**
3. GAU must reconcile the programme codes/certificate titles for **both** Accounting entries. UWS must confirm an exact **BA (Hons) in Global Business** specification if that listing row is to be published. HR needs its own official BSc syllabus. Until then the four statuses stay Incomplete.
