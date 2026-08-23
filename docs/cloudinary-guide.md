# Cloudinary — shared folder, cleanup, account switch

This site stores **absolute `secure_url` values** in the database (`media_assets.url` and denormalized `*_url` / settings fields). The front end (`media_url()`, `getMediaUrl()`) serves those stored URLs as-is. Changing Cloudinary API credentials does **not** rewrite old URLs.

Credentials live only in environment variables — never in code.

## 1. Where to configure

`.env` (then `php artisan config:clear`):

```env
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=

# One folder for local + production (default). Saves Cloudinary storage.
CLOUDINARY_UPLOAD_FOLDER=maverick-academy
CLOUDINARY_ENV_FOLDER=false
CLOUDINARY_DISK_ENV=shared

# Opt-in only: old behaviour (maverick-academy-local vs maverick-academy)
# CLOUDINARY_ENV_FOLDER=true
# CLOUDINARY_ENV_PREFIX=local
```

Config map: `config/services.php` → `cloudinary.*`

| Env | Effect |
|---|---|
| `CLOUDINARY_CLOUD_NAME` / `API_KEY` / `API_SECRET` | Account used for **new** uploads, sync, and deletes |
| `CLOUDINARY_UPLOAD_FOLDER` | Base folder (`maverick-academy`) |
| `CLOUDINARY_ENV_FOLDER` | `false` (default) = shared folder. `true` = append env suffix |
| `CLOUDINARY_DISK_ENV` | Value stored on `media_assets.disk_env` in shared mode (`shared`) |
| `CLOUDINARY_LEGACY_ENV_SUFFIXES` | Extra prefixes scanned during clean/sync (`local,testing,...`) |

Admin UI: **Admin → Site Settings → Media Library** (`/admin/media-assets`).

## 2. Shared-folder fix (stop double storage)

Previously `resolveBaseFolder()` appended `APP_ENV` when not production (`maverick-academy-local`). Local and production uploaded the same files twice.

Now uploads go to `maverick-academy/{subfolder}` in every environment unless `CLOUDINARY_ENV_FOLDER=true`.

After deploy, remapping existing rows is **database metadata only**. Old Cloudinary files keep their public IDs and URLs; nothing is re-uploaded.

```bash
# Safe to rerun. See what would change:
php artisan media:normalize-folders --dry-run

# Write folder + disk_env. Merges hash collisions onto one row and re-points *_asset_id FKs.
php artisan media:normalize-folders
```

What it does:

- Strips `-{local|testing|staging|development|dev}` from the `folder` column
- Sets `disk_env` to `shared` when env folders are off
- Does **not** change `url` or `cloudinary_public_id` (those still point at the real Cloudinary object)

Leftover files sitting only in `maverick-academy-local/` can be removed later with Clean (unused only).

## 3. Used column + Clean Media Library

**Admin → Site Settings → Media Library**

- **Used** column + filter: referenced by any `*_asset_id` / `media_asset_id`, Cloudinary URL, or settings JSON image field
- **Refresh usage** — recomputes flags (`MediaUsageService`)
- **Clean Media Library** — dry-run list in the modal, then confirm
  - Soft-deletes unused `media_assets` rows
  - Writes `media_recycle_logs` (public_id, url, hash) so a mistake can be traced
  - Cloudinary destroy is a **separate checkbox** (off unless you tick it)

CLI (same engine):

```bash
php artisan media:clean
php artisan media:clean --confirm
php artisan media:clean --confirm --purge-cloudinary
php artisan media:clean --confirm --purge-cloudinary --include-orphans
```

`--dry-run` is implied unless `--confirm` is passed. Used files are never deleted. Idempotent: a second run finds nothing left to recycle.

Restore a soft-deleted row from **Media Library → Trashed filter → Restore**. If Cloudinary was purged, the recycle log still has `cloudinary_public_id` + `url` for support tickets / re-upload.

## 4. Account-switch runbook

Old files keep working because the DB stores full `https://res.cloudinary.com/{old-cloud}/...` URLs. Browsers hit the **old** cloud name, not the new credentials.

1. Create the new Cloudinary account. Do not delete the old one until unused-old-account cleanup is done.
2. Leave the site running. Existing pages already use stored absolute URLs.
3. Update `.env` on the target environment:

   ```env
   CLOUDINARY_CLOUD_NAME=new_cloud
   CLOUDINARY_API_KEY=...
   CLOUDINARY_API_SECRET=...
   CLOUDINARY_UPLOAD_FOLDER=maverick-academy
   CLOUDINARY_ENV_FOLDER=false
   ```

4. `php artisan config:clear`
5. New Filament / Media Library uploads now go to the **new** account (new `secure_url` + public_id).
6. Optional: `php artisan media:sync-cloudinary` to import files already sitting in the new account folder.
7. `php artisan media:clean` then review unused rows (often leftover env-folder copies on the **current** account).
8. Keep the old account billed/alive until every stored URL that still points at it is unused or replaced. Then run clean against the old account **before** rotating credentials away, or delete unused assets in the Cloudinary console.

Do **not** rewrite historical URLs to the new cloud name unless you have actually migrated those files.

## 5. Command cheat sheet

| Command | Purpose |
|---|---|
| `media:normalize-folders` | Shared-folder DB remapping (idempotent) |
| `media:sync-cloudinary` | Import Cloudinary folder → `media_assets` |
| `media:clean` | Usage refresh + unused recycle (dry-run default) |

## 6. What is stored vs regenerated

| Field | Stored? | Regenerated from current credentials? |
|---|---|---|
| `media_assets.url` | Yes, Cloudinary `secure_url` | No |
| Settings / model `*_url` | Yes, copied from the asset URL | No |
| `cloudinary_public_id` | Yes | Used only for delete/sync on the **current** account |
| Front-end `<img src>` | Stored URL via `media_url()` | Absolute URLs pass through unchanged |
