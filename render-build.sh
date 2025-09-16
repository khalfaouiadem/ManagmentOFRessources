#!/usr/bin/env bash
set -o errexit

# Installer les dépendances Symfony
composer install --no-dev --optimize-autoloader

# Nettoyer le cache Symfony
php bin/console cache:clear --env=prod
