# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Stage 1 — Install PHP dependencies (production only)
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress

# Bring in the source so the optimized autoloader can map the app classes
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

# ---------------------------------------------------------------------------
# Stage 2 — Build front-end assets (Vite / Vue / Inertia)
# ---------------------------------------------------------------------------
FROM node:22-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

# Vite needs the source + config to produce public/build
COPY vite.config.js postcss.config.js tailwind.config.js jsconfig.json ./
COPY resources ./resources

# app.js imports Ziggy from the Composer package (../../vendor/tightenco/ziggy),
# so make that package available to the Vite build from the vendor stage.
COPY --from=vendor /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

# Reverb (realtime) client config is baked into the JS at build time, so it must
# be available here. Pass these as build args in Coolify (public domain, wss:443).
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST
ARG VITE_REVERB_PORT=443
ARG VITE_REVERB_SCHEME=https
ENV VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY \
    VITE_REVERB_HOST=$VITE_REVERB_HOST \
    VITE_REVERB_PORT=$VITE_REVERB_PORT \
    VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME

RUN npm run build

# ---------------------------------------------------------------------------
# Stage 3 — Runtime (nginx + php-fpm + supervisor)
# ---------------------------------------------------------------------------
FROM php:8.3-fpm-alpine AS runtime

# PHP extensions via the install helper (handles build deps automatically)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
# Retry to survive transient Alpine-mirror / DNS hiccups on the build host;
# fails the build only if every attempt fails.
RUN i=0; until install-php-extensions pdo_mysql pdo_pgsql pgsql pdo_sqlite mbstring bcmath gd zip intl exif pcntl opcache; do \
        i=$((i+1)); [ "$i" -ge 5 ] && echo "install-php-extensions failed after $i attempts" && exit 1; \
        echo "install-php-extensions retry $i in 8s..."; sleep 8; \
    done

# System packages: nginx + supervisor
RUN apk add --no-cache nginx supervisor && \
    mkdir -p /run/nginx

WORKDIR /var/www/html

# Application source (vendor/build are overlaid from the build stages below)
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Container configuration
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Normalize line endings (files may be authored on Windows) and make executable
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && \
    chmod +x /usr/local/bin/entrypoint.sh && \
    chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

# Coolify health check hits Laravel's built-in /up endpoint
HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD wget -qO- http://127.0.0.1:80/up >/dev/null 2>&1 || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
