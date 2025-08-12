# Étape 1 : Build
FROM php:8.2-fpm-alpine as builder

# Dépendances système
RUN apk add --no-cache \
    git \
    unzip \
    curl \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    zlib-dev \
    g++ \
    make \
    autoconf \
    bash

# Extensions PHP
RUN docker-php-ext-install intl pdo pdo_mysql zip opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

### Étape 2 : Runtime
FROM php:8.2-fpm-alpine

# Extensions système
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    bash

# Extensions PHP
RUN docker-php-ext-install intl pdo pdo_mysql zip opcache

# Copier depuis le build
COPY --from=builder /app /var/www
WORKDIR /var/www

# Droits
RUN chown -R www-data:www-data /var/www

# Port FPM
EXPOSE 9000

CMD ["php-fpm"]