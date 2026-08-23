# Shared hosting hardening — Maverick Academy

Prioritized checklist for cPanel / LiteSpeed / limited-resource hosts (e.g. `demo.vsinfosys.in`).
No root SSH is required. Use the account PHP binary (cPanel “ea-php”), not system `php`.

## Exact deploy runbook (no root)

PHP 8.3 path may differ; check **cPanel → Select PHP Version** or `ls /opt/cpanel/ea-php*/root/usr/bin/php`.

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
alias composer='/opt/cpanel/ea-php83/root/usr/bin/php /usr/local/bin/composer'
```

### A — First deploy only (empty host)

1. Point the vhost / addon domain **document root** at `public/` (never the repo root).
2. Upload or `git clone` the app so `public/` is that document root’s parent-child.
3. Copy env and set production values (do this **before** caching config):

```bash
cd ~/demo.vsinfosys.in
cp .env.example .env
# Edit .env: APP_ENV=production, APP_DEBUG=false, APP_URL=https://your-domain.tld,
# DB_*, CACHE_STORE=file, SESSION_DRIVER=file, QUEUE_CONNECTION=sync,
# Cloudinary keys, APP_KEY still empty.
php artisan key:generate
```

4. Install PHP deps and build Laravel caches:

```bash
composer install --no-dev --optimize-autoloader --no-interaction
bash scripts/shared-hosting-optimize.sh
```

5. Smoke: `GET /up` → 200, homepage loads, admin login works.

**Never run `php artisan key:generate` again on this `.env`.**

Do **not** run `npm` on the shared host. Marketing CSS/JS live in `public/assets/` (already committed). If you change Vite/Tailwind (`resources/css`, `resources/js`), run `npm run build` **locally or in CI** and deploy `public/build/` with the release.

### B — Every later deploy (same order, every time)

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
alias composer='/opt/cpanel/ea-php83/root/usr/bin/php /usr/local/bin/composer'

cd ~/demo.vsinfosys.in
git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction
bash scripts/shared-hosting-optimize.sh
```

`scripts/shared-hosting-optimize.sh` (in order):

1. `php artisan down --retry=60`
2. `php artisan migrate --force`
3. `php artisan optimize:clear`
4. `php artisan storage:link`
5. `php artisan event:cache`
6. `php artisan config:cache`
7. `php artisan route:cache`
8. `php artisan view:cache`
9. `php artisan icons:cache` / `php artisan filament:cache-components` when the commands exist
10. `php artisan up`

Do **not** start Redis, `php artisan serve`, or a long-running `queue:work` daemon.

Equivalent Composer shortcut after a pull (does not migrate or `down`/`up`): `composer optimize:hosting`

## P0 — Do before go-live

| # | Item | Status / action |
|---|------|-----------------|
| 1 | Document root = `public/` | Required. Never point the vhost at the repo root. |
| 2 | `.env` exists with a stable `APP_KEY` | Generate **once** on first deploy: `php artisan key:generate`. **Never** regenerate on later deploys. |
| 3 | `APP_ENV=production` and `APP_DEBUG=false` | Leaking stack traces is a security + disk risk. |
| 4 | Cloudinary credentials set | Media is Cloudinary-only; without creds admin uploads fail (by design). |
| 5 | Run optimize script after each deploy | `bash scripts/shared-hosting-optimize.sh` |

## P1 — Caching workflow (config / route / view)

After changing `.env` or `config/*` only:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### APP_KEY rules

| Situation | Command |
|-----------|---------|
| Brand-new empty `.env` | `cp .env.example .env` then `php artisan key:generate` **once** |
| Existing production | Leave `APP_KEY` alone. Changing it invalidates sessions/cookies and any encrypted values. |
| After `config:cache` | `.env` is still read at bootstrap for the key; keep `.env` on the server and **not** in git. |

## P2 — Queue & scheduler (no daemons)

Defaults (shared-hosting friendly):

- `QUEUE_CONNECTION=sync` — jobs run inline; no worker needed
- There are currently **no** `ShouldQueue` jobs in the app

