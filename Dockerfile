FROM php:8.2-fpm

# Node.js 18.x をインストール（Viteやnpm用）
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get update && apt-get install -y \
    git curl zip unzip nodejs \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Composer をコピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www

# アプリケーションをコピー
COPY . .

# Composer install（本番向け最適化）
RUN composer install --no-dev --optimize-autoloader

# npm install & build（ViteやMixを使用する前提）
RUN npm install && npm run build

# WebポートをRenderに合わせる
EXPOSE 10000

# アプリケーション起動時のコマンド
CMD php artisan config:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force \
    && php artisan storage:link \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
