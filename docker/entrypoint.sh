#!/bin/bash
set -e

php artisan config:clear

# attendre PostgreSQL

php artisan migrate --force

# seeders

php artisan config:cache
php artisan route:cache

until pg_isready -h db -p 5432 -U next_user -d next_prod
do
    echo "Waiting for PostgreSQL..."
    sleep 2
done

echo "PostgreSQL is ready."

php artisan migrate --force

php artisan db:seed --class=AdminSeeder --force || true
php artisan db:seed --class=CvTemplateSeeder --force || true
php artisan db:seed --class=ScholarshipSeeder --force || true

if [ -f /shared-data/scholarships.json ]; then
    echo "Importation des bourses en cours"
    php artisan scholarships:import /shared-data/scholarships.json || echo "L'import a rencontré des avertissement, mais le serveur continue de  démarrer."
else
    echo "Fichier introuvable"
fi

php artisan config:cache
php artisan route:cache


exec supervisord -c /etc/supervisord.conf