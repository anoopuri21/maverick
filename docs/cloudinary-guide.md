# Cloudinary legacy guide

Cloudinary is no longer the permanent media provider for this application. New image uploads
from the Media Library and all direct `/admin` image controls are stored on the application's
own `public` disk and served from `/storage/media/...`.

This document remains only for the one-time recovery of a legacy Cloudinary account. The full
runbook is [`self-hosted-media.md`](self-hosted-media.md).

## Recovery command

While the old account is still active, leave its credentials in production `.env` and run:

```bash
php artisan media:migrate-cloudinary --dry-run
php artisan media:migrate-cloudinary --confirm
php artisan optimize:clear
php artisan storage:link
```

The confirmed run:

- downloads every Cloudinary **image** URL stored anywhere in the production database;
- downloads the two former CSS-only backgrounds;
- inventories and copies every image in the configured Cloudinary account, including unreferenced
  assets;
- rewrites database URLs to the local `/storage/media/...` copies;
- retains the original address in `media_assets.source_url` for audit/retry; and
- never deletes a remote Cloudinary asset.

Do not remove old credentials or disable the account until the command exits without errors,
the site is tested in an uncached browser, and a backup of both the database and
`storage/app/public/media` has completed.

## Legacy clean options

`media:clean --purge-cloudinary` and the equivalent old admin checkbox are retained only to
clean an intentional legacy remote copy. They are not required for normal media operation and
must **not** be used during recovery. Self-hosted files remain available after a soft delete and
are removed only when a Media Library record is force-deleted.
