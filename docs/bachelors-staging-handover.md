# Staging handover — nine requested Bachelors listing rows

**2026-10-14 · Branch `arena/a760cf28-maverick` · Code/content ready, NOT seeded or activated.**

## What can go to staging

Five new GAU BSc pages are approved in the existing five-tab format. They are sourced from `gau.edu.tr`, human-edited and fact-checked, then generated into [`bachelors_programs.php`](../database/seeders/data/bachelors_programs.php). All five are **inactive on creation** and belong to the existing `bachelors` category and existing GAU partner; no new tables or partners. Sort orders are deliberately left at their tracker numbers, leaving gaps for blocked rows.

| S.No | Page | Source caveat requiring visual review |
|---:|---|---|
| 70 | [BSc Tourism and Hospitality Management](../output/programs/bachelors/girne-american-university/bsc-tourism-and-hospitality-management-girne-american-university.md) | Le Chateau Lambousa training is an opportunity; career tab is empty, so no career chips were invented. |
| 71 | [BSc International Business Management](../output/programs/bachelors/girne-american-university/bsc-international-business-management-girne-american-university.md) | Two GAU pages disagree on **132 vs 120 local credits**; faculty separately says 240 ECTS. Attendance required, not advertised online. |
| 73 | [BSc Marketing](../output/programs/bachelors/girne-american-university/bsc-marketing-girne-american-university.md) | Internship course is not an employer guarantee; senior career titles are only GAU's examples. |
| 76 | [BSc Economics](../output/programs/bachelors/girne-american-university/bsc-economics-girne-american-university.md) | Four-year award, but published course grid stops at **Year 3**. The missing Year 4 is disclosed in visible copy. No invented career titles. |
| 77 | [BSc Management Information Systems](../output/programs/bachelors/girne-american-university/bsc-management-information-systems-girne-american-university.md) | Internship/Graduation Project are named modules; GAU's nine role examples are not placements. |

**Blocked, not silently skipped:** S.No **72** BSc Accounting and Finance Management (exact BSc award/relationship to Accounting unverified), **74** BSc Human Resources Management (current intake but title-only BSc page), **75** BSc Accounting (faculty BSc award but prospective page missing and separate active course unverified), **78** UWS BA (Hons) in Global Business (UWS publishes BA Global Business Level 9, not an honours specification). Their dossiers and tracker entries remain **Incomplete**, with no pages/seed data. **Seven earlier Doctorate listing rows are also Incomplete**; therefore the claim that all programmes except these four are ready is false. Across the 136-row listing, 11 remain unseeded. Details and official URLs: [`catalogue-reconciliation.md`](catalogue-reconciliation.md).

## Preflight already run in the workspace

1. `python3 tools/qa_gate.py` → **PASS 67/67 pages** (five new plus 62 previous); each new hero/overview/SEO range passed, with British-English style check.
2. `python3 tools/dedupe_scan.py` → **0 STYLISTIC** 8-word overlaps across all 67 pages; 109 acceptable factual overlaps.
3. `python3 tools/build_catalog_data.py` → three generated files; previous Doctorate/Diploma files were **unchanged**, Bachelors file has **five** GAU BSc rows only.
4. `python3 tools/validate_catalog_data.py` → **VALIDATION PASSED** for all 67 required catalogue rows, including the five inactive GAU BSc rows. The 44 warnings are non-blocking declared source gaps; **three new** pages intentionally have zero career chips because GAU does not name jobs there. Missing/empty data files or an active new row now fail validation.
5. `python3 -m unittest discover -s tools/tests -v` → **4/4 PASS** for missing-file, empty-file, inactive-row and complete-file checks. Laravel feature tests for no-new-reference/idempotency/rollback scenarios are supplied but need PHP on staging.
6. Inventory audit: **67** generated catalogue slugs versus **59** legacy seed slugs, with **zero collisions** (126 distinct definitions). This is not a DB count.
7. Tracker uses UTF-8 BOM + CRLF (enforced by `.gitattributes`), original 12 columns, S.No 1–78. [`INDEX.md`](../output/programs/INDEX.md) reports **67 approved / 11 Incomplete / 0 Pending**. Approved page audits and mapped/draft/edited records are in `docs/pipeline/bachelors/`.

**Limit:** this environment has no PHP, Composer, vendor folder or staging DB. There has been **no PHP lint, Laravel seed, browser render, active-site change or live row-count check**. Expected row counts are predictions from seed definitions, not observed staging data.

## Operator checklist (staging first)

- [ ] Back up staging and check `program_categories.slug = 'bachelors'` and `university_partners.slug = 'girne-american-university'` already exist; stop if either is missing. Lint the PHP and run `php artisan test --filter=CatalogSeedingSafetyTest` in a safe test environment.
- [ ] For **only five new GAU BScs**, run `php artisan db:seed --class=BachelorsProgramsSeeder` on staging. It cannot create a category or partner and does not touch the older 62 catalogue rows or 59 legacy programme rows. Repeat it to prove idempotency. If the prior **121** rows were present, total should become **126**; `bachelors` rises from **9** to **14**.
- [ ] If the older 62 catalogue rows are also missing, and all three categories plus GAU, Rushford, Gatehouse Awards and Qualifi partner records are already in the DB, use `php artisan db:seed --class=ExistingCatalogProgramsSeeder` instead (all 67). It fails without inserting anything if a prerequisite is missing. **Do not** run the legacy `CatalogProgramsSeeder` or full `DatabaseSeeder` for this existing-only rollout; those may create references.
- [ ] Confirm all five GAU records are inactive, non-featured and linked to the existing GAU partner. Compare legacy GAU BSc Business Management/Psychology, Rushford BBAs and all 50 master rows for unexpected changes. Only with the all-67 command, also check the previous 62 Doctorate/Diploma rows' content and activation.
- [ ] Inspect each new page's hero, snapshot, learning, accordion, fee labels, FAQs and SEO on mobile and desktop. Check especially the Economics Year 4 gap, three intentionally absent career tag sections, conditional hotel/internship text, elective/core split, and IBM credit discrepancy. Request updated GAU fee and entry decisions; do not infer online study or placement promises.
- [ ] **Only after individual approval**, activate the five generated slugs (not all BSc/category rows) and clear `programs.listing.v2`. Verify each public URL `/programs/{slug}` and repeat the count check.
- [ ] If needed, use the **Bachelors-only** rollback in [`db-seeding-runbook.md`](db-seeding-runbook.md), not the old Doctorate/Diploma rollback. Production seeding waits for staging and editorial sign-off.

Exact commands, targeted activation and rollback are in [`docs/db-seeding-runbook.md`](db-seeding-runbook.md). No decision was made about the pre-existing **extra UWS MBA** or the UCA **“Global MBA + Rushford”** title difference; both are outside the requested nine and require the user's permission before any edit.
