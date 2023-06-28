FROM php:apache

RUN apt-get update && apt-get install -y \
    libicu-dev

RUN docker-php-ext-install intl pdo pdo_mysql

RUN a2enmod rewrite