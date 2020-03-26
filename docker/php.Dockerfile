FROM php:7-fpm

WORKDIR /var/www/html/api

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y git libzip-dev unzip \
    && docker-php-ext-install \
        pdo_mysql zip \
    && docker-php-ext-enable \
        pdo_mysql zip 