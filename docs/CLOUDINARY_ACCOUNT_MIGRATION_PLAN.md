# Cloudinary Account Migration — Safe Plan (old → new account, zero broken images)

> **Goal:** Naya (dusra) Cloudinary account configure karna. Purane account ki saari maverick images naye account pe migrate karni hain. Migration ke dauraan aur baad me **website pe ek bhi image break nahi honi chahiye**.
> **Status:** IMPLEMENTATION (steps 1-5/6 done — audit + merge + migrate + cutover + verify; tests need PHP+vendor to run) — decisions locked: scope = ALL assets, videos = EXCLUDED. Execution 1-by-1 phases me hogi.
> **Date:** 2026-09-08 · Related: `docs/MEDIA_LIBRARY_NOTES.md`, `docs/cloudinary-guide.md`, `docs/SHARED_HOSTING.md`

---

## 0. Strategy ek line me (kyu ye plan safe hai)

1. **Pehle files copy karo (purana account sirf READ hoga, kabhi modify nahi)** → site untouched rehti hai.
2. **Phir DB me URLs naye account pe point karo** — sirf un files ke liye jo naye account me **verify** ho chuki hain.
3. **Phir credentials switch karo** — naye uploads naye account pe.
4. **Purana account 2–4 hafte zinda rakho** (buffer) → kuch gadbad ho to rollback turant (DB backup restore + `.env` revert).
5. **Sabse aakhir me purana account band karo.**

**Golden rule:** Purana account tab tak delete/disable NAHI hoga jab tak Phase 4 verification + buffer period poora na ho. Is rule se rollback hamesha possible hai.

---

## 1. Background facts (verified — inhi pe plan tikka hai)

| # | Fact | Source |
|---|---|---|
| 1 | DB me **absolute URLs** stored hain (`media_assets.url` + denormalized `*_url` + settings JSON). Frontend unhe as-is serve karta hai. Credentials badalne se purane URLs NAHI badalte. | `MEDIA_LIBRARY_NOTES.md` §8, `cloudinary-guide.md` §4 |
| 2 | Naye account ka Upload API **purane account ke URL se directly fetch karke upload** kar sakta hai (remote HTTP URL = valid `file` param). Server pe download karne ki zaroorat nahi, server bandwidth bachat. | Cloudinary PHP SDK docs |
| 3 | Upload ke time **same `public_id` (folder path samet) preserve** kiya jaa sakta hai — folder auto-create ho jaata hai. Isse `cloudinary_public_id` column badalne ki zaroorat NAHI. | Cloudinary support docs |
| 4 | Har naye upload ka `/v<version>/` segment alag hoga. Isliye **blind string-replace (`old-cloud` → `new-cloud`) UNSAFE** — har asset ka actual naya `secure_url` upload response se record karke hi DB update hoga. | Cloudinary transformation-URL docs |
| 5 | `cloudinary_public_id` same rehne se cutover ke baad delete/sync naye credentials pe normally kaam karenge. | `CloudinaryService` study |
| 6 | Sandbox (ye environment) me prod DB + Cloudinary creds NAHI hain — **execution production/staging server pe hogi** (cPanel PHP binary se, `SHARED_HOSTING.md` ke hisaab se). Is repo me sirf tooling (artisan commands) banegi + tests. | verified: no vendor, no `.env` |

---

## 2. Phase 0 — Pre-flight & Audit (100% read-only, zero risk)

**Goal:** Kya migrate karna hai, iski poori list + koi surprise to nahi.

- [ ] **0.1 DB backup** — production ka full dump lo, safe jagah rakho. Counts note karo (`media_assets` total, `used/unused`, settings rows).
- [ ] **0.2 Cloud-name inventory** — naye audit command se (`media:audit-cloudinary-urls`, banana hai): har stored URL ka cloud name nikaalo. Expectation: sab `res.cloudinary.com/<OLD>/...`. Strays note karo:
  - Stored URLs me **transformations** (`/upload/w_500/...`) — mapping me special handling chahiye.
  - Non-Cloudinary: pexels/unsplash, YouTube thumbnails, local `public/assets` — **ye migrate NAHI honge, untouched rahenge**.
  - Dusre cloud names (agar koi ho) — alag se decide.
  - **[R1/R2]** Shared-mode guard check (`usesEnvFolder()` must be false) + legacy `-local`/`-testing` public_ids ki list + same-hash duplicate groups ki list (R3 merge input).
