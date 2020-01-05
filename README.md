# Shopware Composer Repository

**WIP**

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
