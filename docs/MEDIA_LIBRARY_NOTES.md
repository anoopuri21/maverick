# Media Library + Cloudinary — Architecture Notes

> **Purpose:** Is session me hum sirf Cloudinary aur Media Library pe kaam karenge, isliye poora structure layer-by-layer verify karke yahan note kiya gaya hai. Ye file future changes ke liye single reference hai.
> **Date verified:** 2026-09-08 · **Branch:** `arena/01a08013-maverick` · **Koi code change nahi kiya gaya, sirf study + notes.**
> Related official doc: `docs/cloudinary-guide.md` (credentials, account-switch runbook).

---

## 0. TL;DR (30-second overview)

- **Permanent image storage = Cloudinary only.** Server pe images save nahi hoti — sirf Livewire ki temporary upload (`livewire-tmp/`) briefly rehti hai, phir Cloudinary pe upload hokar temp file discard ho jaati hai.
- **Database me 2 cheezein store hoti hain har image ke liye:** (1) `media_assets` table me ek central row (hash, Cloudinary `public_id`, `secure_url`, folder, size…), (2) jahan image use ho rahi hai wahan **do columns**: `xyz_asset_id` (FK → `media_assets.id`) + legacy `xyz` column me **denormalized full Cloudinary URL** (backward compat + fast frontend render).
- **Admin UI (Filament)** me image select/upload ke liye custom component hai: `MediaPicker` → modal me Livewire `MediaLibraryModal` (Browse + Upload tabs).
- **Local vs Production folders:** Pehle non-production env me Cloudinary folder ka naam `maverick-academy-local` (env suffix ke saath) banta tha, production me `maverick-academy` — matlab **same files 2 jagah upload hokar double storage**. Ab default **shared folder** hai (`CLOUDINARY_ENV_FOLDER=false`) — sab env same `maverick-academy/...` folder use karte hain. Purana behaviour opt-in flag se wapas laaya jaa sakta hai.
- **Maintenance engine:** `MediaUsageService` (kaunsi image kahan use ho rahi hai), `MediaCleanService` (unused recycle, dry-run default), `MediaFolderNormalizer` (purane `-local` folder metadata ko shared pe remap), `media:sync-cloudinary` (Cloudinary → DB import).

---

## 1. Layer Map (poora stack ek nazar me)

```
L0  CONFIG ......... .env + config/services.php (cloudinary.*) + config/media.php + config/livewire.php
                     |
L1  CLOUDINARY ..... app/Services/CloudinaryService.php   (API client, folder resolve, upload/delete/list)
                     |
L2  LIBRARY ........ app/Services/MediaLibraryService.php (validation, SHA-256 dedupe, DB row create)
                     |
L3  DATABASE ....... media_assets + *_asset_id FK columns + denormalized *_url + media_recycle_logs
                     |
L4  ADMIN UI ....... MediaAssetResource (CRUD) + MediaPicker (field) + MediaLibraryModal (Livewire modal)
                     |
L5  SAVE/SYNC ...... Eloquent Resources: syncFieldFromAsset() in Create/Edit pages
                     Settings pages: Spatie settings JSON + HydratesRepeaterMediaFields + SeoFormFields
                     Legacy (9 fields): direct FileUpload → cloudinary_upload() (bina library row)
                     |
L6  FRONTEND ....... media_url() / settings_media_url() / HasMediaAssets::getMediaUrl() / mlp_image_url()
                     |
L7  MAINTENANCE .... MediaUsageService / MediaCleanService / MediaFolderNormalizer + artisan commands
                     |
L8  TOOLING ........ media:migrate-fields (AST-based FileUpload → MediaPicker migrator) + tests
```

---

## 2. L0 — Configuration (sabse neeche wali layer)