- [ ] **0.3 Volume estimate** — `SUM(size_bytes)` + row counts se naye account ka storage/plan sufficient hai ya nahi, confirm karo. API rate limits note karo.
- [ ] **0.4 Naya Cloudinary account banao** — credentials note karo. **`.env` abhi MAT badlo.** Dono accounts ka Admin API access verify karo.
- [ ] **0.5 Usage refresh** — Admin → Media Library → Refresh usage (ya `media:clean` dry-run) taaki `used` vs `unused` pata chale (scope decision ke liye).
- [ ] **0.6 Upload-freeze decision** — Migration window me naye uploads purane account pe jaayenge → unke liye **delta pass** (Phase 1.5) mandatory hai. (Alternative: window me admin uploads rokna — recommended NAHI, delta pass kaafi hai.)

**Exit criteria:** Inventory report ready, naye account credentials ready, scope (all vs used-only) decided. Site untouched.

---

## 3. Phase 1 — File migration, purana → naya account (site untouched)

**Goal:** Saari files naye account me, **same `public_id`**, bina website ko touch kiye.

### 3.1 Tooling banana hai (next implementation task)

Naya artisan command: **`media:migrate-account`** (design):

```
Source: purane account ki alag env vars (CLOUDINARY_SOURCE_CLOUD_NAME / _API_KEY / _API_SECRET)
        — taaki main CLOUDINARY_* vars ko haath na lagana pade.
Dest:   naye account ki vars (pehle CLOUDINARY_DEST_* me, cutover ke baad wahi main banengi).

Har asset ke liye:
  1. old secure_url + public_id lo (media_assets row se).
  2. NEW account pe upload: file = old secure_url (Cloudinary-to-Cloudinary fetch),
     options: public_id = <EXACT same public_id>, overwrite = false.
  3. Response ka secure_url + bytes record karo → mapping (old_url → new_url).
  4. Batch + throttle (existing commands jaisa 250ms), --limit, --dry-run,
     resumable state (migration log table/JSON), failure log + retry.
```

- Legacy settings URLs (9 direct-upload fields: site logos, CEO, OurStory…) — inke liye `media_assets` row NAHI hai, to command inhe **settings scan karke** alag list se migrate karega (aur mapping me rakhega).
- Videos (hero video Cloudinary URL): scope me hua to `resource_type: auto` se migrate (image-only validation is command pe apply NAHI hogi).
- **[R1]** Startup guard: `usesEnvFolder() === true` → HARD-FAIL (naye account pe env-suffix folder banana impossible).
- **[R2]** Legacy `-local`/`-testing` public_ids: naye account me **shared path pe normalize** (mapping me old→new public_id dono record). Shared-path collision ho to SKIP + manual review.
- **[R3]** Same-hash group: canonical row ki file ek baar migrate (pre-merge ke baad duplicates rehte hi nahi; merge §13-R3 ke hisaab se cutover se pehle).

### 3.2 Execution (server pe, chhote batches me)

```bash
php artisan media:migrate-account --dry-run        # pehle hamesha preview
php artisan media:migrate-account --limit=200     # chhote batches (shared hosting safe)
# ... repeat till 0 remaining
php artisan media:migrate-account --verify        # naye account listing vs mapping count+bytes match
```

### 3.3 Delta pass (mandatory)

- Migration window me aaye naye uploads (purane account pe) → command dobara chalao, sirf missing `public_id` migrate honge (idempotent).

**Exit criteria:** Mapping 100% complete, naye account pe har file HEAD-check OK, purana account untouched (sirf reads hue). **Site abhi bhi purane URLs pe — zero user impact.**

