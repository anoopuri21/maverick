# Runbook — existing-reference catalogue and Bachelor admin drafts

**Scope (15 October 2026):** one existing-only command loads **70 catalogue definitions**: **67 approved pages** (13 Doctorate, 49 Diploma, 5 GAU BSc) **plus 3 incomplete Bachelor admin drafts** (GAU Accounting and Finance Management, GAU Human Resources Management, UWS BA Global Business (Level 9)). There are **8 distinct Bachelor catalogue rows, not 9**. The original BSc Accounting (S.No 75) has no new row: its distinctness from the Accounting and Finance Management offering is unverified. The other 7 Research Incomplete Doctorates remain outside this batch.

This is a **source-code plan, not a database observation or deployment report**. No PHP, Composer, `vendor/` or staging/live DB was available in the authoring workspace, so PHP feature tests and DB seeding must run on your environment. [Official evidence, exact quotes and numbered gaps](bachelors-admin-draft-evidence-2026-10-15.md) explain which draft fields remain blank.

## Which sources and commands

| Source / seeder | Effect |
|---|---|
| `output/programs/**/*.md` → `tools/build_catalog_data.py` → `database/seeders/data/{doctorate,diploma,bachelors}_programs.php` | **67 approved pages only**; each has a Completed tracker row and passes the full QA gate. Generated PHP is never edited by hand. |
| `docs/bachelors-admin-drafts.json` → same builder → `database/seeders/data/bachelors_admin_drafts.php` | **3 incomplete, separately marked admin drafts**. Tracker stays Research Incomplete; SEO, fee, careers and other unverified sections are empty. No page under `output/programs/`. |
| `php artisan db:seed --class=ExistingCatalogProgramsSeeder` | **Preferred single existing-only staging command:** the 70 rows above, plus copy/SEO refresh for **up to 55 already-existing** in-scope Master/BBA legacy rows (48 Master, 7 BBA). Missing legacy rows are reported, not created. The 2 unapproved UWS/UCA MBA rows are excluded; the 2 older GAU BScs are not refreshed. In production add `--force`. |
| `php artisan db:seed --class=BachelorsProgramsSeeder` | **Alternative, not the requested full rollout:** only the 5 approved GAU BSc pages, no 3 drafts or legacy updates. |
| `CatalogProgramsSeeder` / `DatabaseSeeder` | **Do not use for this existing-only rollout**. The bootstrap path may create Doctorate/Diploma references and does **not** contain the 3 admin drafts; the full DB seeder has other unrelated effects. |

**No new tables, categories or partners.** The safe command requires existing `program_categories` rows `bachelors`, `doctorate`, `diploma`, and existing `university_partners` rows `girne-american-university`, `rushford-business-school`, `gatehouse-awards`, `qualifi`, **`university-of-the-west-of-scotland`**. In particular, `GlobalUniversityPartnersSeeder` writes to **`gup_partner_universities`**, a *different table*; that row does not meet the programme's UWS `UniversityPartner` prerequisite. If the UWS row is absent in the target DB, **stop** and resolve the data prerequisite with the owner; do not fabricate a partner, remap the BA to GAU or run a bootstrap seeder to make the command pass.

The PHP seeder checks the **70 source rows' expected counts**, inactive first-insert defaults and the 3 official draft identities/URLs before DB writes. All reference lookups precede programme insertion inside a single transaction; a missing reference, slug/award-subject collision (including an older Accounting or Global Business row under another slug), changed programme identity, malformed legacy source or ambiguous FAQ slot aborts and rolls back. The offline validator also requires Completed approval for the 5 Bachelor pages **and separately** checks tracker Incomplete/Admin draft status, exact titles, official domains and blank draft fields. A blank field never passes the public-page QA requirement.