| File | Kya hai |
|---|---|
| `.env` → `CLOUDINARY_CLOUD_NAME / API_KEY / API_SECRET` | Cloudinary account credentials. **Sirf naye uploads/sync/deletes** isi account pe hote hain; purane DB URLs purane cloud name pe hi point karte rehte hain (absolute URL stored hai). |
| `CLOUDINARY_UPLOAD_FOLDER=maverick-academy` | Base folder. Default `maverick-academy`. |
| `CLOUDINARY_ENV_FOLDER` (default `false`) | `false` = **shared folder** (local+prod same folder). `true` = env suffix wala purana behaviour. |
| `CLOUDINARY_DISK_ENV` (default `shared`) | Shared mode me `media_assets.disk_env` me store hone wali value (`shared`). |
| `CLOUDINARY_ENV_PREFIX` | Env-folder mode me suffix override, else `APP_ENV` (production me suffix nahi lagta). |
| `CLOUDINARY_LEGACY_ENV_SUFFIXES` (default `local,testing,staging,development,dev`) | Clean/sync ke time scan hone wale purane env-prefixes. |
| `config/services.php → cloudinary.*` | Upar wali env values ka map + `secure => true` (https URLs). |
| `config/media.php` | `max_upload_kilobytes` (default 5120 = 5MB, env `MEDIA_MAX_UPLOAD_KB`), `allowed_mime_prefixes = ['image/']` (**sirf images allowed**), `schema_skip_tables` (usage-scan me ignore hone wale system tables). |
| `config/livewire.php → temporary_file_upload` | Temp uploads: `directory = 'livewire-tmp'`, rules `image, max:5120`. **Ye temp hai — permanent storage nahi.** |
| `config/filesystems.php` | `local` disk = `storage/app/private`, `public` disk = `storage/app/public`. Media library inhe permanent storage ki tarah use **nahi** karti (sirf Livewire temp + local `public/assets` fallback images alag cheez hai). |

> Note: `.env.example` me `CLOUDINARY_ENV_FOLDER` line nahi hai — default `false` (shared) `config/services.php` se aata hai.

---

## 3. L1 — CloudinaryService (Cloudinary se baat karne wala eklauta gatekeeper)

**File:** `app/Services/CloudinaryService.php` · Package: `cloudinary/cloudinary_php ^3.1`

### 3.1 Folder resolution (sabse important logic — local/prod folders yahin bante hain)

```
resolveBaseFolder()
  base = CLOUDINARY_UPLOAD_FOLDER (default 'maverick-academy')
  if usesEnvFolder() == false  →  return base                              // SHARED (current default)
  else:
    prefix = CLOUDINARY_ENV_PREFIX ?? (production ? null : APP_ENV)
    return base + '-' + prefix   // e.g. maverick-academy-local            // LEGACY opt-in

resolveUploadFolder($folder)  →  base + '/' + $folder
  e.g. resolveUploadFolder('partner-logos') = 'maverick-academy/partner-logos'
  (agar $folder pehle se full path hai to as-is return)

diskEnv()
  env_folder on  →  APP_ENV (e.g. 'local', 'production')   // per-env library rows
  env_folder off →  CLOUDINARY_DISK_ENV (default 'shared') // sab env same rows dekhte hain
```

### 3.2 Upload / Delete / List methods

| Method | Kaam |
|---|---|
| `uploadImage($path, $folder)` | Sirf `secure_url` return karta hai (legacy direct-upload flow ke liye). |
| `uploadImageDetailed($path, $folder)` | `['url', 'public_id', 'folder']` return. Options: `resource_type=image`, transformation `quality=auto:good, fetch_format=auto` (auto-compress + auto-format), `overwrite=false, unique_filename=true` (kabhi overwrite nahi, hamesha unique naam). |
| `deleteImage($url)` / `deleteByPublicId($id)` | Cloudinary destroy. Delete fail ho to `false` + log (exception nahi). |
| `extractPublicId($url)` | Cloudinary URL se public_id nikaalta hai (regex `/upload/(v123/)?...`). Non-Cloudinary URL → `null`. |
| `folderFromPublicId($id)` | `dirname()` se folder nikaalta hai. |
| `listImagesByPrefix($prefix, $cursor)` | Admin API se 500/page listing (sync/clean ke liye). |
| `listPrefixes()` | Scan list: current base + base + har legacy suffix (`maverick-academy-local` etc.). |
| `normalizeFolderPath($folder)` | `maverick-academy-local/x` → `maverick-academy/x` (shared mode me hi; metadata fix ke liye, Cloudinary pe kuch move nahi hota). |
| `hasCredentials()` / `client()` | Credentials check + lazy `Cloudinary` client. Missing creds → clear error message (`config:clear` hint ke saath). |

---

## 4. L2 — MediaLibraryService (upload pipeline: validate → dedupe → upload → DB row)

**File:** `app/Services/MediaLibraryService.php`

`store($file, $folder='general', $originalName=null)` step-by-step:

1. **Path resolve** — `UploadedFile` / Livewire `TemporaryUploadedFile` / string path sab supported (`getRealPath()`).
2. **Size check** — `config('media.max_upload_kilobytes')` (5MB) se bada → exception.
3. **MIME check** — `image/*` prefix mandatory, warna exception (videos/PDF library me allowed nahi).
4. **SHA-256 hash** — file ka hash nikaalta hai.
5. **Dedupe check** — `media_assets` me same `hash` (+ env mode me same `disk_env`) dhoondhta hai, **soft-deleted rows included**:
   - Mil gaya + trashed → **restore** karke wahi row return (dobara upload nahi!).
   - Mil gaya + active → wahi row return ("Reusing existing asset").
