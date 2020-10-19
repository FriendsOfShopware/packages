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

FROM node:alpine as npm
WORKDIR /app
COPY package.json package-lock.json /app/
RUN npm install
RUN mkdir -p public/build
COPY assets /app/assets
COPY config /app/config
COPY templates /app/templates
COPY webpack.config.js /app/
RUN npm run build

FROM ghcr.io/shyim/shopware-docker/6/nginx:php74

ARG GIT_TAG=unspecified
ENV APP_ENV=prod REDIS_URL=redis://redis:6379 VERSION=$GIT_TAG FPM_PM_MAX_CHILDREN=10 PHP_MAX_EXECUTION_TIME=60

COPY config/docker/www.conf /etc/nginx/sites-enabled/
COPY --chown=1000:1000 . /var/www/html
COPY --from=vendor --chown=1000:1000 /app/vendor /var/www/html/vendor
COPY --from=npm --chown=1000:1000 /app/public /var/www/html/public

RUN php bin/console cache:clear && \
    echo "opcache.preload=/var/www/html/var/cache/prod/App_KernelProdContainer.preload.php" > /usr/local/etc/php/conf.d/preload.ini && \
    echo "opcache.preload_user=www-data" >> /usr/local/etc/php/conf.d/preload.ini && \
    echo "display_errors=0" > /usr/local/etc/php/conf.d/prod.ini