---

## 4. Phase 2 — DB cutover (URLs naye account pe) — sabse sensitive step

**Goal:** DB ke saare Cloudinary URLs naye account ke **verified actual URLs** se replace.

### 4.1 Pehle: fresh DB backup (mandatory, cutover se turant pehle)

### 4.2 Update order (naya command: `media:cutover-account --dry-run` / `--confirm`)

1. `media_assets.url` ← mapping se actual naya `secure_url` (public_id match karke). Normal rows ka `cloudinary_public_id` **unchanged**; **[R2]** legacy-normalized rows ka `cloudinary_public_id` + `folder` bhi mapping se update (shared path).
2. Denormalized Eloquent columns (`logo_url`, `image_url`, …) ← apne `*_asset_id` wale asset ka naya URL. (Asset-linked rows only — no guessing.)
3. Settings JSON (`seo.og_image_url`, repeater `image`, …) ← pehle `*_asset_id` linkage se, phir bache legacy direct URLs public_id mapping se.
4. **Koi bhi URL jiska mapping me verified entry NAHI hai → use SKIP + report** (kabhi blind-guess replace nahi).

### 4.3 Cutover verification (same run me)

- Query check: `media:audit-cloudinary-urls` dobara chalao — `cloud_names` me sirf naya cloud hona chahiye (0 old-cloud refs, sirf jaan-boojh-ke-chhode externals ke saath).
- `MediaUsageService::refresh()` + `media:clean` dry-run — counts pehle jaisi honi chahiye.
- Admin Media Library me random previews kholo (naye URLs load hone chahiye).

**Rollback (agar kuch galat lage):** DB backup restore karo → saare URLs wapas purane account pe (jo alive hai) → site turant normal. `.env` abhi tak purana hai, isliye rollback = sirf DB restore.

**Exit criteria:** 0 unverified old-cloud references, admin previews OK.

---

## 5. Phase 3 — Credential switch (naye uploads naye account pe)

- [ ] `.env`: `CLOUDINARY_CLOUD_NAME/API_KEY/API_SECRET` → naye account. Baaki folder flags same (`UPLOAD_FOLDER`, `ENV_FOLDER=false`, `DISK_ENV=shared`).
- [ ] `php artisan config:clear` + shared-hosting optimize script (`scripts/shared-hosting-optimize.sh` order follow karo).
- [ ] Test upload: Media Library me 1 test image upload → naya cloud name wala URL bana? → test row delete karo.
- [ ] `php artisan media:sync-cloudinary --dry-run` → expectation: **0 to-create** (sab pehle se mapped).
- [ ] Temp migration env vars (`CLOUDINARY_SOURCE_*`, `CLOUDINARY_DEST_*`) `.env` me rakho abhi (rollback/decommission tak), document karo.

**Exit criteria:** Naye uploads naye account pe, purane pages naye URLs se load. **Purana account ABHI BHI ALIVE (billing on).**

---

## 6. Phase 4 — Verification (prove karo: zero broken images)

- [ ] **Automated URL check** (naya command `media:verify-urls`, banana hai): DB ki har distinct image URL pe HTTP HEAD/GET → non-200 ki report. Expectation: 0 failures (externals ko allowlist karo).
- [ ] **Page spot-checks:** homepage, programs list/detail, blogs, news, accreditations, global pathways pages, contact — har hero/gallery/logo section.
- [ ] **Admin spot-checks:** Media Library grid, 4–5 alag resources ke image previews.
- [ ] **Buffer period: 2–4 hafte purana account ON rakho.** Kyu: browser/CDN cache, bheje hue emails, WhatsApp/FB-scraped og images, external hotlinks — ye sab purane URLs hit kar sakte hain.
- [ ] Buffer ke dauraan purane account ka usage/bandwidth monitor karo (girna chahiye ~zero ki taraf).

**Exit criteria:** 0 broken URLs + buffer period complete.

---

## 7. Phase 5 — Decommission (purana account band)