6. **Cloudinary upload** — `uploadImageDetailed($path, $folder)`.
7. **DB row create** — `hash, original_name, mime_type, size_bytes, width/height (getimagesize), cloudinary_public_id, url, folder, disk_env, used=false, is_duplicate=false`.
8. `currentDiskEnv()` / `scopeLibrary($query)` — env-folder mode ON hai to queries `disk_env` se filter hoti hain, warna sab rows visible (shared library).

> Matlab: **same file dobara upload karo to Cloudinary pe dobara upload nahi hota** — purani row reuse/restore hoti hai. Ye storage + cost bachata hai.

---

## 5. L3 — Database schema

### 5.1 `media_assets` (central library table)

Migrations: `2026_07_31_194800_create_media_assets_table` + `..._add_unique_cloudinary_public_id...` + `2026_08_22_180000_add_media_usage_and_recycle_log`

| Column | Matlab |
|---|---|
| `id` | PK, har jagah `*_asset_id` FK isi ko point karta hai |
| `hash` (64) | SHA-256 dedupe key. Unique: `['hash','disk_env']` |
| `original_name`, `mime_type`, `size_bytes`, `width`, `height` | File metadata |
| `cloudinary_public_id` (UNIQUE) | Cloudinary ka asli identifier — delete/sync isi se |
| `url` | Cloudinary `secure_url` (absolute, `res.cloudinary.com/...`) |
| `folder` | Full Cloudinary folder path e.g. `maverick-academy/partner-logos` (indexed) |
| `alt` | Alt text (admin me editable) |
| `disk_env` | `shared` (default) ya env name (env-folder mode me) |
| `used` (bool, indexed) | Kahi reference hai ya nahi — `MediaUsageService::refresh()` se recompute hota hai |
| `is_duplicate` (bool) | Same hash ki 2+ rows me pehli ko chhod baaki `true` |
| `deleted_at` (SoftDeletes) | Recycle = soft-delete (reversible); dedupe me trashed bhi match hota hai |

Model: `app/Models/MediaAsset.php` (fillable + casts + `SoftDeletes`).

### 5.2 Dual-column convention (har jagah same pattern — VERIFIED)

Jahan bhi image use hoti hai (Eloquent models, Spatie settings, repeater JSON):

```
logo_url           →  denormalized FULL Cloudinary URL (legacy column, frontend isi ko padhta hai)
logo_url_asset_id  →  FK → media_assets.id (nullable, nullOnDelete)
```

- Migrations: `2026_08_01_04*_add_media_asset_id_columns.php` (10 files, har model ke liye ek).
- Settings (Spatie) me ye keys JSON me store hoti hain, e.g. `seo.og_image_url` + `seo.og_image_url_asset_id`, repeater rows me `image` + `image_asset_id`.
- **Kyun 2 columns?** URL column = purana code/frontend bina change chalta rahe + bina JOIN ke fast render. `asset_id` = library linkage (dedupe, usage tracking, cleanup).

### 5.3 `media_recycle_logs`

Clean/recycle ka audit trail: `media_asset_id, cloudinary_public_id, url, hash, folder, disk_env, original_name, deleted_from_cloudinary (bool), payload (JSON), timestamps`. Model: `app/Models/MediaRecycleLog.php`.

---

## 6. L4 — Admin UI (Filament + Livewire)

### 6.1 Media Library CRUD — `MediaAssetResource`

- **File:** `app/Filament/Resources/MediaAssetResource.php` · Nav: **Admin → Site Settings → Media Library** (`/admin/media-assets`).
- **Create** (`Pages/CreateMediaAsset.php`): `FileUpload(upload)` + `folder` (default `library`) → `MediaLibraryService::store()` → row create → `original_name/alt` update. Direct `FileUpload` yahan **intentional** hai (library ka ingest point hai, khud picker use nahi kar sakta).
- **Edit** (`Pages/EditMediaAsset.php`): **sirf `original_name` + `alt` editable** — hash/URL/public_id kabhi change nahi hote (`mutateFormDataBeforeSave` me `only([...])`).
- **List** (`Pages/ListMediaAssets.php`): Preview, Name, **Used badge**, Folder, Env, Size + filters (Used/Duplicate/Folder/Env/Trashed). Header actions:
  - **Refresh usage** → `MediaUsageService::refresh()`.
  - **Clean Media Library** → modal me dry-run preview + confirm checkbox + optional Cloudinary purge checkbox → `MediaCleanService::execute(dryRun:false, ...)`.
  - Table query me `SoftDeletingScope` hata rakha hai (trashed bhi dikhte hain + Restore).

