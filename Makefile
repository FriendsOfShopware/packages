SHELL := bash

up:
	docker-compose up -d

stop:
	docker-compose stop

shell:
	docker-compose exec cli bash

phpstan:
	docker-compose exec cli php -d memory_limit=-1 ./vendor/bin/phpstan analyse -c .phpstan.neon

index:
	docker-compose exec cli bin/console search:package:index

cache:
	rm -rf var/cache/*
	docker-compose exec redis redis-cli flushdb
