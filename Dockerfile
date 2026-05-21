FROM php:8.3-fpm-alpine AS builder

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql intl bcmath zip fileinfo @composer-2

RUN apk add --no-cache nodejs npm

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

RUN npm install --ignore-scripts && npm run build && rm -rf node_modules

FROM php:8.3-fpm-alpine AS production

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql intl bcmath zip fileinfo

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

COPY --from=builder /var/www .

RUN rm -rf public/storage && php artisan storage:link

RUN chown -R www-data:www-data storage bootstrap/cache