- [ ] Final re-verify (Phase 4 ka URL check dobara).
- [ ] Purana Cloudinary account: files delete ya account close (billing stop).
- [ ] `.env` se `CLOUDINARY_SOURCE_*`/`CLOUDINARY_DEST_*` temp vars hatao.
- [ ] Docs update: `cloudinary-guide.md` (naya cloud name note), `MEDIA_LIBRARY_NOTES.md` (migration entry), ye plan → `Executed on <date>` mark karo.
- [ ] Migration tooling rakho ya hatao — decide karke document karo (future account-switch me kaam aayegi, rakhna recommended).

---

## 8. Edge cases & decisions log

| # | Edge case | Handling |
|---|---|---|
| 1 | URL me `/v123/` version segment | Blind replace FORBIDDEN — sirf upload response wale actual `secure_url` use honge. |
| 2 | Stored URLs me transformations | Phase 0.2 audit me detect; mapping public_id-base pe + transform preserve karke rebuild. |
| 3 | Legacy `-local` folders | **[R2]** Shared path me normalize (public_id change, mapping authoritative); collision → skip + review. |
| 13 | Local-dev DB alag hai | **[R4]** Cutover ke baad local DB refresh mandatory; local pe `media:sync-cloudinary` MANA (dup rows). |
| 14 | Same file 2 env me upload → 2 Cloudinary copies | **[R5]** Interim: uploads prod-only. Proper fix (deterministic public_id) alag future task — decision pending. |
| 4 | 9 legacy direct-upload fields (settings) | Settings-scan se migrate + mapping; `media_assets` row banaye BINA (scope creep nahi — sirf URL cutover). |
| 5 | Videos (hero video etc.) | **EXCLUDED (locked)** — videos purane account pe rahenge. Consequence: purana account videos ke liye alive rakhna padega (§11 dekho). |
| 6 | Non-Cloudinary URLs | Kabhi touch nahi (pexels, YouTube, local assets). |
| 7 | Migration window me naye uploads | Delta pass (Phase 1.5) mandatory. |
| 8 | Shared hosting limits | Chhote batches (`--limit`), CLI only, koi daemon/queue nahi, cPanel PHP binary. |
| 9 | API rate limits | 250ms throttle (existing commands jaisa) + retry-on-429. |
| 10 | Bheje hue emails / scraped og images | Buffer period (purana account alive) se cover; buffer ke baad purane email images break ho sakte hain (accepted, document). |
| 11 | `media:sync-cloudinary` cutover ke baad | Naye account pe chalana safe — `public_id` unique check se duplicates nahi banenge. |
| 12 | Rollback window | Phase 5 (purana account delete) tak rollback possible. Uske baad rollback = naye account se wapas (same tooling, reverse direction). |

---

## 9. Kya is repo me banega (implementation checklist, NEXT tasks)

