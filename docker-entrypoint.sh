#!/bin/sh
set -e

# Support Render dynamic PORT
PORT="${PORT:-80}"
echo "[Render Entrypoint] Configuring Apache to listen on port ${PORT}..."
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf

# Create SQLite database file if DB_CONNECTION is sqlite and file doesn't exist
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    SQLITE_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    if [ ! -f "${SQLITE_PATH}" ]; then
        echo "[Render Entrypoint] Creating SQLite database file at ${SQLITE_PATH}..."
        mkdir -p "$(dirname "${SQLITE_PATH}")"
        touch "${SQLITE_PATH}"
        chown www-data:www-data "${SQLITE_PATH}"
    fi
fi

# Ensure storage symlink exists
php artisan storage:link || true

# Run database migrations if RUN_MIGRATIONS or AUTO_MIGRATE is true
if [ "${RUN_MIGRATIONS}" = "true" ] || [ "${AUTO_MIGRATE}" = "true" ]; then
    echo "[Render Entrypoint] Running database migrations..."
    php artisan migrate --force || echo "[Render Entrypoint] Warning: Migration failed. Check your database connection settings."
fi

# Optimization caches for production
if [ "${APP_ENV}" = "production" ]; then
    echo "[Render Entrypoint] Caching configuration, routes, and views for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    php artisan config:clear || true
fi

echo "[Render Entrypoint] Starting Apache web server on port ${PORT}..."
exec "$@"
