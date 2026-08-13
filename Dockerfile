# Baka News — minimal PHP image for Render.
FROM php:8.2-cli-alpine

# Build deps: oniguruma-dev for mbstring, sqlite-dev for pdo_sqlite.
# ca-certificates lets PHP fetch the live weird-news RSS feeds over HTTPS.
RUN apk add --no-cache oniguruma-dev sqlite-dev ca-certificates \
 && update-ca-certificates \
 && docker-php-ext-install pdo_sqlite mbstring

WORKDIR /app
COPY . /app

# Writable dirs for SQLite + storage (mount a Render disk at /app/data/db to persist).
RUN mkdir -p /app/data/db /app/storage && chmod -R 0775 /app/data /app/storage

ENV BAKA_DB_PATH=/app/data/db/baka.sqlite
ENV BAKA_STORAGE=/app/storage

# Render provides $PORT. Serve everything through the router.
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} server.php"]