1. ✅ `media:audit-cloudinary-urls` — DONE (`app/Services/MediaAuditService.php` + `app/Console/Commands/AuditCloudinaryUrlsCommand.php` + `tests/Feature/MediaAuditTest.php`). Covers: cloud names, transforms, externals, legacy-prefix list, same-hash groups, bytes, shared-mode guard. Bonus: embedded-URL regex scan (rich-text `<img>` inside text/JSON columns) — usage-scan se superset hai.
2. ✅ `media:migrate-account` — DONE (`app/Services/MediaMigrationService.php` + `app/Console/Commands/MigrateAccountCommand.php` + `tests/Feature/MediaMigrationTest.php` + `database/migrations/2026_09_08_000001_create_media_migration_map_table.php` + `app/Models/MediaMigrationMap.php`). Fetch-upload old secure_url → DEST exact public_id (`overwrite=false`, `unique_filename=false`, NO transforms — bytes 1:1), mapping-only writes (library rows + source account never touched). Resumable/idempotent, `--limit` batches, 250ms throttle (tunable), failed auto-retry, `--verify` (DEST listing vs mapping count+bytes). **[R1]** env-folder/source==dest hard-fail; **[R2]** legacy→shared normalize, collision/new-pid-claimed → skip+review; **[R3]** canonical uploads once, rest share (deferred across `--limit` splits, converges next run); videos/foreign-cloud/pid-less rows skipped with reasons. `CloudinaryService` extended with DEST client (`uploadRemoteImage`/`destResource`/`listDestImagesByPrefix`, `CLOUDINARY_SOURCE_*`/`CLOUDINARY_DEST_*` in `config/services.php`). Fix 2026-09-08: failed-row self-claim retry bug (`claimedNew` now owner-mapped, own target never blocks its retry). Schema fact: `media_assets.cloudinary_public_id` is UNIQUE + NOT NULL ⇒ same-pid asset rows impossible; same-pid dedupe tested cross-source (asset + settings) instead.
3. ✅ `media:merge-duplicates` — DONE (`app/Services/MediaMergeService.php` + `app/Console/Commands/MergeDuplicatesCommand.php` + `tests/Feature/MediaMergeTest.php`). Canonical = current disk_env row, else lowest id. Repoints FK cols + JSON `*_asset_id` + exact URLs + transformed variants (rebuilt on canonical pid, version dropped) + rich-text embeds. Dry-run default, per-dup transaction, idempotent. Shared traits: `app/Services/Concerns/ExtractsStoredUrls.php` + `ScansMediaTables.php` (audit refactored onto them, behavior identical). Fix 2026-09-08: skip-list compares schema-stripped names via `baseTable()` — SQLite returns qualified `main.*` names which bypassed the skip list (media_assets self-scan + recycle-log rewrites), caught by tests + regression tests added.
4. ✅ `media:cutover-account` — DONE (`app/Services/MediaCutoverService.php` + `app/Console/Commands/CutoverAccountCommand.php` + `tests/Feature/MediaCutoverTest.php`). Mapping-only DB swap, dry-run default (`--confirm` applies), zero API calls (no DEST creds needed). Order: media_assets urls → Eloquent cols via `*_asset_id` linkage → settings JSON (linkage first, legacy-direct via pid mapping). Unmapped → skip + report, never guess. Transforms rebuilt on new pid (same chain, version dropped). **[R2]** migrated rows move url+pid+folder; shared rows url-only (UNIQUE(pid) safety). Externals/foreign-cloud/videos never touched. Linkage mismatch → linkage wins + repaired count. Every live run ends with a source-cloud residue scan (§4.3: image residue must be 0, videos tracked separately); residue > 0 → FAILURE exit. Command prints backup + R4 (local DB refresh) reminders. Idempotent (crash-safe via rerun). Fixes 2026-09-08: (1) `media_migration_map` added to `schema_skip_tables` — cutover was rewriting its own mapping audit trail (old_url := new_url), and the same gap made merge/usage/audit treat bookkeeping rows as live refs; (2) asset↔mapping join falls back to new_pid so already-cut R2 rows report current (not bogus unmapped) on reruns.
5. ✅ `media:verify-urls` — DONE (`app/Services/MediaVerifyService.php` + `app/Console/Commands/VerifyUrlsCommand.php` + `tests/Feature/MediaVerifyTest.php`). Requests every distinct stored image URL (audit-style collection: library incl. trashed + all scanned columns; HEAD with ranged-GET fallback on 405/501; 100ms throttle, tunable). Any LIVE failure (any host) → FAILURE exit; trashed-only failures are warnings; videos skipped; bot-hostile hosts skippable via `media.verify_skip_hosts` / `--allow-host=*` (config in `config/media.php` + `.env.example`). Tests use `Http::fake` (zero real network).
6. Tests: mapping/cutover/merge logic ke unit/feature tests (Cloudinary API stub karke — real creds sandbox me nahi hain).
7. ✅ Migration mapping store — DONE: option (a) **table `media_migration_map`** (migration `2026_09_08_000001_...` + model `MediaMigrationMap`). Statuses migrated/shared/skipped/failed + reason/attempts/audit cols; cutover reads ONLY this table.
8. ✅ `.env.example` update — DONE: `CLOUDINARY_ENV_FOLDER=false` + `CLOUDINARY_DISK_ENV=shared` + `CLOUDINARY_SOURCE_*`/`CLOUDINARY_DEST_*` migration vars + `MEDIA_MIGRATION_THROTTLE_MS` (docs-only change).

