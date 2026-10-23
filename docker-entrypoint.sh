#!/bin/sh
set -e

# Support Render dynamic PORT
PORT="${PORT:-80}"
echo "[Render Entrypoint] Configuring Apache to listen on port ${PORT}..."
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf

# Sanitize Database Environment Variables (strip accidental trailing newlines/spaces)
if [ -n "${DB_HOST}" ]; then
    CLEAN_DB_HOST="$(printf '%s' "${DB_HOST}" | tr -d '\r\n ')"
    export DB_HOST="${CLEAN_DB_HOST}"
fi
if [ -n "${DB_PORT}" ]; then
    CLEAN_DB_PORT="$(printf '%s' "${DB_PORT}" | tr -d '\r\n ')"
    export DB_PORT="${CLEAN_DB_PORT}"
fi
if [ -n "${DB_DATABASE}" ]; then
    CLEAN_DB_DATABASE="$(printf '%s' "${DB_DATABASE}" | tr -d '\r\n ')"
    export DB_DATABASE="${CLEAN_DB_DATABASE}"
fi
if [ -n "${DB_USERNAME}" ]; then
    CLEAN_DB_USERNAME="$(printf '%s' "${DB_USERNAME}" | tr -d '\r\n ')"
    export DB_USERNAME="${CLEAN_DB_USERNAME}"
fi

# Auto-enable SSL/TLS CA for secure cloud databases (TiDB, Aiven, etc.)
if [ -z "${MYSQL_ATTR_SSL_CA}" ] && [ -f "/etc/ssl/certs/ca-certificates.crt" ]; then
    echo "[Render Entrypoint] Enabling SSL CA for database connection (/etc/ssl/certs/ca-certificates.crt)..."
    export MYSQL_ATTR_SSL_CA="/etc/ssl/certs/ca-certificates.crt"
fi

# Ensure storage directories exist
mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/bootstrap/cache

# Ensure log file exists with write permissions for all
touch /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log || true

# Default LOG_CHANNEL to stderr if not set (streams directly to Render dashboard logs)
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

# Create SQLite database file if DB_CONNECTION is sqlite and file doesn't exist
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    SQLITE_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    if [ ! -f "${SQLITE_PATH}" ]; then
        echo "[Render Entrypoint] Creating SQLite database file at ${SQLITE_PATH}..."
        mkdir -p "$(dirname "${SQLITE_PATH}")"
        touch "${SQLITE_PATH}"
        chown www-data:www-data "${SQLITE_PATH}"
        chmod 664 "${SQLITE_PATH}"
    fi
fi

# Ensure storage symlink exists
php artisan storage:link || true

# Run database migrations if RUN_MIGRATIONS or AUTO_MIGRATE is true
if [ "${RUN_MIGRATIONS}" = "true" ] || [ "${AUTO_MIGRATE}" = "true" ]; then
    echo "[Render Entrypoint] Running database migrations..."
    php artisan migrate --force || echo "[Render Entrypoint] Warning: Migration failed. Check your database connection settings."

    # Auto-seed initial store and accounts if users table is empty
    USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "1")
    if [ "${USER_COUNT}" = "0" ] || [ "${RUN_SEEDER}" = "true" ]; then
        echo "[Render Entrypoint] Initializing store data and default accounts..."
        php artisan db:seed --force || echo "[Render Entrypoint] Warning: Seeding failed."
    fi
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

# Fix ownership and permissions for www-data before launching Apache
echo "[Render Entrypoint] Ensuring storage and cache permissions for www-data..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage/logs /var/www/html/storage/framework

echo "[Render Entrypoint] Starting Apache web server on port ${PORT}..."
exec "$@"
