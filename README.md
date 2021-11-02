# Discontinued use Shopware Packages instead with the Shopware Account https://shyim.me/blog/deprecation-frosh-packages/

# Shopware Composer Repository

If you want to contribute, please check before the open issues

## Installation

### Requirements

* PHP 7.4
* MySQL 8

### Normal

* Clone repository
* composer install
* php generate_ssl.php
* default nginx / apache setup
* npm install
* npm run build
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
