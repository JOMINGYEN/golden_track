FROM php:8.2-apache

RUN apt-get update && apt-get install -y     git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev     && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN chmod -R 777 storage bootstrap/cache
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate

EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=8080
