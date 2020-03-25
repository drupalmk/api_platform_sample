FROM php:7-fpm

RUN apt-get update && apt-get install -y \
    && docker-php-ext-install \
        pdo pdo_mysql \
    && docker-php-ext-enable \
        pdo pdo_mysql