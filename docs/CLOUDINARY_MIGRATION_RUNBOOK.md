# Cloudinary Migration — Execution Runbook (server pe chalane ka order)

> **Goal:** Naye Cloudinary account pe file copy → DB cutover → credential switch → verify → decommission, **zero broken images**. Ye doc OPERATIONAL hai (kaunsa command, kab, kya output expect karo, kab ruko). Design decisions ke liye `CLOUDINARY_ACCOUNT_MIGRATION_PLAN.md` dekho.
> **Status:** READY (tooling 6/6 + 50 tests green) · **Date:** 2026-09-08 · Related: `SHARED_HOSTING.md`, `cloudinary-guide.md`, `MEDIA_LIBRARY_NOTES.md`

---

## 0. Ground rules (ek baar padh lo, phir bhoolna mat)

1. **Live commands SIRF production server pe.** `merge --confirm`, `migrate` (live), `cutover --confirm` kabhi local/dev pe mat chalao — ye prod DB + real Cloudinary accounts ko touch karte hain. Dry-runs (`--dry-run`, audit, `verify --dry`...) kahin bhi safe hain.
2. **Har 🛑 backup gate MANDATORY hai.** Backup ke bina aage badhna mana hai.
3. **Koi gate fail ho to RUKO.** Debug karo ya rollback karo (§G) — ummeed pe aage mat badho.
4. **Main `CLOUDINARY_*` vars Phase 3 tak PURANE.** Migration `CLOUDINARY_SOURCE_*` / `CLOUDINARY_DEST_*` se chalti hai.
5. **Site DOWN karne ki zaroorat NAHI** — saare commands site-UP state me chalane ke liye bane hain (chhote batches, idempotent rerun). `down`/`up` sirf deploy ke time (optimize script khud karta hai).

---

## A. Server checklist (pre-flight — sab ✅ tabhi §D shuru)

### A1. Access + PHP binary

- [ ] Production server pe terminal access (SSH ya cPanel Terminal).
- [ ] Har session ki shuruaat me ye alias banao (per `SHARED_HOSTING.md` — system `php` kabhi mat use karo):
  ```bash
  alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
  ```
  Path verify: `ls /opt/cpanel/ea-php*/root/usr/bin/php` (version alag ho sakta hai).
- [ ] `cd ~/demo.vsinfosys.in` (ya actual app path) + `php artisan --version` chalta hai.

### A2. Code deploy (migration tooling server pe honi chahiye)

- [ ] Branch `arena/01a08013-maverick` ka PR `main` me merge karo (GitHub pe), phir server pe normal deploy flow:
  ```bash
  git pull origin main
  composer install --no-dev --optimize-autoloader --no-interaction
  bash scripts/shared-hosting-optimize.sh
  ```
- [ ] Note: tooling me **koi nayi composer dependency nahi** — deploy normal hi hai. Optimize script ka `migrate --force` nayi `media_migration_map` table bana dega. Verify:
  ```bash
  php artisan migrate:status | grep -i migration_map
  php artisan list media
  ```
  Expectation: `media:audit-cloudinary-urls`, `media:merge-duplicates`, `media:migrate-account`, `media:cutover-account`, `media:verify-urls` sab listed.

### A3. `.env` (server ka — pehle snapshot, phir edit)

- [ ] Snapshot lo (har `.env` change se pehle, aadat banao):
  ```bash
  cp .env .env.bak-preflight-$(date +%Y%m%d-%H%M)
  ```
- [ ] Confirm karo (change MAT karo): `APP_ENV=production`, `APP_DEBUG=false`, main `CLOUDINARY_CLOUD_NAME/_API_KEY/_API_SECRET` = **PURANA** account.
- [ ] Ye lines hon (shared mode — R1 guard inse enforce hota hai):
  ```env
  CLOUDINARY_ENV_FOLDER=false
  CLOUDINARY_DISK_ENV=shared
  ```
- [ ] Migration vars add karo:
  ```env
  CLOUDINARY_SOURCE_CLOUD_NAME=<OLD account ka cloud name>
  CLOUDINARY_DEST_CLOUD_NAME=<NEW account ka cloud name>
  CLOUDINARY_DEST_API_KEY=<NEW key>
  CLOUDINARY_DEST_API_SECRET=<NEW secret>
  ```