---

## 10. Execution commands (server pe, jab phase aaye — abhi RUN mat karna)

```bash
# Phase 0
php artisan media:audit-cloudinary-urls
php artisan media:clean                    # dry-run: used/unused counts

# Phase 1 (batches me, har batch verify ke saath)
php artisan media:migrate-account --dry-run
php artisan media:migrate-account --limit=200
php artisan media:migrate-account --verify
php artisan media:migrate-account --limit=200   # delta pass (window uploads)

# R3 pre-migration merge (fresh DB backup recommended before --confirm)
php artisan media:merge-duplicates --dry-run
php artisan media:merge-duplicates --confirm

# Phase 2 (fresh DB backup ke BAAD)
php artisan media:cutover-account --dry-run
php artisan media:cutover-account --confirm
php artisan media:clean                    # counts sanity check

# Phase 3
# .env update + config:clear + optimize script, phir:
php artisan media:sync-cloudinary --dry-run   # expect 0 to-create

# Phase 4
php artisan media:verify-urls
```

---

## 11. Decisions (locked 2026-09-08, user-confirmed)

- **Q1. Scope = ALL assets** ✅ — `media_assets` ki har row (used + unused) + legacy settings images + Cloudinary orphans check. Purana account images se poori tarah khaali ho jaayega.
- **Q2. Videos = EXCLUDED** ✅ — Cloudinary video URLs (hero video etc.) migrate NAHI honge, purane account/URLs pe hi rahenge.
  - ⚠️ **Implication:** Jab tak videos purane account pe hain, **purana account poori tarah band NAHI ho sakta** (Phase 5 partial hoga — images delete, account alive for videos). Videos ka alag mini-task baad me karna hoga, ya old account videos ke liye chalta rahega. (Revision discussion me confirm karna hai.)
- **Q3. Next step = APPROVED → implementation running** ✅ — revision round complete (§12–§13 locked), ab 1-by-1 tooling ban rahi hai (§9 checklist).

## 12. Revision notes (discussion round — user input pending)

- **R0 (user requirement, locked):** Local + production **ek hi shared folder** ke assets use karenge. Alag-alag env folders me double upload nahi hoga, Cloudinary pe load kam rahega. Poora detail §13 me.
- **R2 (locked):** Legacy `-local` files naye account me **shared path me normalize** hongi (§13-R2).
- **R5 (locked):** Deterministic public_id = **future task**. Interim rule: content/admin uploads production pe only (§13-R5).
- Known open point: videos exclude hone ke baad Phase 5 (decommission) ka exact shape — old account kab tak alive, video migration future task me ya kabhi nahi.

## 13. Single shared folder guarantee (REVISION — user requirement)

**Goal statement:** Local aur production dono `maverick-academy/...` (ONE folder) use karen. Naye account pe kabhi `-local`/`-testing` jaise env-suffix folders na banen. Same content dobara upload na ho.

### R1. Shared mode har jagah LOCK (code + config)

- Naye account ka base folder: `maverick-academy` (same naam, fresh account).
- Local + production **dono** `.env` me explicit:
  ```env
  CLOUDINARY_ENV_FOLDER=false
  CLOUDINARY_DISK_ENV=shared
  ```
- `.env.example` me ye dono lines add hongi (abhi `CLOUDINARY_ENV_FOLDER` documented hi nahi hai — gap verified).
- Migration/cutover tooling me **guard**: agar `usesEnvFolder() === true` to command HARD-FAIL (naye account pe env-suffix upload impossible).
- Verified current state: `config/services.php` default already `false` → shared. Ye revision use document + enforce karti hai, behavior flip nahi.

### R2. Legacy `-local` files: naye account me SHARED path me normalize (LOCKED ✅)

