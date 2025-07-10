FROM php:8.2-fpm

# 必要パッケージのインストール
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libzip-dev libpq-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Laravelの依存関係インストール
COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
