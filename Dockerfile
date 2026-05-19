FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libicu-dev nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring bcmath zip gd intl \
    && apt-get clean

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

RUN mkdir -p database && touch database/database.sqlite
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=80"]
