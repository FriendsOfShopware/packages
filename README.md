# Shopware Composer Repository

If you want to contribute, please check before the open issues

## Installation

### Normal

* Clone repository
* composer install
* php generate_ssl.php
* php bin/console server:run or default nginx / apache setup
* npm install
* npm run watch
* Should be running

### Docker

* docker-compose up -d
* docker-compose exec web sh
    * php generate_ssl.php
    * ./bin/console database:migrate
* Running at localhost

## Self-Hosting

* The plugin informations are currently aggregated using a private endpoint of the Shopware API only accessible for shopware employees
* You can use following site to get daily dumps of the database to host it itself. https://packages-dump.friendsofshopware.com
