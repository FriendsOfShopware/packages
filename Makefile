SHELL := bash

up:
	docker-compose up -d

stop:
	docker-compose stop

shell:
	docker-compose exec cli bash

phpstan:
	docker-compose exec cli ./vendor/bin/phpstan analyse -c .phpstan.neon

