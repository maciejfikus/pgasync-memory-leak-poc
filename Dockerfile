# syntax=docker/dockerfile:1
FROM php:8.1-alpine

WORKDIR /var/www

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# recommended: install optional extensions ext-ev and ext-sockets
RUN apk --no-cache add ${PHPIZE_DEPS} libev \
    && pecl install ev \
    && docker-php-ext-enable ev \
    && docker-php-ext-install sockets \
    && apk del ${PHPIZE_DEPS} \
    && echo "memory_limit = -1" >> "$PHP_INI_DIR/conf.d/acme.ini"

COPY . .

RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

ENV X_LISTEN 0.0.0.0:8080
EXPOSE 8080

USER nobody:nobody
ENTRYPOINT ["php", "public/index.php"]
