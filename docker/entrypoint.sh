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

JSON_FILE="/var/www/scraper/storage/scholarships.json"

if [ -f "$JSON_FILE" ]; then
    echo "Importation des bourses en cours"
    php artisan scholarships:import "$JSON_FILE" \
        || echo "L'import a rencontré des avertissements, mais le serveur continue de démarrer."
else
    echo "Fichier introuvable : $JSON_FILE"
fi

php artisan config:cache
php artisan route:cache


exec supervisord -c /etc/supervisord.conf