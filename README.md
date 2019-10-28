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

### Nixops-Way

* Modifiy `dev-ops/server.nix`
* `nixops create -d your-deployment dev-ops/server.nix`
* `nixops deploy -d your-deployment`
* Its running