Approved rows refresh authored copy/SEO and numbered FAQs on re-seed while preserving activation, featured state, sort order, canonical URL, media and other editorial toggles. **Draft rows are insert-once:** re-seeding skips their copy/SEO/FAQs altogether after the partner, category, title and level identity checks, preserving all client-filled fields **and** activation. A previously activated draft generates a warning, not a silent reset. Never change a draft's title/slug/partner casually in Filament; reconcile a revised award and its identity with the source manifest first. Existing legacy rows are updated only if their identity/partner/category matches; the 2 excluded MBA rows are not read/written.

## Staging preflight — separate test database

1. Deploy the reviewed code and **all 4 generated catalogue files** to staging. Back up staging with your normal database procedure and snapshot existing rows, categories, partners, activation/featured/sort settings, SEO and FAQ IDs, both excluded MBA rows and any client-edited draft fields. Do **not** run the Laravel test suite against the live database: these feature tests use `RefreshDatabase`.
2. In the Laravel project root, run the source gates and PHP lint. Regenerate/index-check on the review branch or staging only, **not** by hand-editing PHP on the live server:

   ```bash
   python3 tools/qa_gate.py                     # 67/67 approved pages
   python3 tools/dedupe_scan.py                  # 0 STYLISTIC
   python3 tools/build_catalog_data.py          # 4 generated data files
   python3 tools/build_index.py                 # approved pages separate from drafts
   python3 tools/validate_catalog_data.py       # 70 rows / 4 files; 8 Bachelor rows
   python3 -m unittest discover -s tools/tests -v
   php -l database/seeders/CatalogProgramsSeeder.php
   php -l database/seeders/ExistingCatalogProgramsSeeder.php
   php -l database/seeders/BachelorsProgramsSeeder.php
   php -l database/seeders/data/bachelors_admin_drafts.php
   php -l database/seeders/data/bachelors_programs.php
   # Only with an isolated test DB, never the production DB:
   php artisan test --filter=CatalogSeedingSafetyTest
   ```

3. On the **target staging DB**, confirm that **all 3 categories and 5 university-partner rows** already exist. You may use these read-only SQL queries in your DB client, or the terminal Tinker preflight shown in the production section:

   ```sql
   SELECT id, slug FROM program_categories
   WHERE slug IN ('bachelors', 'doctorate', 'diploma');
   SELECT id, slug FROM university_partners
   WHERE slug IN ('girne-american-university', 'rushford-business-school',
                  'gatehouse-awards', 'qualifi', 'university-of-the-west-of-scotland');
   ```

   Expect **3** category rows and **5** `university_partners` rows. Confirm any *already-existing* seed slug belongs to the expected title, level, category and partner. Inspect for a pre-existing separate GAU BSc Accounting record; **do not delete or overwrite it** without resolving S.No 72/75 with GAU and the client.
4. Seed on staging and compare the before/after snapshot. A repeated command must be idempotent and **not** erase client-filled draft fields:

   ```bash
   php artisan db:seed --class=ExistingCatalogProgramsSeeder
   # Compare identities, counts, public 404s for newly inserted inactive rows,
   # preserved admin edits and both excluded MBA rows; then repeat once:
   php artisan db:seed --class=ExistingCatalogProgramsSeeder
   ```

   If the complete older **121-row baseline** (59 legacy definitions including the 2 excluded MBAs, plus 62 Doctorate/Diploma rows) was actually present and none of the 8 Bachelor catalogue rows was present, the expected overall total is **129** (not 129 new inserts), with **17** Bachelors across old GAU BScs, Rushford BBAs and this batch. Actual counts depend on the target DB; do not assume this baseline without querying it. A full safe rollout creates at most **70** catalogue rows and **zero** legacy rows.

## Live server terminal — only after staging sign-off and a live DB backup

Run from the **deployed Laravel project root** after the merged code **and all generated files** are on the server. Stop on any failing preflight. Do not run `php artisan migrate:fresh`, bare `php artisan db:seed`, the test suite, or `GlobalUniversityPartnersSeeder` on the live DB.

