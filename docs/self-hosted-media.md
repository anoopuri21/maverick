# Self-hosted media and Cloudinary recovery

The site now stores all **new** image uploads on its own server. This recovery is intentionally
for images only; the homepage video continues to use the self-hosted URL already configured in
**Admin → Homepage → Hero Section** and is not copied by this command.

```text
storage/app/public/media/...
          ↓ php artisan storage:link
public/storage → storage/app/public
          ↓
https://your-domain.tld/storage/media/...
```

The URL saved in `media_assets` and the CMS is a `/storage/...` URL, so the browser requests
media from the Maverick site rather than Cloudinary. Existing Cloudinary images are moved with
the one-time recovery command below.

> **Important:** moving files off Cloudinary removes the Cloudinary account dependency; it does
> not make a single server indestructible. A server disk failure, hosting-account suspension, or
> accidental deletion can still remove files. Back up **both the database and
> `storage/app/public/media`** off-site. Treat that pair as one backup set.

## What changed

- Media Library uploads and every direct image upload in `/admin` use the Laravel `public` disk.
- A media-library item stores `storage_disk` and `storage_path`; the old Cloudinary URL is kept
  in `source_url` as recovery/audit metadata.
- A force-delete in Media Library also deletes its self-hosted file. Regular delete is still a
  soft delete, so it remains restorable.
- The two old CSS-only Cloudinary backgrounds are already pointed at deterministic local paths.
  The migration command downloads those files too.
- Cloudinary code remains only for **one-time migration/inventory** and old-account cleanup. New
  uploads do not require Cloudinary credentials.

## One-time production migration

Do this while the legacy Cloudinary account is still enabled and its images are reachable.
Run it on the production server, against the production database and production `.env`.

### 1. Take a rollback backup

Before deploying the change, make a database backup and a copy of the current persistent
storage directory. Confirm the hosting plan has enough free disk for a second copy of every
image plus normal application operations.

At minimum, configure a scheduled off-site cPanel/S3/Google Drive/remote backup for:

```text
DATABASE (all content/settings URL rows)
storage/app/public/media
```

Do not rely on the Git repository, `public/storage` symlink, or a browser cache as a media
backup. They do not contain the actual source files.

### 2. Deploy this release and prepare local storage

```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
```

`storage/app/public` must be writable by the PHP/cPanel user. The existing deployment script
already runs `storage:link`; check that `public/storage` points to `storage/app/public` after
deploying.

### 3. Credentials are optional for images used by the site

The normal recovery command downloads each public `res.cloudinary.com/...` URL directly. It
therefore does **not** need the old Cloudinary account credentials to recover images referenced
by the database or the configured CSS/default-image sources.

Do not assume the `CLOUDINARY_*` values currently in `.env` belong to the legacy account. Those
values can belong to a newer account and must not be used to inventory an unrelated account.
Normal admin uploads do not use Cloudinary credentials at all.

If you also need an archive of images that are no longer referenced by any website page, first
temporarily configure the credentials for the **exact old account**:

```env
CLOUDINARY_CLOUD_NAME=old_cloud_name
CLOUDINARY_API_KEY=old_account_key
CLOUDINARY_API_SECRET=old_account_secret
```

Only then use the optional `--include-remote` command described below.

### 4. Review the site-reference inventory first

```bash
php artisan media:migrate-cloudinary --dry-run
```

The dry-run writes nothing. It reports Cloudinary image URL occurrences found in every
string/text/JSON field in the database and the configured CSS/default-image sources. It does not
call the configured Cloudinary account unless `--include-remote` is explicitly supplied.

### 5. Run the copy and URL rewrite

```bash
php artisan media:migrate-cloudinary --confirm
```

The command is safe to re-run:

- every recovered source URL maps to a deterministic local filename;
- already-downloaded files are reused;
- URLs that were successfully changed no longer match Cloudinary;
- **no Cloudinary file is deleted**.

If you have configured credentials for the exact old account and also want to archive its
unreferenced images, run this separately:

```bash
php artisan media:migrate-cloudinary --confirm --include-remote
```

For an unusually large account, that optional inventory can take time. Do not close the SSH
session while it runs. `--skip-remote` remains as a compatibility option, but remote inventory
is skipped unless `--include-remote` is supplied.

If a source cannot be downloaded, the command exits non-zero, lists it, and leaves that
particular database URL unchanged. Fix the URL/account/network access and run the command again.
A `404` means Cloudinary no longer has a public file at that address; recover that exact rendition
from a backup or the old account before disabling it.

### 6. Clear caches and verify before disabling anything

```bash
php artisan optimize:clear
php artisan storage:link
```

Then verify from a private/incognito browser window:

1. Homepage, program listing/detail, blog/news, global partners and all CMS-heavy pages.
2. Admin → Media Library previews and a **new** test image upload.
3. Existing image edits retain their preview and save correctly.
4. `https://your-domain.tld/storage/media/...` returns images directly.
5. The command output has zero errors and the Cloudinary account's usage is no longer needed by
   page requests.

Keep the old Cloudinary account intact for at least one backup cycle after verification. Only
then remove its credentials from production `.env` and disable/cancel the account if desired.
Never run a Cloudinary purge as part of this migration.

## Routine operation

New uploads appear under:

```text
storage/app/public/media/{admin-folder}/{year}/{month}/{uuid}.{extension}
```

Use `/admin` normally; no Cloudinary configuration is needed for that workflow. Standard media
checks remain available:

```bash
php artisan media:clean
php artisan media:clean --confirm
```

The clean command soft-deletes unused database rows. It does not remove a self-hosted image
until an administrator explicitly chooses **Force Delete** for that item.

## Backups and deploys

1. Include `storage/app/public/media` in daily off-site backups and retain multiple restore
   points.
2. Back up the database in the same job; its rows map pages to media URLs and asset IDs.
3. Test a restore into a staging directory at least quarterly.
4. Preserve `storage/` between releases. A `git pull` is safe; do not run a deployment command
   that deletes `storage/app/public`.
5. Monitor disk usage. The configured admin image limit is `MEDIA_MAX_UPLOAD_KB` (default 5 MB),
   but migrated legacy files may be larger.

## Environment settings

```env
MEDIA_STORAGE_DISK=public
MEDIA_STORAGE_PREFIX=media
MEDIA_MAX_UPLOAD_KB=5120
MEDIA_REMOTE_DOWNLOAD_TIMEOUT=90
```

`MEDIA_STORAGE_DISK` should remain `public` for this shared-hosting setup. If it is changed to a
non-public disk, the application would need a signed/controller download route instead of the
`/storage` symlink described here.
