FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev \
    libxml2-dev libzip-dev nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