```bash
test -f artisan && test -f vendor/autoload.php
python3 tools/qa_gate.py
python3 tools/dedupe_scan.py
python3 tools/validate_catalog_data.py
php -l database/seeders/CatalogProgramsSeeder.php
php -l database/seeders/ExistingCatalogProgramsSeeder.php
php -l database/seeders/data/bachelors_admin_drafts.php
php artisan tinker --execute='$categories=["bachelors","doctorate","diploma"]; $partners=["girne-american-university","rushford-business-school","gatehouse-awards","qualifi","university-of-the-west-of-scotland"]; $gotCategories=\Illuminate\Support\Facades\DB::table("program_categories")->whereIn("slug",$categories)->pluck("slug")->all(); $gotPartners=\Illuminate\Support\Facades\DB::table("university_partners")->whereIn("slug",$partners)->pluck("slug")->all(); $missing=array_merge(array_diff($categories,$gotCategories),array_diff($partners,$gotPartners)); if ($missing) throw new \RuntimeException("Missing existing category/UniversityPartner: ".implode(", ",$missing)); echo "Prerequisites present: 3 categories, 5 UniversityPartner rows\n";'
# STOP HERE until the production DB is backed up and staging comparison approved.
php artisan db:seed --class=ExistingCatalogProgramsSeeder --force
```

**Read-only after-seed spot check** (same deployed project root):

```bash
php artisan tinker --execute='$files=["doctorate_programs.php","diploma_programs.php","bachelors_programs.php","bachelors_admin_drafts.php"]; $slugs=collect($files)->flatMap(fn($f)=>collect((require database_path("seeders/data/".$f))["programs"])->pluck("slug"))->all(); $found=\App\Models\Program::query()->whereIn("slug",$slugs)->count(); echo "Catalogue seed slugs found: {$found}/70\n"; if ($found!==70) throw new \RuntimeException("Catalogue seed count differs from 70"); $drafts=require database_path("seeders/data/bachelors_admin_drafts.php"); foreach ($drafts["programs"] as $row) { $p=\App\Models\Program::query()->where("slug",$row["slug"])->firstOrFail(); echo $p->slug." | ".$p->title." | ".$p->universityPartner->slug." | active=".(int)$p->is_active."\n"; }'
```

Compare the 3 draft `is_active` values with the **before snapshot**: only *newly inserted* drafts must be `0`; an already reviewed/activated row remains unchanged. Confirm in Filament that all 8 Bachelor catalogue records are editable and that the 3 new drafts have blank unsupported details. `/programs/{slug}` for a new inactive draft should return **404**, and the public listing should exclude it. The two excluded MBA rows and older GAU BScs must be unchanged. The seeder clears public programme caches on success. Do **not** bulk-activate Bachelors: the 3 drafts are not approved for a public page. Promotion requires new attributable evidence, individual content/SEO QA and client editorial approval.

## Recovery and future changes

For any wrong copy or client-field overwrite, restore the **pre-run DB backup or audited per-row snapshots**, not an indiscriminate category delete. Stop and investigate a missing UWS partner or identity mismatch; rerunning another seeder is not a fix. A newly inserted unwanted draft may be removed only after inspecting downstream records and any client edits, with its **exact slug**, related SEO/FAQs and a live backup. Never delete the Bachelors category or GAU/UWS partner as a rollback.

If the universities later supply an exact award specification, update the appropriate dossier and tracker. Promote only the verified draft to a **new fully approved page** through the Humanizer/Fact-Checker, `qa_gate`, dedupe, builder, validator and a reviewed migration of its identity/source; remove/adjust that draft's source row deliberately before changing its seeder count. Do **not** duplicate the GAU Accounting award or silently relabel the Level 9 BA as Honours. For a plain approved-page addition, update `expectedProgramCounts()` only after the new page has passed the public-page gate.