### 6.2 MediaPicker — har form ka image field (VERIFIED: 117 usages)

**File:** `app/Filament/Forms/Components/MediaPicker.php` + blade `resources/views/filament/forms/components/media-picker.blade.php`

- Factory: `MediaPicker::forField('logo_url', 'partner-logos')` banata hai:
  - state key: `logo_url_asset_id` (asset id store hota hai),
  - `urlField`: `logo_url` (sibling URL column),
  - `folder`: Cloudinary subfolder.
- Dotted names supported: `forField('seo.og_image_url', 'contact/seo')` me `urlField` = last segment (`og_image_url`).
- **Live URL sync:** koi asset select hote hi (`afterStateUpdated`, `live()`), `MediaAsset::find($state)` karke sibling URL field me `asset->url` set ho jaata hai. **Clear karne pe typed URL wipe nahi hota** (sirf Clear button dono null karta hai).
- **2 modal actions:** `Choose from Library` (initialTab=browse) aur `Upload New` (initialTab=upload) — dono `MediaLibraryModal` Livewire component render karte hain (unique key ke saath).
- **Blade preview:** agar asset linked hai to asset image + folder; warna agar sirf URL hai to "Saved URL (no library asset linked)" fallback; warna "No image selected". `x-on:media-asset-selected.window` event se selection aata hai (statePath match karke `$wire.set`).
- **Save-time sync (static helpers):**
  - `syncFieldFromAsset($data, 'logo_url')` — `logo_url_asset_id` se asset dhoondhkar `logo_url` me URL denormalize karta hai. Asset key absent hai to data untouched. Asset null + URL empty → dono null.
  - `syncUrlFromAsset($data, 'photo')` — purana `media_asset_id` convention wala variant.

### 6.3 MediaLibraryModal — Browse + Upload modal (Livewire)

**Files:** `app/Livewire/MediaLibraryModal.php` + `resources/views/livewire/media-library-modal.blade.php`

- Props: `statePath` (kaunse field me selection jaayegi), `folder` (default subfolder + filter), `initialTab`, `selectedAssetId`.
- **Browse tab:** search (name/public_id), folder dropdown + "Show all folders", 24/page pagination, grid + selected ring + check badge. Query hamesha `MediaLibraryService::scopeLibrary()` se guzarti hai (env-mode me disk_env filter).
- **Upload tab:** drag&drop + file input → `saveUpload()`: validate `image, max:5120` → `MediaLibraryService::store($upload, $folder)` → notification ("Uploaded" ya "already exists — reusing") → `emitSelection($asset->id)`.
- **Selection:** `selectAsset($id)` → scope-checked find → Livewire event `media-asset-selected(assetId, statePath)` dispatch → **sirf topmost modal close** hota hai (JS: last `.fi-modal-close-btn` click — parent Create/Edit modal khula rehta hai).
- Temp file Livewire ke `livewire-tmp/` me aata hai, `store()` uska real path padhkar Cloudinary bhejta hai — **server pe permanent copy kabhi nahi banti.**

---

## 7. L5 — Save/Sync conventions (kaunse flow me data kaise save hota hai)

### 7.1 Eloquent Resources (migrated — VERIFIED)

- Models with `HasMediaAssets` trait (16): `BlogPost, FacultyInsight, GupPartnerUniversity, Insight, MediaGalleryPhoto, MediaGalleryVideo, OurStoryAward, OurStoryGalleryImage, OurStoryTimeline, PartnerLogo, PartnershipGalleryItem, Program, StudentSuccessStory, StudentSuccessVideo, Testimonial, UniversityPartner`.
- Har Create/Edit page me: `mutateFormDataBeforeCreate/Save` → `MediaPicker::syncFieldFromAsset($data, '<field>')` → (kuch pages me) empty ho to existing record value preserve.
- VERIFIED: 36 resource pages me sync hook hai. Bina hook wali pages (`Event`, `GlobalAccessPointCountry`, `ProgramCategory`, `ZapierWebhook`, `MediaAssetResource`) me **koi media field hi nahi** — gap nahi hai.
- `app/Concerns/HasMediaAssets.php`: `getMediaUrl('logo_url')` = asset URL preferred, fallback legacy column. Zero per-model config.

### 7.2 Spatie Settings pages (JSON-based, mixed state)

