# Composer

FROM composer:2 as vendor

WORKDIR /app
COPY composer.* /app/
RUN set -ex; \
    COMPOSER_MEMORY_LIMIT=-1 composer install \
    --ignore-platform-reqs \
    --no-dev \
    --no-scripts \
    --no-suggest \
    --no-interaction \
    --prefer-dist \
    --no-autoloader

COPY src /app/src

RUN set -ex; \
    composer dump-autoload \
    --no-dev \
    --optimize \
    --classmap-authoritative

# Webpack Encore

FROM node:16-alpine as npm
WORKDIR /app
COPY package.json package-lock.json /app/
COPY --from=vendor --chown=1000:1000 /app/vendor /app/vendor
RUN npm install
RUN mkdir -p public/build
COPY assets /app/assets
COPY config /app/config
COPY templates /app/templates
COPY webpack.config.js /app/
RUN npm run build

FROM php:8.0-fpm-alpine

ARG GIT_TAG=unspecified
ENV APP_ENV=prod \
    REDIS_URL=redis://redis:6379 \
    VERSION=$GIT_TAG \
    TZ=Europe/Berlin \
    FPM_PM=dynamic \
    FPM_PM_MAX_CHILDREN=10 \
    FPM_PM_START_SERVERS=2 \
    FPM_PM_MIN_SPARE_SERVERS=1 \
    FPM_PM_MAX_SPARE_SERVERS=3 \
    PHP_MAX_UPLOAD_SIZE=128m \
    PHP_MAX_EXECUTION_TIME=60 \
    PHP_MEMORY_LIMIT=512m

COPY --from=ghcr.io/shyim/supervisord /usr/local/bin/supervisord /usr/bin/supervisord
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN apk add --no-cache \
      nginx \
      shadow && \
    install-php-extensions bcmath intl mysqli pdo_mysql sockets bz2 gmp soap zip gmp redis opcache && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log && \
    rm -rf /var/lib/nginx/tmp && \
    ln -sf /tmp /var/lib/nginx/tmp && \
    mkdir -p /var/tmp/nginx/ || true && \
    chown -R www-data:www-data /var/lib/nginx /var/tmp/nginx/ && \
    chmod 777 -R /var/tmp/nginx/ && \
    rm -rf /tmp/* && \
    chown -R www-data:www-data /var/www && \
    usermod -u 1000 www-data && \
    chown -R 1000 /var/www/html

COPY config/docker/ /

ENTRYPOINT ["/entrypoint.sh"]
STOPSIGNAL SIGKILL

COPY --chown=1000:1000 . /var/www/html
COPY --from=vendor --chown=1000:1000 /app/vendor /var/www/html/vendor
COPY --from=npm --chown=1000:1000 /app/public /var/www/html/public

RUN php bin/console cache:clear && \
    echo "opcache.preload=/var/www/html/var/cache/prod/App_KernelProdContainer.preload.php" > /usr/local/etc/php/conf.d/preload.ini && \
    echo "opcache.preload_user=www-data" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "opcache.jit_buffer_size=100M" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "realpath_cache_ttl=600" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "display_errors=0" > /usr/local/etc/php/conf.d/prod.ini
