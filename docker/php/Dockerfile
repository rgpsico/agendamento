FROM php:8.4-fpm

# Dependências do sistema (já tem as principais, adicionamos só o que falta para builds de extensões PECL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    supervisor \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        intl \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pcntl

# Instala a extensão Redis via PECL (phpredis)
RUN pecl install redis \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Supervisor config
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/laravel-worker.conf
RUN mkdir -p /var/log/supervisor

WORKDIR /var/www