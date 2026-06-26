# ==============================================================================
# Stage 1: Build Frontend Assets (Node)
# ==============================================================================
FROM node:20-alpine AS frontend-build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY vite.config.js ./
COPY resources/css/ ./resources/css/
COPY resources/js/ ./resources/js/

RUN npm run build

# ==============================================================================
# Stage 2: Production (PHP-FPM + Nginx)
# ==============================================================================
FROM php:8.4-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    freetype-dev \
    pkgconfig \
    postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath gd xml dom curl intl opcache \
    && apk del --purge freetype-dev libjpeg-turbo-dev

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy only what's needed for composer install
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize --no-dev

# Copy application code
COPY . .

# Copy built frontend assets from stage 1
COPY --from=frontend-build /app/public/build/ ./public/build/

# Create required directories
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy opcache config
COPY docker/php-opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Remove docker directory from production
RUN rm -rf docker/

EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8080/up || exit 1

# Startup script
RUN printf '#!/bin/sh\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan migrate --force 2>/dev/null || true\n\
php-fpm -D\n\
nginx -g "daemon off;"\n' \
> /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
