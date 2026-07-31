#!/bin/bash
set -e

php artisan config:clear
php artisan config:cache
php artisan route:cache

php artisan migrate --force

php artisan db:seed --class=AdminSeeder --force || true
php artisan db:seed --class=CvTemplateSeeder --force || true
php artisan db:seed --class=ScholarshipSeeder --force || true

if [ -f /shared-data/scholarships.json ]; then
    echo "Importation des bourses en cours"
    php artisan scholarships:import /shared-data-/scholarships.json || echo "L'import a rencontré des avertissement, mais le serveur continue de  démarrer."
else
    echo "Fichier introuvable"
fi

php artisan config:cache
php artisan route:cache


exec supervisord -c /etc/supervisord.conf