- Settings pages (`app/Filament/Pages/Manage*.php`, Spatie `spatie/laravel-settings`) me MediaPicker dotted/repeater paths me bhi use hota hai, e.g. `forField('seo.og_image_url', ...)`, repeater me `forField('image', ...)`.
- Repeater pattern (e.g. `ManageGlobalOpportunities`): `mutateFormDataBeforeFill` me `hydrateRepeaterMediaFields()` (URL backfill), `mutateFormDataBeforeSave` me `syncImageIfSelected()` + `preserveRepeaterImageFields()` + `RepeaterNormalizer::stripEmptyRows()` — traits: `HydratesRepeaterMediaFields`, `HandlesCloudinaryImageFields`.
- `HandlesCloudinaryImageFields::preserveExistingImageFields()` — MediaPicker-managed keys (`*_asset_id` + unka URL counterpart) ko **skip** karta hai taaki intentional clear wipe na ho jaaye; baaki image-ish keys (image/logo/favicon/thumbnail/banner/photo/avatar/icon) empty ho to existing settings value preserve.
- SEO: `SeoFormFields::forSettingsPage()` (og/twitter pickers + manual URL inputs) — dotted relationship variant bhi hai.
- `SavesSettingsGroups` trait: kuch pages settings-group save isi se karti hain (preserve + rows-exist + fill/save + error notification).

### 7.3 Legacy direct-upload (abhi bhi 9 fields — Media Library bypass)

**Files:** `ManageSiteSettings` (2: logo, logo_white), `ManageCeoMessage`, `ManageWhoWeAre`, `ManageOurStoryHero/Beginning/CeoQuote/Today/Vision` (7) — total **9 `FileUpload` fields**.

- Pattern: `FileUpload::make('x')->image()->saveUploadedFileUsing(fn($file) => cloudinary_upload($file->getRealPath(), '<folder>'))` + `getUploadedFileUsing(existingCloudinaryImage)` (preview ke liye stored URL ko fake file-array banata hai).
- Helper `cloudinary_upload()` (`app/helpers.php`): `CloudinaryService::uploadImage()` call, fail ho to report + Filament danger notification + `null` (existing image preserved).
- **Farq:** ye flow `media_assets` row **nahi** banata — Cloudinary pe file jaati hai, URL settings me save hota hai, lekin dedupe/usage-tracking/cleanup me ye asset "unknown" hai (cleanup me orphan ban sakta hai). Future migration candidate.
- `MediaAssetResource` ka `FileUpload('upload')` is count me nahi — wo library ka ingest hai, legacy nahi.

---

## 8. L6 — Frontend render (read path — VERIFIED: 118 usages)

Helpers (`app/helpers.php`):

| Helper | Kaam |
|---|---|
| `media_url($path, $fallback)` | Absolute URL (`http/https///data:/blob:`) → as-is. Relative path → `cached_asset()` (mtime `?v=` + `asset()`, taaki `/programs/x` jaise sub-routes pe link na toote). |
| `settings_media_url($item, $field)` | Settings/array/object se pehle `$field` URL, warna `{$field}_asset_id` se `MediaAsset` lookup → URL. |
| `HasMediaAssets::getMediaUrl($field)` | Eloquent models: asset URL preferred → legacy column fallback. |
| `mlp_image_url($path, ['w'=>1600])` | Cloudinary URL me `f_auto,q_auto,w_N` transform inject karta hai (MBA landing). Pehle se transform hai to untouched. |
| `cached_asset($path)` | `public/` file ko mtime version ke saath serve karta hai (immutable cache-friendly). |

- Blade examples: `media_url($post->author_avatar_url)`, `settings_media_url($cinematicSettings, 'image_url')`.
- **Key point:** frontend stored absolute Cloudinary URLs ko as-is serve karta hai — credentials change se purane URLs nahi badalte (dekho `docs/cloudinary-guide.md` §4 account-switch runbook).

---

## 9. L7 — Maintenance engine (usage / clean / normalize / sync)

### 9.1 MediaUsageService — "kaunsi image kahan use ho rahi hai"

**File:** `app/Services/MediaUsageService.php` · `refresh()` → `{used, unused, duplicates, referenced_ids}`

- Poora DB scan: har table (skip: `config('media.schema_skip_tables')` — migrations/cache/jobs/sessions + `media_assets` + `media_recycle_logs` khud) ke har chunk me:
  - `*_asset_id` / `media_asset_id` columns → direct id match,
  - image-ish/`*_url` columns → normalized URL match (scheme+host+path) **aur** `extractPublicId` match,
  - JSON/text columns (`payload`, `*json*`, json types) → recursive walk (depth 20), andar `*_asset_id` keys + URL strings dono match (Spatie settings JSON isi se cover hota hai).
- `is_duplicate`: same non-empty `hash` group me pehli row ko chhod baaki sab `true`.
- Flags `saveQuietly()` se persist (events nahi fire hote).

### 9.2 MediaCleanService — recycle unused (safe by default)

