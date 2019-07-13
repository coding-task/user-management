#!/bin/bash

echo "Running Composer Install"

# Run composer
composer self-update
composer install

cp .env.example .env

php artisan migrate
php artisan db:seed
php artisan apidoc:generate

# Run supervisord
/usr/bin/supervisord -n -c /etc/supervisord.conf
