# Deploying to Coolify

This app ships with a production `Dockerfile` (nginx + php-fpm + supervisor) that
builds the Vite assets and PHP dependencies, runs migrations on boot, and serves
the queue worker and scheduler alongside the web server in one container.

## Option A — Dockerfile build pack (recommended)

1. **New Resource → Application → your Git repository.**
2. **Build Pack:** `Dockerfile`. Coolify auto-detects the `Dockerfile` in the repo root.
3. **Ports Exposed:** `80`.
4. **Health check:** already defined in the image (`GET /up`). No extra config needed.
5. Attach a **MySQL/MariaDB** (and optionally **Redis**) resource from Coolify and
   copy its connection details into the env vars below.
6. **Persistent storage:** add a volume mount so uploads survive redeploys:
   - Source: (named volume) → Destination: `/var/www/html/storage/app/public`
7. Set the environment variables (next section) and **Deploy**.

## Option B — Docker Compose build pack

Use the bundled `docker-compose.yml` (app + MariaDB + Redis, all with volumes).
Select **Build Pack: Docker Compose**, set `APP_KEY`, `APP_URL` and `DB_PASSWORD`,
and deploy. Everything else has sensible defaults.

## Required environment variables

```dotenv
APP_NAME=MommyLovesMe
APP_ENV=production
APP_DEBUG=false
APP_KEY=            # generate locally: php artisan key:generate --show
APP_URL=https://your-domain.tld   # must match the Coolify domain (for correct asset/cookie URLs)

LOG_CHANNEL=stderr  # so logs appear in Coolify's log viewer
LOG_LEVEL=warning

# Database (point at your Coolify DB resource)
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=mommylovesme
DB_USERNAME=mommylovesme
DB_PASSWORD=...

# These work out of the box on a single container
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database   # or "redis" if you attach a Redis resource

# Mail (set real SMTP for password resets / verification)
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=hello@your-domain.tld
MAIL_FROM_NAME="${APP_NAME}"
```

> **Set a permanent `APP_KEY`.** If left empty the container generates a temporary
> one on each boot, which invalidates all sessions and encrypted data on redeploy.

## What happens on each deploy

The entrypoint (`docker/entrypoint.sh`) runs automatically:

1. Creates writable `storage/` + `bootstrap/cache` dirs and fixes ownership.
2. Creates the `public/storage` symlink.
3. Runs `php artisan migrate --force` (disable with `RUN_MIGRATIONS=false`).
4. Rebuilds `config`, `route`, `view` and `event` caches for production.

Then supervisor starts **php-fpm**, **nginx**, the **queue worker**
(`queue:work`) and the **scheduler** (`schedule:work`).

## Files added for deployment

| File | Purpose |
|------|---------|
| `Dockerfile` | Multi-stage production image |
| `.dockerignore` | Keeps the build context lean |
| `docker/nginx.conf` | Web server config (serves `public/`) |
| `docker/php.ini` | Production PHP + OPcache tuning |
| `docker/supervisord.conf` | Runs fpm, nginx, queue, scheduler |
| `docker/entrypoint.sh` | Boot tasks (migrate, cache, storage link) |
| `docker-compose.yml` | Optional full stack for Compose build pack |

## Local sanity check (optional)

```bash
docker build -t mommylovesme .
docker run --rm -p 8080:80 \
  -e APP_KEY="$(php artisan key:generate --show)" \
  -e APP_URL=http://localhost:8080 \
  -e DB_CONNECTION=sqlite \
  -e RUN_MIGRATIONS=true \
  mommylovesme
# open http://localhost:8080/up  -> should return 200
```
