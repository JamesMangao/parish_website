FROM php:8.2-cli

# Install system deps
RUN apt-get update && apt-get install -y \
    curl unzip git nodejs npm \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Install JS deps and build
RUN npm install && npm run build

# Laravel caches
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