Optional database queue (only if you start queueing mail/jobs later):

```env
QUEUE_CONNECTION=database
```

Then add a **cron** (not a forever worker):

```cron
* * * * * cd /home/USER/maverick-academy && /opt/cpanel/ea-php83/root/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

When `QUEUE_CONNECTION=database`, the scheduler drains the queue each minute with:

`queue:work --stop-when-empty --max-time=50`

## P3 — Web server / static assets

- Apache/LiteSpeed: hardened `public/.htaccess` (gzip/brotli, long-cache CSS/JS/images, no-cache HTML/PHP, basic security headers)
- Nginx: see `deploy/nginx-shared-hosting.conf.example`
- PHP limits: `public/.user.ini` (`upload_max_filesize=8M`, `memory_limit=256M`, OPCache hints)

## P4 — Media / uploads

| Layer | Behavior |
|-------|----------|
| Permanent media | Cloudinary only (`MediaLibraryService` + `CloudinaryService`) |
| Temp uploads | `storage/app/private/livewire-tmp` via Livewire (`disk=local`, max **5MB**, images only, auto-cleanup) |
| Hard limit | `config/media.php` → `MEDIA_MAX_UPLOAD_KB` (default 5120) enforced in `MediaLibraryService` |

Do **not** store large originals under `public/` or `storage/app/public`. Prefer Cloudinary URLs in the DB.

## P5 — Database

- Keep migrations applied: `php artisan migrate --force`
- Performance indexes: `2026_07_21_*`, `2026_08_22_020000_*`, `2026_08_22_030000_*`, `2026_08_22_040000_*`
- Prefer `select()` / `with()` already used in controllers; avoid loading full `content` columns on index pages
- Public page data uses Laravel **file** cache (`PublicContentCache`, 24h TTL) and is flushed when admin saves settings or public models. No Redis.

## P6 — Cache / session / logs (recommended `.env`)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.tld

QUEUE_CONNECTION=sync
CACHE_STORE=file
SESSION_DRIVER=file
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=error
LOG_DAILY_DAYS=7

FILESYSTEM_DISK=local
MEDIA_MAX_UPLOAD_KB=5120

CLOUDINARY_CLOUD_NAME=...
CLOUDINARY_API_KEY=...
CLOUDINARY_API_SECRET=...
CLOUDINARY_UPLOAD_FOLDER=maverick-academy
CLOUDINARY_ENV_FOLDER=false
CLOUDINARY_DISK_ENV=shared
```

`file` cache/session reduces MySQL load on small shared plans. Use `database` sessions only if you need sticky sessions across multiple nodes (rare on shared hosting).

Fingerprinted URLs: Blade uses `cached_asset()` (`?v=filemtime`) so CSS/JS/images can keep 1-year `Cache-Control: immutable` in `public/.htaccess` and still bust after a deploy.

## P7 — OPCache (recommended host settings)

If the panel exposes OPCache / `php.ini`:

| Setting | Suggested |
|---------|-----------|
| `opcache.enable` | `1` |
| `opcache.memory_consumption` | `128`–`256` |
| `opcache.max_accelerated_files` | `10000`+ |
| `opcache.validate_timestamps` | `1` on shared hosts |
| `opcache.revalidate_freq` | `60` (seconds) |
| `opcache.save_comments` | `1` (needed by some frameworks) |

After each deploy, OPCache will pick up new files within `revalidate_freq`. If the host allows `opcache_reset()` via a one-off script, run it after deploy; otherwise wait ~60s.

`public/.user.ini` already suggests these values where the host honors per-directory INI.

## Post-deploy smoke test

1. `GET /up` → 200
2. Homepage + one program + blog index load
3. Admin login works
4. Cloudinary image on a CMS page loads over HTTPS
5. `storage/logs` is not exploding (daily + `LOG_LEVEL=error`)
6. Confirm `bootstrap/cache/config.php` exists after optimize

## Rollback (broken cache)

```bash
php artisan optimize:clear
php artisan up
```

Then fix `.env` / code and re-run `scripts/shared-hosting-optimize.sh`.