**File:** `app/Services/MediaCleanService.php`

- `preview()`: `usage->refresh()` + unused rows list + Cloudinary orphans (Admin API se `listPrefixes()` scan karke jo public_id DB me nahi ya unreferenced hai) + error string (creds missing ho to remote compare skip).
- `execute($dryRun=true, $deleteFromCloudinary=false, $includeOrphans=false)`:
  - **Dry-run default** — bina `--confirm` kuch delete nahi hota.
  - Har unused row: dobara reference check (`stillUsed` ya `used=true` ho to skip + flag fix) → optional Cloudinary destroy → `media_recycle_logs` entry → **soft-delete** (reversible).
  - Orphans (`includeOrphans` + purge dono ON): sirf Cloudinary destroy + recycle log (DB row thi hi nahi).
  - **Used files kabhi delete nahi hote.** Idempotent (dobara run = kuch nahi milega).
- Entry points: CLI `media:clean [--confirm] [--purge-cloudinary] [--include-orphans]` + Admin modal (Clean Media Library).

### 9.3 MediaFolderNormalizer — purane `-local` metadata ko shared pe lao

**File:** `app/Services/MediaFolderNormalizer.php` · CLI: `media:normalize-folders [--dry-run]`

- Har `media_assets` row (with trashed): `folder` = `normalizeFolderPath()` (+ `public_id` se derived folder preferred), `disk_env` = shared mode me `shared`.
- Collision: same `hash` + target `disk_env` pe doosri row already hai → `repointReferences()` (saare tables ke `*_asset_id` FK naye id pe) + purani row soft-delete (merge count).
- **Sirf DB metadata badalti hai** — `url`/`public_id` untouched (Cloudinary files move/re-upload nahi hote). Idempotent + dry-run supported.

### 9.4 SyncCloudinaryMediaCommand — Cloudinary → DB import

**File:** `app/Console/Commands/SyncCloudinaryMediaCommand.php` · CLI: `media:sync-cloudinary [--folder=] [--dry-run]`

- Default prefix = `resolveBaseFolder()`. Paginated Admin API listing.
- Skip agar `public_id` already DB me hai. Naya mile to row create: synthetic hash `imported:<sha256(public_id)[0:55]>` (64-char limit fit), `original_name=basename(public_id)`, mime format se, bytes/width/height API se, folder normalized, `disk_env` current, `used=false`.
- Use case: naya Cloudinary account / console se seedhe upload ki hui files ko library me laana.

---

## 10. L8 — Migrator + Tests

- `media:migrate-fields [--dry-run] [--force] [--resource=]` (`app/Console/Commands/MigrateMediaFieldsCommand.php`, `nikic/php-parser` AST-based):
  - Filament **Resources** me `FileUpload::make('x')->image()` chains → `MediaPicker::forField('x','<folder>')` (folder `saveUploadedFileUsing→uploadImage` arg se detect), model me `HasMediaAssets` + fillable, Create/Edit pages me sync hooks, consolidated `*_asset_id` migration generate.
  - Safety: dry-run default, idempotent, parse-validate-before-write, unknown chain methods → NEEDS REVIEW (skip), **Settings pages + Blade + MediaAssetResource kabhi touch nahi** (manual migration).
- Tests (VERIFIED, padhe gaye):
  - `tests/Unit/CloudinaryFolderTest.php`: shared default, env-flag legacy suffix, `normalizeFolderPath` strip.
  - `tests/Feature/MediaLibraryMaintenanceTest.php`: usage flagging, normalize repoint (URL unchanged assert), clean dry-run no-delete.

---

## 11. End-to-end upload flows (3 flows)

### Flow A — Library modal se Upload (sabse common, 117 pickers)

```
Admin form → MediaPicker [Upload New] → MediaLibraryModal(upload tab)
  → Livewire temp upload (livewire-tmp/, validate image+5MB)
  → saveUpload() → MediaLibraryService::store(temp, folder)
      → size/MIME/hash → dedupe? reuse : Cloudinary uploadImageDetailed
          → folder 'maverick-academy/<sub>' + auto quality/format, unique name
      → media_assets row create
  → event 'media-asset-selected' → picker state (asset_id) + sibling URL auto-fill
  → form Save → syncFieldFromAsset() → '<field>' URL + '<field>_asset_id' persist
```

### Flow B — Media Library CRUD se seedha upload

```
Admin → Media Library → Create → FileUpload + folder → CreateMediaAsset::handleRecordCreation
  → MediaLibraryService::store() (same pipeline) → original_name/alt update → row list me dikhta hai
```

### Flow C — Legacy direct upload (9 fields, library bypass)

