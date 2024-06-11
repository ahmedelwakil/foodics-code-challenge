#!/bin/bash

composer install
chown -R www-data:www-data /var/www/html/
chmod 777 /var/www/html/storage/ -R
php artisan migrate:install
php artisan migrate
./vendor/bin/phpunit
php artisan db:seed
#php artisan jwt:secret --force
service supervisor start
#supervisorctl reload
#supervisorctl reread
#supervisorctl update
supervisorctl start 'laravel-queue-worker:*'
apache2-foreground
