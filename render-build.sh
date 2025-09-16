#!/usr/bin/env bash
set -o errexit

# Mettre à jour PHP à la bonne version
sudo apt-get update
sudo apt-get install -y php8.2-cli php8.2-common php8.2-mysql php8.2-xml php8.2-mbstring unzip curl

# Installer Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances Symfony
composer install --no-dev --optimize-autoloader

# Nettoyer le cache Symfony
php bin/console cache:clear --env=prod
