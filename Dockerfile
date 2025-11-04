# 🧱 Base image: PHP 8.2 + Apache (ổn định, nhẹ)
FROM php:8.2-apache

<<<<<<< HEAD
# Cài đặt dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Giảm warning
RUN echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_NOTICE" > /usr/local/etc/php/conf.d/error.ini

# Copy source
WORKDIR /var/www/html
COPY . .

# Set quyền
RUN chmod -R 777 storage bootstrap/cache

# ❗ Chặn artisan chạy tự động bằng cách disable scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Sau khi install xong mới chạy thủ công package discovery
RUN php artisan key:generate || true
RUN php artisan package:discover || true
=======
# 👇 Tắt warning PHP (cho sạch log)
RUN echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_NOTICE" > /usr/local/etc/php/conf.d/error.ini

# 🧩 Cài extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 📦 Cài composer từ image chính thức (khỏi tải thêm)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🏗️ Thiết lập thư mục làm việc
WORKDIR /var/www/html
COPY . .

# ⚡ Fix quyền truy cập cho Laravel (storage, cache)
RUN chmod -R 777 storage bootstrap/cache || true
>>>>>>> 657fa01 (Optimized Dockerfile for Render)

# ⚙️ Giới hạn composer, tránh tắt do thiếu RAM
ENV COMPOSER_MEMORY_LIMIT=-1

# 💨 Cài đặt Laravel dependencies mà không chạy dev
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

# 🔑 Tạo APP_KEY nếu chưa có
RUN php artisan key:generate --force || true

# 🌍 Expose port 8080 cho Render
EXPOSE 8080

# 🧠 Lệnh khởi chạy app Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
