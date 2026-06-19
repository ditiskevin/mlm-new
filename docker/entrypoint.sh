#!/bin/sh
set -e

cd /var/www/html

echo "[entrypoint] Booting MommyLovesMe..."

# ---------------------------------------------------------------------------
# Ensure writable runtime directories exist (covers fresh persistent volumes)
# ---------------------------------------------------------------------------
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# ---------------------------------------------------------------------------
# APP_KEY guard — required for encryption/sessions
# ---------------------------------------------------------------------------
if [ -z "${APP_KEY}" ]; then
    echo "[entrypoint] WARNING: APP_KEY is empty. Generating a temporary key."
    echo "[entrypoint] Set a permanent APP_KEY in Coolify (php artisan key:generate --show)"
    echo "[entrypoint] or sessions/encrypted data will break on every redeploy."
    export APP_KEY="$(php artisan key:generate --show --no-interaction)"
fi

# ---------------------------------------------------------------------------
# Storage symlink (public/storage -> storage/app/public)
# ---------------------------------------------------------------------------
php artisan storage:link --no-interaction 2>/dev/null || true

# ---------------------------------------------------------------------------
# Database migrations (skip gracefully if no DB is configured yet)
# ---------------------------------------------------------------------------
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Running migrations..."
    php artisan migrate --force --no-interaction || \
        echo "[entrypoint] WARNING: migrations failed (is the database reachable?)"
fi

# ---------------------------------------------------------------------------
# Seed standard template content (doelgroepen / "Ik ben", checklists,
# zwangerschapsweken, namen, forumcategorieën, groepen, startartikelen).
# Idempotent + self-guarded: skips when the site is already seeded, so a
# redeploy never overwrites admin edits. Set SEED_FRESH=true to force a re-sync.
# ---------------------------------------------------------------------------
if [ "${RUN_SEEDERS:-true}" = "true" ]; then
    echo "[entrypoint] Seeding template content..."
    php artisan db:seed --class=TemplateSeeder --force --no-interaction || \
        echo "[entrypoint] WARNING: seeding failed (is the database reachable?)"
fi

# ---------------------------------------------------------------------------
# Optimize: rebuild config/route/view/event caches for production
# ---------------------------------------------------------------------------
php artisan optimize:clear --no-interaction || true
# Composer scripts are skipped during build, so discover packages now
php artisan package:discover --no-interaction || true
php artisan config:cache --no-interaction
php artisan route:cache  --no-interaction || php artisan route:clear --no-interaction
php artisan view:cache   --no-interaction || true
php artisan event:cache  --no-interaction || true

echo "[entrypoint] Ready. Handing over to: $*"
exec "$@"