- Problem: purane account me `maverick-academy-local/...` files hain jo prod DB ke stored URLs use kar rahe hain (normalizer ne sirf `folder` metadata fix kiya tha, `public_id`/URL abhi bhi `-local` pe point karte hain).
- Rule: aisi file naye account me **shared path** pe jaayegi (`maverick-academy-local/x` → `maverick-academy/x`), mapping me `old_public_id → new_public_id + new_url` record hoga, cutover me us row ka **`url` + `cloudinary_public_id` + `folder` teeno update** honge (mapping authoritative hai).
- Collision safety: agar shared-path `public_id` pehle se kisi DOOSRI content se occupied hai to migrate SKIP + manual-review report (blind overwrite kabhi nahi).
- Alternative (NOT recommended): exact `-local` path preserve → naye account me phir 2 folders ban jaayenge, jo is requirement ke against hai.
- Note: ye exception sirf legacy-prefixed files ke liye hai — normal files ka `public_id` unchanged rehta hai (§1 fact #3).

### R3. Same-content duplicates: migrate ONCE (Cloudinary load kam)

- `media:sync`/`normalize` ke baad bhi same-hash ki multiple rows ho sakti hain (`is_duplicate=true`).
- Rule: same `hash` group me **canonical row** (lowest id, shared-folder preferred) ki file ek baar migrate hogi. Baaki same-hash rows ki files dobara upload NAHI hongi.
- Schema fact (verified 2026-09-08 via test failure): `(hash, disk_env)` UNIQUE hai, isliye duplicate groups **hamesha multiple `disk_env` values span** karte hain. Merge design isi assumption pe banega.
- Pre-migration merge (cutover se pehle, alag confirm ke saath): duplicate rows ke `*_asset_id` FK + denormalized URLs canonical row pe repoint karke dup rows soft-delete (existing `MediaFolderNormalizer::repointReferences` pattern reuse hoga). Merge ke baad migrate — to duplicate content Cloudinary pe ek hi baar jaata hai.
- Soft-deleted (trashed) rows: scope=ALL ke hisaab se inki files BHI migrate hongi (withTrashed), taaki future Restore ke baad URL purane (band) account pe na point kare.

### R4. Dev-environment (local DB) refresh — alag DBs hone ki wajah se MANDATORY step

- Fact: local (sqlite, dev) aur production (MySQL) ke DB **alag** hain. Prod cutover se local DB ke URLs nahi badlenge.
- Rule: cutover ke baad har developer apna **local DB post-cutover prod backup/seed se refresh** karega. Tab local + prod dono naye account ke same shared folder ke same assets use karenge.
- Warning: refresh ke bajaye local pe `media:sync-cloudinary` chalane se synthetic-hash duplicate rows ban jaayengi — ye karna MANA hai (docs me likhna hai).
- Buffer period me local purane account se images dikhata rahega (broken nahi, bas purana source) — refresh ke baad naya source.

### R5. Future hardening: deterministic `public_id` (cross-env Cloudinary-level dedupe) — LOCKED ✅ (future task, is migration me NAHI)

- Root cause (verified): dedupe aaj per-database hai (`MediaAsset::where('hash')` current env ke DB me). Same file local + prod dono me upload ho to Cloudinary pe 2 files banti hain chahe folder shared ho.
- Proper fix: naye uploads ka `public_id` content-hash se derive ho (e.g. `<folder>/<sha256-12>`), `unique_filename=false + overwrite=false` ke saath — Cloudinary same `public_id` pe dobara file banane ke bajaye **existing asset return** karta hai. Matlab: upload once (kahi se bhi), use everywhere (har env se), Cloudinary pe 1 copy.
- Ye `MediaLibraryService::store()` ka behavior change hai (naming scheme) + purani random-name files as-is rehengi — isliye **alag task** ke roop me recommended hai, is migration ke andar NAHI (risk isolation).
- Interim process (zero code): content/admin uploads **production pe only**; local sirf refreshed-DB se read karega.
