# Maverick Academy website

Laravel 13 + Filament 3 application for the Maverick Business Academy marketing site.
Public pages are driven by Spatie settings and a handful of content models; editors manage
them at `/admin`. Media is stored on Cloudinary. Form notifications use Zoho Mail (or the
default Laravel mailer), newsletter signups can sync to Zoho Campaigns, and lead events can
be forwarded to Zapier webhooks configured in the admin panel.

## Requirements

- PHP 8.3+ with `bcmath`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `pdo_sqlite`/`pdo_mysql`, `xml`, `zip`
- Composer 2
- Node 20+ (only to build Vite/Tailwind assets locally)
- MySQL or SQLite for local development
- Cloudinary credentials for admin media uploads

## Local setup

```bash
git clone <this-repo> && cd maverick
composer install
cp .env.example .env
php artisan key:generate
# Edit .env: DB_*, APP_URL, Cloudinary keys (see below)
php artisan migrate
php artisan db:seed                    # demo content only; safe on a fresh local DB
php artisan make:filament-user         # create your /admin login
npm ci --ignore-scripts && npm run build
php artisan serve
```

Admin panel: `http://localhost:8000/admin`.

## Configuration

All deployment-specific values live in `.env` (never commit it). The important groups:

| Group | Variables | Notes |
|---|---|---|
| App | `APP_URL`, `APP_ENV`, `APP_DEBUG` | Set the real public URL before caching config. |
| Origin | `PREFERRED_ORIGIN_URL`, `PREFERRED_ORIGIN_HOSTS` | Canonical URL and aliases consolidated by redirects/canonicals/sitemap. Defaults match production. |
| Mail fallback | `CONTACT_FALLBACK_EMAIL` | Last-resort form recipient when all admin settings are empty. |
| Cloudinary | `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`, `CLOUDINARY_UPLOAD_FOLDER` | Required for the media library. See `docs/cloudinary-guide.md`. |
| reCAPTCHA | `RECAPTCHA_ENABLED`, `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY`, `RECAPTCHA_ALLOWED_HOSTNAMES` | v2 checkbox. Leave disabled until all values are set; public forms remain usable without it. Enable on production and include the exact serving hostname (comma-separated). |
| Proxy / preview | `TRUSTED_PROXIES`, `APP_ALLOWED_URL_HOSTS` | Only needed behind a known proxy or preview host. Supports `*.e2b.app` style wildcards. |
| Zapier | configured at `/admin` | Webhook URLs and event subscriptions are stored in the database, not `.env`. |

Zoho Mail, Zoho Campaigns, mail template HTML, SEO fields and all page content are managed
in `/admin`; see `ZOHO_ZAPIER_SETUP.md` and `docs/zoho-setup-step-by-step.md`.

After changing `.env` or `config/*` on a server, rebuild caches:

```bash
php artisan optimize:clear && php artisan config:cache
```

## Tests and code style

```bash
composer test                 # php artisan test (SQLite in-memory)
npm test                      # JS regression tests in tests/js/
npm run build                 # Vite build
vendor/bin/pint --test        # formatting
```

The same PHP and JS checks run in GitHub Actions on pushes and pull requests.

## Deployment

For cPanel/LiteSpeed shared hosting, follow `docs/SHARED_HOSTING.md` and run
`bash scripts/shared-hosting-optimize.sh` after every deploy. The document root must be
`public/`. Never run `php artisan key:generate` on an existing production `.env`.

## Media maintenance

```bash
php artisan media:clean                         # dry run
php artisan media:clean --confirm               # recycle unused rows
php artisan media:clean --confirm --purge-cloudinary --include-orphans
```

Destructive runs are refused if any reference-table scan fails. See `docs/cloudinary-guide.md`.

## Further documentation

- `docs/SHARED_HOSTING.md` — production deploy/hardening checklist
- `ZOHO_ZAPIER_SETUP.md`, `docs/zoho-setup-step-by-step.md` — form email, newsletter, webhooks
- `docs/cloudinary-guide.md` — media library
- `docs/mlp-design-system.md`, `docs/program-content-template.md` — content conventions
- `docs/REMEDIATION_PHASES.md` — review follow-up tracking sheet
