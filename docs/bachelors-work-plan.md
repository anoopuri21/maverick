# Bachelors build — working plan

Date: 2026-10-14. Branch: `arena/5f12f105-maverick`.

## Recon decisions

- Read the template, charter, completion report, runbook and completed Qualifi law page. `docs/catalogue-reconciliation.md` was absent at baseline; reconstruct from listing.pdf and seed data, never describe a repository count as a verified production count.
- Existing category: `bachelors`. ProgramSeeder has business management (sort 1), BscPsychologyProgramSeeder psychology (2), RushfordBbaProgramSeeder seven BBAs (10–16). Master payload uses 100–149. Doctorate uses 201–213; diploma 214–262. Reserve **263–271**, one slot per requested listing row even if blocked.
- Existing partners: `girne-american-university`, `university-of-the-west-of-scotland`. Bind by slug, not an invented database integer. UWS is declared in master_programs.php. DatabaseSeeder calls the legacy bachelor seeders and MasterProgramsSeeder before CatalogProgramsSeeder.
- Append S.No 70–78 to the single tracker; preserve all columns, UTF-8 BOM and CRLF.
- No existing bachelor content will be rebuilt. No Phase 8 decision will be applied without permission.

## Pipeline and milestones

1. Official-source research, one programme at a time; facts, exact quotations, access dates and numbered gaps. Shared facts are scoped, never assumed to apply to a partner delivery route.
2. Strategy, SEO draft, separate Humanizer edit, separate fact-check report, then approved organiser output. Process three requested rows per batch; a blocked row keeps its slot and does not acquire invented content.
3. Each batch: QA → whole-catalogue dedupe → guarded corrections → repeat gates → build → validate → index → commit → push.
4. Wire bachelors payload, document category-based activation, verify generated caveats remain visible, then final reconciliation and staging handover.
5. Ask whether to keep/deactivate the unlisted UWS MBA and whether to rename the UCA Global MBA; do not change either here.

## Implementation hazard found

The existing Markdown parser discards free prose in learning and careers. New-page warnings must be carried to visible fields in the generated payload. Do not silently lose the derived-coverage preamble. Regression coverage is required if parser/seeder behaviour changes.

## Baseline reconciliation

PDF re-read with pypdf: 136 listing rows. Existing seed catalogue: 121 rows = 120 listing matches + one extra UWS MBA. Nine pending bachelors + seven previously blocked rows explain the sixteen missing listing rows. If all nine can be verified, repository total would become 130 = 129 listing matches + one extra, **not** 130 matches out of 136. Actual activation cannot be verified here.