- [ ] `.env`-only change ka refresh (full optimize script NAHI — usme down/up hai):
  ```bash
  php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
  ```

### A4. Naya Cloudinary account

- [ ] Account bana + cloud name/key/secret noted (↑ A3 me bhare).
- [ ] Plan/storage sufficient hai (audit ke `SUM(size_bytes)` se compare karo — §D1 ke baad confirm).
- [ ] Admin API access OK (verify step me listing use hogi — pehla `--limit=5` batch hi connectivity proof hai).

### A5. Backup readiness

- [ ] Apna DB type note karo: `grep -E '^DB_CONNECTION=' .env` → `mysql` (likely prod) ya `sqlite`.
- [ ] MySQL: `mysqldump` available hai (`mysqldump --version`). SQLite: DB file path noted (`grep -E '^DB_DATABASE=' .env`).
- [ ] Backup folder me jagah hai (DB size × 3, kyunki multiple snapshots lenge): `df -h ~/backups || mkdir -p ~/backups && df -h ~/backups`.
- [ ] (Recommended) Ek test restore staging/copy DB pe karke dekho — backup tabhi backup hai jab restore prove ho.

### A6. Window + comms

- [ ] Cutover low-traffic hours me schedule karo (fast hai, phir bhi).
- [ ] Upload-freeze ki zaroorat NAHI (delta pass cover karta hai) — bas team ko bata do migration chal rahi hai.
- [ ] Rollback owner decide karo: "agar D12/D14 me failures → 30 min debug, phir §G rollback."

---

## B. Backup plan (exact commands)

### B1. KAB backup lena hai (5 mandatory gates)

| # | Kab | Naam pattern |
|---|---|---|
| 1 | D1 se pehle (baseline) | `maverick-baseline-<ts>.sql.gz` |
| 2 | `merge --confirm` se turant pehle | `maverick-premerge-<ts>.sql.gz` |
| 3 | `cutover --confirm` se turant pehle | `maverick-precutover-<ts>.sql.gz` |
| 4 | Phase 3 credential switch se pehle | `maverick-preswitch-<ts>.sql.gz` (+ `.env` snapshot) |
| 5 | Phase 5 decommission se pehle | `maverick-predecom-<ts>.sql.gz` |

### B2. MySQL backup + restore

```bash
# Backup (creds .env se uthao — command me password likhne se bacho, history me rehta hai)
export MYSQL_PWD='<DB_PASSWORD from .env>'
mysqldump -u'<DB_USERNAME>' -h'<DB_HOST>' --single-transaction --quick '<DB_DATABASE>' \
  | gzip > ~/backups/maverick-<phase>-$(date +%Y%m%d-%H%M).sql.gz
unset MYSQL_PWD
ls -lh ~/backups/   # file bani + size sane? (0 bytes = FAIL, dobara karo)

# Restore (rollback ke time)
gunzip -c ~/backups/maverick-<phase>-<ts>.sql.gz | mysql -u'<DB_USERNAME>' -h'<DB_HOST>' '<DB_DATABASE>'
php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### B3. SQLite backup + restore (agar prod sqlite nikla to)

```bash
# Backup (writes rokne ke liye 10 second down — sqlite file-copy safe tabhi hai)
php artisan down --retry=60
cp "<DB_DATABASE path>" ~/backups/maverick-<phase>-$(date +%Y%m%d-%H%M).sqlite
php artisan up
ls -lh ~/backups/

