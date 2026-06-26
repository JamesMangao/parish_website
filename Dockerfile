# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources/ ./resources/
RUN npm run build

# Stage 2: Production image
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    curl-dev icu-dev oniguruma-dev libzip-dev libpng-dev \
    libjpeg-turbo-dev libxml2-dev postgresql-dev \
    libpq-dev git unzip

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif bcmath gd opcache intl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize --no-dev --no-scripts

COPY . .

# Discover packages after full source is available
RUN php artisan package:discover --ansi

# Copy built frontend assets from stage 1
COPY --from=frontend /app/public/build ./public/build

# Cache configs
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# PHP-FPM config
RUN mkdir -p /usr/local/etc/php/conf.d \
    && echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.jit=1255" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.jit_buffer_size=64M" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "pm.status_path = /status" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Startup script
RUN printf '#!/bin/sh\nphp artisan migrate --force\nexec php-fpm\n' \
    > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]
