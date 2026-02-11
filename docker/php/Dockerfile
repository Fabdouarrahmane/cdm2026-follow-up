FROM php:8.2-fpm-alpine

# Installation des extensions PHP nécessaires
RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    && docker-php-ext-install pdo pdo_mysql intl opcache

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Configuration PHP-FPM
RUN echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/www.conf
