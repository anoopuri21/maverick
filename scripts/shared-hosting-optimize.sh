#!/usr/bin/env bash
# Safe production optimize for cPanel / LiteSpeed / shared hosting.
# Usage (from app root, after git pull + composer install --no-dev):
#   bash scripts/shared-hosting-optimize.sh
#
# NEVER run key:generate on an existing production .env (breaks encrypted data).

set -euo pipefail

cd "$(dirname "$0")/.."

if [[ ! -f .env ]]; then
  echo "ERROR: .env missing. Copy .env.example, set APP_KEY once, then re-run."
  exit 1
fi

if ! grep -qE '^APP_KEY=base64:' .env; then
  echo "ERROR: APP_KEY is empty or invalid. On FIRST deploy only:"
  echo "  php artisan key:generate --force"
  echo "Then re-run this script. Do not regenerate on subsequent deploys."
  exit 1
fi

if grep -qE '^APP_DEBUG=true' .env; then
  echo "WARNING: APP_DEBUG=true — set APP_DEBUG=false for production."
fi

php artisan down --retry=60 || true

php artisan migrate --force

# Clear stale compiled files, then rebuild caches in a safe order.
php artisan optimize:clear
php artisan storage:link --force 2>/dev/null || php artisan storage:link || true
php artisan event:cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache 2>/dev/null || true
php artisan filament:cache-components 2>/dev/null || true

php artisan up

echo "OK: config/route/view caches rebuilt. Document root must be /public."
