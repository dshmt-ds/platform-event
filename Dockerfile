FROM php:8.3-fpm-alpine

# Install extensions database
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies tanpa paket dev
RUN composer install --no-dev --optimize-autoloader

# Berikan izin akses penuh untuk folder writable Laravel
RUN chmod -R 777 storage bootstrap/cache

# Buka port internal container
EXPOSE 8080

# PERBAIKAN DI SINI: Menggunakan sintaksis array JSON untuk CMD agar sinyal port Railway terbaca dengan tepat
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]