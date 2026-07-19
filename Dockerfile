FROM php:8.3-fpm-alpine

RUN apk add --no-cache nodejs npm
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "npm run build && php artisan serve --host=0.0.0.0 --port=8080"]