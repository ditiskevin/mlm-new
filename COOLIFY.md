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

# Realtime chat (Laravel Reverb) — see the "Realtime" section below
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_APP_SECRET=...
# Server-side publishing happens inside the container, so point at localhost:
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
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
(`queue:work`), the **scheduler** (`schedule:work`) and the **Reverb**
websocket server (`reverb:start` on `:8080`, proxied by nginx at `/app`).

## Realtime chat (Laravel Reverb)

Private messages update live over WebSockets. The image already runs the Reverb
server under supervisor and nginx proxies the `/app` path to it, so no extra
container or exposed port is needed — clients connect over the normal HTTPS
domain (`wss://your-domain.tld/app`).

Two things must be configured:

1. **Runtime env** (server-side publishing) — set `BROADCAST_CONNECTION=reverb`
   and the `REVERB_APP_ID/KEY/SECRET` plus `REVERB_HOST=127.0.0.1`,
   `REVERB_PORT=8080`, `REVERB_SCHEME=http` (the app talks to the local Reverb).
   Generate the app credentials once with `php artisan reverb:install`.

2. **Build args** (browser client) — the `VITE_REVERB_*` values are compiled into
   the JS at image-build time, so pass them as **build arguments** in Coolify
   (Build → Build Variables), pointing at the *public* domain over TLS:

   ```
   VITE_REVERB_APP_KEY=<same as REVERB_APP_KEY>
   VITE_REVERB_HOST=your-domain.tld
   VITE_REVERB_PORT=443
   VITE_REVERB_SCHEME=https
   ```

   These must match `REVERB_APP_KEY`; if they're missing the chat silently falls
   back to polling-free, non-live behaviour (messages still send, they just don't
   appear until the page is reloaded).

### Local development

```bash
php artisan reverb:start      # websocket server on :8080
npm run dev                   # or `npm run build`
php artisan serve             # the app
```

The committed `.env`/`.env.example` already point the dev client at
`localhost:8080`, so realtime works out of the box once `reverb:start` is running.

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