```
Settings page FileUpload → saveUploadedFileUsing → cloudinary_upload(realPath, folder)
  → CloudinaryService::uploadImage → secure_url → settings column me save
  (koi media_assets row nahi; dedupe/usage/clean me invisible)
```

---

## 12. Local vs Production folders — KYU 2 folders, KAISE bante the, AB kya hai

### 12.1 Kaise bante the (mechanism — purana behaviour)

- `CloudinaryService::resolveBaseFolder()` production ke alawa har env me base ke aage `-APP_ENV` lagata tha:
  - local: `maverick-academy-local/...` · production: `maverick-academy/...`
- Saath me `disk_env` bhi per-env (`local` / `production`) store hota tha, aur library queries `disk_env` se filter hoti thin — matlab **local admin ko prod images nahi dikhte the aur vice versa**.

### 12.2 Kyu banaye gaye the (probable reason)

- **Environment isolation:** dev/test uploads production assets se alag rahen, local experiments prod library/folder ko pollute na karein, aur per-env cleanup/sync aazadi se ho sake. Ye common pattern hai.
- **Side effect (problem):** same image local + prod dono jagah upload hui → Cloudinary pe **double storage + double cost**, aur envs ke beech library share nahi hoti thi.

### 12.3 Ab kya hai (current default — VERIFIED code + tests + docs)

- `CLOUDINARY_ENV_FOLDER=false` (default) → **ONE shared folder** `maverick-academy/...` sab env ke liye + `disk_env='shared'` → sab env same library rows dekhte hain, dedupe bhi global.
- Purane `-local`/`-testing`/… folders Cloudinary pe ab bhi ho sakte hain (files auto-move nahi hoti) — `listPrefixes()` unhe scan karta hai, `media:normalize-folders` DB metadata remap karta hai, `media:clean --include-orphans` se unused leftovers hataaye jaa sakte hain.

### 12.4 Wapas env-folders ON karna ho to (opt-in, NOT recommended)

```env
CLOUDINARY_ENV_FOLDER=true
CLOUDINARY_ENV_PREFIX=local     # optional; default APP_ENV (production me suffix nahi lagta)
```
Phir `php artisan config:clear`. Isse `resolveBaseFolder()` → `maverick-academy-local`, `diskEnv()` → `local`, library per-env filter hone lagegi. **Naye uploads alag folder me jaayenge; purane shared rows/URLs untouched rahenge** — soch-samajh ke karna.

---

## 13. Verified facts (counts — `grep` se confirm)

| Cheez | Count | Detail |
|---|---|---|
| `MediaPicker::forField` usages | 117 | Resources + Settings pages + SEO |
| `HasMediaAssets` models | 16 | list §7.1 me |
| Legacy direct-upload `FileUpload` fields | 9 | SiteSettings(2) + CeoMessage + WhoWeAre + OurStory×5 |
| Resource pages me sync hooks | 36 files | bina hook wali 10 pages me media field hi nahi (gap nahi) |
| Frontend helper usages | 118 | `media_url/getMediaUrl/settings_media_url/mlp_image_url` |
| `*_asset_id` migration files | 10 + OurStoryTestimonials wali | `2026_08_01_*` + `2026_07_31_194801` |
| Upload limit | 5MB | `MEDIA_MAX_UPLOAD_KB` + Livewire rules + modal validation — teeno aligned |
| Allowed types | images only | `config/media.php` + modal `image` rule + FileUpload `->image()` |

---

## 14. Future changes ke liye gotchas (dhyaan rakhne wali baatein)

