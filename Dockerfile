# ==========================================
# Multi-stage Dockerfile for Laravel on Render
# ==========================================

# ------------------------------------------
# Stage 1: Build Frontend Assets (Vite)
# ------------------------------------------
FROM node:20-alpine AS node_builder
WORKDIR /app

COPY package*.json ./
RUN npm ci --ignore-scripts

COPY vite.config.* ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ------------------------------------------
# Stage 2: Install PHP Composer Dependencies
# ------------------------------------------
FROM composer:2 AS composer_builder
WORKDIR /app

COPY composer*.json ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .

# Copy compiled frontend assets from node_builder
COPY --from=node_builder /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev --ignore-platform-reqs

# ------------------------------------------
# Stage 3: Production Runtime (PHP 8.4 Apache)
# ------------------------------------------
FROM php:8.4-apache AS runner

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions for Laravel
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    bcmath \
    gd \
    zip \
    opcache \
    intl \
    pcntl

# Configure Apache DocumentRoot for Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers

# Configure PHP settings for Production
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'upload_max_filesize=64M'; \
        echo 'post_max_size=64M'; \
        echo 'memory_limit=256M'; \
        echo 'max_execution_time=120'; \
    } > /usr/local/etc/php/conf.d/laravel.ini

WORKDIR /var/www/html

# Copy application from composer_builder stage
COPY --from=composer_builder --chown=www-data:www-data /app /var/www/html

# Ensure proper storage & cache permissions
RUN mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and prepare entrypoint script (strip CRLF just in case)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Default port exposed (Render will set $PORT dynamically, e.g. 10000)
EXPOSE 80 10000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
