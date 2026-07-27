FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    oniguruma-dev \
    nginx \
    supervisor

RUN docker-php-ext-install pdo pdo_pgsql zip mbstring

RUN { \
    echo 'post_max_size = 20M'; \
    echo 'upload_max_filesize = 20M'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY backend/ .

RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache storage/logs \
    && chmod -R 775 bootstrap/cache storage \
    && composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]