1. **URL rewrite mat karna:** DB me absolute Cloudinary URLs hain — credentials/account change pe purane URLs purane cloud pe hi rahenge. Naya account = sirf naye uploads naye account pe. (Runbook: `docs/cloudinary-guide.md` §4.)
2. **Naya image field add karna ho** → pattern: `MediaPicker::forField('<field>', '<subfolder>')` + `HasMediaAssets` (Eloquent) + `<field>_asset_id` migration (FK, nullable, nullOnDelete) + Create/Edit pages me `syncFieldFromAsset` + frontend me `getMediaUrl`/`settings_media_url`. Resources ke liye `media:migrate-fields --dry-run` se start kar sakte ho; Settings pages hamesha manual.
3. **Legacy 9 fields** abhi library bypass karte hain — cleanup me orphan dikhenge; migrate karne pe dedupe/usage me aa jaayenge.
4. **`syncFieldFromAsset` vs live sync:** live `afterStateUpdated` sirf UI preview/URL fill karta hai; **persist hamesha save-time sync se hota hai** — Create/Edit page me hook bhool gaye to URL save nahi hogi. (Settings repeater me `syncImageIfSelected` + preserve combo.)
5. **`preserveExistingImageFields` MediaPicker keys skip karta hai** — intentional clear ko preserve logic wipe nahi karta. Naya save-flow likhte time ye trait zaroor use karna (`.cursorrules` §1 bhi yahi kehta hai).
6. **`used` flag realtime nahi hai** — `Refresh usage` / `media:clean` chalane pe recompute hota hai. Delete se pehle hamesha refresh + dry-run dekho.
7. **Clean me Cloudinary purge alag checkbox/flag hai** — default soft-delete only (reversible via Trashed → Restore). Purge ke baad bhi `media_recycle_logs` me public_id+URL rehta hai.
8. **Dedupe + restore:** same file re-upload → trashed row **restore** ho jaati hai (nayi row nahi). Iska matlab "delete karke wapas upload" purani row wapas laata hai — id ke saath.
9. **Env-folder flag flip karna** upload destination + library visibility dono badal deta hai; flip ke baad `media:normalize-folders --dry-run` zaroor chalana.
10. **Videos:** library images-only hai. Hero video jaise cases me seedha Cloudinary video URL text field me store hota hai (e.g. `ManageHero.video_url`) — uska alag flow hai, library se unrelated.
11. **MIME spoofing note:** `store()` me `$mime === ''` ko pass diya gaya hai (mime detect na ho to allow) — future hardening point.
12. **Filament + Livewire temp:** `livewire-tmp/` aur Filament temp uploads server pe jama na hon, iska cleanup Livewire/cron pe depend hai — permanent media ka source of truth Cloudinary + DB hai.

---

## 15. Command cheat sheet

```bash
php artisan media:normalize-folders --dry-run   # shared-folder remap preview
php artisan media:normalize-folders             # folder + disk_env fix (merge collisions)
php artisan media:sync-cloudinary --dry-run     # Cloudinary → DB import preview
php artisan media:sync-cloudinary               # import missing assets
php artisan media:clean                         # dry-run: usage + unused + orphans report
php artisan media:clean --confirm               # soft-delete unused rows (+ recycle log)
php artisan media:clean --confirm --purge-cloudinary                      # + Cloudinary destroy
php artisan media:clean --confirm --purge-cloudinary --include-orphans   # + DB ke bahar ki files bhi
php artisan media:migrate-fields --dry-run      # FileUpload → MediaPicker report (Resources only)
```

---

## 16. File index (jaldi jump karne ke liye)

```
config/services.php                  cloudinary.* env map
config/media.php                     limits, MIME, skip-tables
config/livewire.php                  temp upload rules (livewire-tmp)
app/Services/CloudinaryService.php   L1 — API + folders
app/Services/MediaLibraryService.php L2 — store() pipeline
app/Services/MediaUsageService.php   usage scan
app/Services/MediaCleanService.php   preview + recycle
app/Services/MediaFolderNormalizer.php folder/disk_env remap
app/Models/MediaAsset.php            central row
app/Models/MediaRecycleLog.php       audit log
app/Concerns/HasMediaAssets.php      getMediaUrl()/mediaAsset()
app/helpers.php                      cloudinary_upload(), media_url(), settings_media_url(), mlp_image_url()
app/Filament/Forms/Components/MediaPicker.php          field component
app/Filament/Forms/Components/SeoFormFields.php        SEO pickers + sync
app/Filament/Concerns/HandlesCloudinaryImageFields.php existingCloudinaryImage() + preserve*()
app/Filament/Concerns/HydratesRepeaterMediaFields.php  repeater sync/hydrate/preserve
app/Filament/Concerns/SavesSettingsGroups.php          settings-group save
app/Filament/Resources/MediaAssetResource.php + Pages/  library CRUD + usage/clean actions
app/Livewire/MediaLibraryModal.php                     browse/upload modal logic
resources/views/livewire/media-library-modal.blade.php  modal UI
resources/views/filament/forms/components/media-picker.blade.php  picker UI
app/Console/Commands/SyncCloudinaryMediaCommand.php
app/Console/Commands/NormalizeMediaFoldersCommand.php
app/Console/Commands/CleanMediaCommand.php
app/Console/Commands/MigrateMediaFieldsCommand.php
database/migrations/2026_07_31_194800_*  media_assets create
database/migrations/2026_07_31_201400_*  public_id unique
database/migrations/2026_08_22_180000_*  used/is_duplicate + recycle log
database/migrations/2026_08_01_*         *_asset_id FK columns
tests/Unit/CloudinaryFolderTest.php
tests/Feature/MediaLibraryMaintenanceTest.php
docs/cloudinary-guide.md             credentials + account-switch runbook
```