# Restore
php artisan down --retry=60
cp ~/backups/maverick-<phase>-<ts>.sqlite "<DB_DATABASE path>"
php artisan up
```

### B4. `.env` snapshots (DB ke saath-saath)

```bash
cp .env .env.bak-<phase>-$(date +%Y%m%d-%H%M)   # har .env change se pehle
# Rollback: cp .env.bak-<phase>-<ts> .env && php artisan optimize:clear && php artisan config:cache ...
```

---

## C. Dry-runs + execution order (D0–D15)

> Har step: command → expected output → gate. Gate fail = RUKO (§F dekho ya §G rollback).

### D0. (Recommended) Staging rehearsal — agar staging + prod-clone DB hai

- Staging DB = prod ka fresh clone. Staging `.env`: `DEST_*` = ek **TEENWA test Cloudinary account** (free tier — real naye account ko rehearsal se ganda mat karo), `SOURCE_*` = prod old account (fetch read-only hai, safe).
- D1–D12 staging pe end-to-end chalao. Ye rehearsal prod run ka dress rehearsal hai — yahan mile har surprise ko note karke prod run me handle karo.

### D1. Audit (read-only) — `media:audit-cloudinary-urls`

```bash
php artisan media:audit-cloudinary-urls
```
- Expect: `guard.pass = true`; `cloud_names` me sirf OLD cloud (+ jaane-pehchane externals); `duplicate_groups`, legacy `-local` list, bytes note karo.
- 🛑 Gate: guard fail (ENV_FOLDER on) → A3 fix karo, aage MAT badho. Unknown cloud names → plan §2-strays review.

### D2. Merge dry-run — `media:merge-duplicates --dry-run`

```bash
php artisan media:merge-duplicates --dry-run
```
- Expect: `would-merge` groups == D1 ke `duplicate_groups`; ref counts sane lage.
- Gate: groups mismatch → ruko, report bhejo (merge/audit canonical rule same hai — mismatch matlab data surprise).

### D3. 🛑 BACKUP (B1 #2), phir merge confirm

```bash
# backup lo (B2/B3), phir:
php artisan media:merge-duplicates --confirm
php artisan media:merge-duplicates --confirm   # dobara: expect 0 merged (idempotent proof)
```
- Expect: `merged = N`, `errors = []`; second run `merged = 0`.
- 🛑 Gate: errors non-empty → failed dup ki wajah dekho (blank canonical url? table error?) → fix/skip-decision → rerun. Sab green bina D4 mat jao.

### D4. Migrate dry-run — `media:migrate-account --dry-run`

```bash
php artisan media:migrate-account --dry-run
```
- Expect: `migrated` (would-be) ≈ library size; `skipped` me videos/foreign (expected); `dest_checks: skipped-dry-run`. Koi DEST call nahi hoti.
- Gate: R1/source errors → A3/A4 fix. Unexpected `unmapped`-jaisi cheez yahan nahi hoti (dry me mapping likhi hi nahi jaati) — counts D1 se tally karo.

### D5. Pehla live batch — connectivity proof

```bash
php artisan media:migrate-account --limit=5
```
- Expect: `migrated = 5`, `failed = 0`; naye account dashboard me 5 files dikhe (shared folder me, `-local` wali normalized path pe agar thi).
- Gate: koi failure → §F. **Library untouched check:** homepage + 1-2 pages kholo — sab purane URLs se load (Phase 1 site ko touch hi nahi karta).

### D6. Baaki batches — `... --limit=200` repeat till 0 remaining

```bash
php artisan media:migrate-account --limit=200
# ... repeat till processed==0 / remaining==0
```
- Time andaaza: ~250ms/file → 1000 files ≈ 5 min/batch-run + verify. Batches ke beech gap de sakte ho (resumable hai).
- Collisions (`skipped: collision`): naye account dashboard me us pid ki file kholo —
  - Stray/galat file hai → **delete karo**, phir `php artisan media:migrate-account --retry-skipped --limit=200` (wapas upload hoga).
  - Confusion ho → ruko, note karo, §F.
- `new-pid-claimed` (do purani files → ek shared path): content decision chahiye — kaunsi file rakhni hai? Same content ho to jis row ka content duplicate hai use merge/skip karo; alag content ho to ek ko **manual upload** (dashboard se alag naam pe) + **manual mapping row** (§H) banao.
- 🛑 Gate: `remaining = 0` + `failed = 0` + collisions resolved. Ye gate pass kiye bina cutover SOCHO BHI MAT (unmigrated file = cutover skip = residue FAIL).

### D7. Migrate verify — `media:migrate-account --verify`

```bash
php artisan media:migrate-account --verify
```
- Expect: `pass`, missing 0, mismatched 0, listing not capped.
- 🛑 Gate: missing/mismatch → D6 retry loop me wapas (failed rows auto-retry, collisions §D6).

### D8. Delta pass (window uploads) + verify dobara

```bash
php artisan media:migrate-account --limit=200   # sirf missing pids migrate honge
php artisan media:migrate-account --verify      # dobara pass chahiye
```
- Migration window me aaye naye uploads (purane account pe) ab pakde gaye. Cutover se turant-pehle ek aur delta pass chalana hai (D10 ke saath).

### D9. Cutover dry-run — `media:cutover-account --dry-run`

```bash
php artisan media:cutover-account --dry-run
```
- Expect: assets `updated` == mapping migrated+shared count; skips == KNOWN list (videos, foreign, pehle-decided). Har `unmapped` skip ko Naam se samjho — unknown unmapped = D6 me gap hai.
- 🛑 Gate: unknown skips zero karo, phir D10.

### D10. 🛑 BACKUP (B1 #3, turant-pehle) + delta + cutover confirm

```bash
# 1) Backup (B2/B3) — iske bina --confirm MANA hai.
# 2) Aakhri delta (D10 ke 5 min ke andar uploads bhi pakad lo):
php artisan media:migrate-account --limit=200
# 3) Cutover:
php artisan media:cutover-account --confirm
```
- Expect: exit SUCCESS + `image residue 0` (videos alag se gin honge — expected).
- 🛑 Gate: FAILURE exit (residue > 0) → residue samples dekho → wajah (mapping gap? nayi upload?) → D6/D8 loop → cutover rerun (idempotent hai). **SUCCESS bina D11 mat jao.**

### D11. Post-cutover checks (usi session me, 15 min)

```bash
php artisan media:audit-cloudinary-urls     # cloud_names == NEW only (+ jaan-boojh-ke externals), old-cloud refs 0
php artisan media:clean                     # dry-run counts pehle jaisi
```
- Admin Media Library kholo: 5-6 random previews load? Homepage + 2-3 andar ke pages: hero/gallery/logo sab dikhe?
- 🛑 Gate: kuch toota lage → **rollback abhi sasta hai** (§G: pre-cutover backup restore — `.env` abhi purana hai, to sirf DB restore).

### D12. Phase 3 credential switch (`.env` main vars → NEW)

```bash
cp .env .env.bak-preswitch-$(date +%Y%m%d-%H%M)   # snapshot (B1 #4)
# .env edit: CLOUDINARY_CLOUD_NAME/API_KEY/API_SECRET = NEW account. Baaki same.
php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
# Test upload: Media Library me 1 test image → naya cloud URL bana? → test row delete karo.
php artisan media:sync-cloudinary --dry-run      # expect: 0 to-create
```
- 🛑 Gate: test upload fail → creds check; `to-create > 0` → matlab mapping me gap (D6/D10 revisit) — switch ROLLBACK karo (`.env` restore + cache refresh) jab tak samajh na aaye.
- `SOURCE_*/DEST_*` vars **rakho** (rollback/decommission tak).

### D13. `media:verify-urls` + spot-checks + buffer start

```bash
php artisan media:verify-urls
# bot-hostile externals ho to: php artisan media:verify-urls --allow-host=img.youtube.com --allow-host=...
```
- Expect: exit SUCCESS (0 live failures). Har live failure ko kholo — nayi ya purani URL? Nayi fail = D6 gap (rare); purani fail = cutover gap → D10 loop.
- Manual spot-checks (plan §6): homepage, programs list/detail, blogs, news, accreditations, pathways, contact, admin previews.
- 🛑 Gate: sab green → **buffer period start (2–4 hafte, purana account ON, billing on)**. Purane account ka bandwidth monitor karo (~zero ki taraf girna chahiye).

### D14. Buffer end → final verify → Phase 5 decommission

```bash
php artisan media:verify-urls     # dobara green chahiye
```
- Phir: purana account ki IMAGES delete (videos rehne do — scope excluded, account videos ke liye alive rahega), `.env` se `SOURCE_*/DEST_*` hatao + cache refresh, docs update (`cloudinary-guide.md`, `MEDIA_LIBRARY_NOTES.md`, plan → Executed).
- Rollback ab sirf reverse-direction me possible hai (same tooling, SOURCE↔DEST swap karke) — isliye D14 se pehle do baar socho.

---

## D. Cheat sheet — "good" kaisa dikhta hai

| Command | Good output |
|---|---|
| `audit-cloudinary-urls` | guard pass; 1 cloud (+ externals) |
| `merge-duplicates` | errors []; rerun 0 |
| `migrate-account` | failed 0; remaining 0; verify pass |
| `cutover-account` | exit 0; image residue 0 |
| `sync-cloudinary --dry-run` (post-switch) | 0 to-create |
| `verify-urls` | exit 0; 0 live failures |

## E. Troubleshooting (symptom → wajah → action)

| Symptom | Likely wajah | Action |
|---|---|---|
| R1 guard fail kahin bhi | `ENV_FOLDER=true` | A3 fix + cache refresh, phir rerun |
| DEST creds error | galat/adhuri `DEST_*` | A3 verify + refresh; dashboard me key regenerate hui thi? |
| `failed: upload-error` (429/timeout) | rate limit / network blip | Kuch min ruko → rerun (auto-retry) |
| `skipped: collision` repeat | stray abhi bhi DEST pe | Dashboard se delete karo → `--retry-skipped` |
| `skipped: new-pid-claimed` | do files → ek path | §D6 content decision / §H manual mapping |
| cutover FAILURE (residue) | mapping gap / window upload | D6/D8 → cutover rerun |
| post-switch `to-create > 0` | mapping gap | Switch rollback → D6 → D10 → D12 |
| verify live failure (new cloud) | upload corrupt/gap | File dashboard me check → D6 retry → D10 → verify |
| verify live failure (old cloud) | cutover ne chhoda | Residue sample ki table dekho → mapping? → D10 rerun |
| verify live failure (external) | toot gaya / bot-block | Fix karo ya `--allow-host` (soch-samajh ke) |

## F. Rollback runbook (lagbhag har phase me: DB restore ± `.env` revert)

| Phase | Rollback |
|---|---|
| D1–D2, D4, D9 (dry-runs) | Kuch nahi — inhone likha hi nahi |
| D5–D8 (migrate live) | DB untouched hai — rollback = bas RUK jao. (DEST pe aadhi files reh jaayengi — harmless; dobara shuru karne pe mapping wahi se resume. Abort-forever ho to DEST assets dashboard se saaf karo.) |
| D3 (merge confirm) | `maverick-premerge` backup restore (B2/B3) + cache refresh |
| D10–D11 (cutover) | `maverick-precutover` backup restore + cache refresh (`.env` abhi purana → site turant normal) |
| D12 (switch) | `maverick-preswitch` backup restore + `.env.bak-preswitch` wapas + cache refresh |
| D13 buffer | Same as D12 (purana account alive hai) |
| D14 (post-decommission) | ⚠️ Normal rollback KHATAM — sirf reverse migration (tooling wahi, direction ulti) |

Restore ke baad hamesha: `php artisan media:audit-cloudinary-urls` (cloud wapas OLD?) + homepage spot check.

## G. Manual mapping escape hatch (aakhri upaay — jab tooling se na ho)

Jab ek file ko haath se handle karna pade (rename-case, dashboard-upload):

1. Naye account dashboard se file upload karo (exact path note karo, shared folder me).
2. File kholo → actual `secure_url` + bytes note karo (blind-guess kabhi nahi).
3. Mapping row insert karo (server pe `php artisan tinker` ya DB client se):
   ```sql
   INSERT INTO media_migration_map
     (old_public_id, new_public_id, old_url, new_url, bytes, media_asset_id, source, source_ref, status, reason, attempts, created_at, updated_at)
   VALUES
     ('<OLD pid>', '<NEW pid>', '<OLD secure_url>', '<NEW secure_url (dashboard se)>', <bytes>,
      <asset id ya NULL>, 'asset', 'media_assets#<id>', 'migrated', 'manual', 1, NOW(), NOW());
   ```
4. `php artisan media:migrate-account --verify` — nayi row verify honi chahiye, phir normal flow resume (D8→).

---

> Execution ke baad: ye runbook + plan dono me `Executed on <date>` + notes likhna mat bhoolna (kaunse gate pe ruke, kya seekha). Agli migration me kaam aayega.
