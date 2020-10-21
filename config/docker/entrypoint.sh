#!/usr/bin/env sh

if [[ $APP_ENV == "dev" ]]; then
    rm /usr/local/etc/php/conf.d/preload.ini
    rm /usr/local/etc/php/conf.d/prod.ini
    rm /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
fi

/usr/bin/supervisord -c /etc/supervisord.conf
