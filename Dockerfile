FROM php:8.4-cli

# Install system deps
RUN apt-get update && apt-get install -y \
    curl unzip git nodejs npm \
    libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev libcurl4-openssl-dev pkg-config libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath gd \
       xml dom curl intl opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize --no-dev

# Install JS deps and build
RUN npm install && npm run build

EXPOSE 10000

# Create startup script (caching + migration at runtime when env vars exist)
RUN printf '#!/bin/sh\nphp artisan config:cache\nphp artisan route:cache\nphp artisan view:cache\nphp artisan migrate --force\nexec php artisan serve --host=0.0.0.0 --port=10000\n' \
    > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
