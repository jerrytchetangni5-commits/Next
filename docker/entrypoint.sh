#!/bin/bash
set -e

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan migrate --force

exec supervisord -c /etc/supervisord.conf