#!/usr/bin/env bash
composer install --no-dev -o
composer dump-autoload
#php artisan opcache:status
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan queue:restart
#php artisan opcache:clear

#npm install
#npm run prod

echo "REMEMBER TO DUMP OPCACHE!!!"
