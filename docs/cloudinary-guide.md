# Cloudinary legacy guide

Cloudinary is no longer the permanent media provider for this application. New image uploads
from the Media Library and all direct `/admin` image controls are stored on the application's
own `public` disk and served from `/storage/media/...`.

This document remains only for the one-time recovery of a legacy Cloudinary account. The full
runbook is [`self-hosted-media.md`](self-hosted-media.md).

## Recovery command

While the old account is still active and its public delivery URLs work, run:

```bash
php artisan media:migrate-cloudinary --dry-run
php artisan media:migrate-cloudinary --confirm
php artisan optimize:clear
php artisan storage:link
```

The normal confirmed run downloads public Cloudinary delivery URLs directly; it does **not** need
Cloudinary credentials to recover images currently referenced by the production database.

The confirmed run:

- downloads every Cloudinary **image** URL stored anywhere in the production database;
- downloads the two former CSS-only backgrounds;
- rewrites database URLs to the local `/storage/media/...` copies;
- retains the original address in `media_assets.source_url` for audit/retry; and
- never deletes a remote Cloudinary asset.

To archive unreferenced images as well, first set `CLOUDINARY_*` to the **same old account** and
run the explicit optional inventory:

```bash
php artisan media:migrate-cloudinary --confirm --include-remote
```

Do not use credentials from a different/newer account for this option: that would inventory its
files, not the legacy account's files. The regular migration also allow-lists the old cloud name
through `MEDIA_LEGACY_CLOUDINARY_CLOUD_NAMES` (default: `i08gwudw`), so newer-account URLs stay
unchanged unless explicitly approved later.

Do not remove old credentials or disable the account until the command exits without errors,
the site is tested in an uncached browser, and a backup of both the database and
`storage/app/public/media` has completed.

## Legacy clean options

`media:clean --purge-cloudinary` and the equivalent old admin checkbox are retained only to
clean an intentional legacy remote copy. They are not required for normal media operation and
must **not** be used during recovery. Self-hosted files remain available after a soft delete and
are removed only when a Media Library record is force-deleted.
