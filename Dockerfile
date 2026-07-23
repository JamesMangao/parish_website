# ==========================================================
# Stage 1 - Build Frontend Assets
# ==========================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build


# ==========================================================
# Stage 2 - Production
# ==========================================================
FROM php:8.4-fpm-alpine

# ----------------------------------------------------------
# Install system packages
# ----------------------------------------------------------
RUN apk add --no-cache \
    nginx \
    git \
    unzip \
    curl-dev \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libxml2-dev \
    postgresql-dev \
    libpq-dev

# ----------------------------------------------------------
# Install PHP extensions
# ----------------------------------------------------------
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        zip \
        exif \
        bcmath \
        gd \
        intl \
        opcache

# ----------------------------------------------------------
# Install Composer
# ----------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ----------------------------------------------------------
# Install Composer dependencies
# ----------------------------------------------------------
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# ----------------------------------------------------------
# Copy application
# ----------------------------------------------------------
COPY . .

# ----------------------------------------------------------
# Laravel package discovery
# ----------------------------------------------------------
RUN php artisan package:discover --ansi

# ----------------------------------------------------------
# Copy frontend build
# ----------------------------------------------------------
COPY --from=frontend /app/public/build ./public/build

# ----------------------------------------------------------
# Storage & cache directories
# ----------------------------------------------------------
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    /var/log/nginx \
    /var/cache/nginx \
    /etc/nginx/http.d

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ----------------------------------------------------------
# PHP Opcache
# ----------------------------------------------------------
RUN cat <<EOF > /usr/local/etc/php/conf.d/opcache.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.jit=1255
opcache.jit_buffer_size=64M
EOF

# ----------------------------------------------------------
# PHP-FPM listen
# ----------------------------------------------------------
RUN sed -i \
's|listen = /run/nginx-v8.sock|listen = 127.0.0.1:9000|' \
/usr/local/etc/php-fpm.d/zz-docker.conf

# ----------------------------------------------------------
# PHP-FPM tuning
# ----------------------------------------------------------
RUN cat <<EOF > /usr/local/etc/php-fpm.d/zz-tuning.conf
[www]
pm = dynamic
pm.max_children = 25
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 15
EOF

# ----------------------------------------------------------
# Nginx
# ----------------------------------------------------------
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# ----------------------------------------------------------
# Startup script
# ----------------------------------------------------------
RUN cat <<'EOF' > /usr/local/bin/start.sh
#!/bin/sh

set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Clearing old caches..."
php artisan optimize:clear

echo "Optimizing Laravel..."
php artisan optimize

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g "daemon off;"
EOF

RUN chmod +x /usr/local/bin/start.sh

# ----------------------------------------------------------
# Expose HTTP
# ----------------------------------------------------------
EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
