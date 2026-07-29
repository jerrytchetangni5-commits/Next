#!/bin/bash
set -e

echo "Attente du fichier scholarships.json..."
while [ ! -f /shared-data/scholarships.json ]; do
    sleep 2
done
echo "Fichier trouvé, poursuite du démarrage."

php artisan config:clear
php artisan config:cache
php artisan route:cache

php artisan migrate --force

php artisan db:seed --class=AdminSeeder --force
php artisan db:seed --class=CvTemplateSeeder --force

php artisan scholarships:import /shared-data/scholarships.json

exec supervisord -c /etc/supervisord.conf