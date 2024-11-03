COMPOSE := docker compose
PHP := ${COMPOSE} run app php

up:
	${COMPOSE} up --build -d

init: up migrate

install:
	${COMPOSE} run composer install

test:
	${PHP} bin/console doctrine:migrations:migrate --env=test --allow-no-migration --no-interaction
#${PHP} bin/console doctrine:fixtures:load  --env=test
	${PHP} bin/phpunit

migration:
	${PHP} bin/console make:migration

migrate:
	${PHP} bin/console doctrine:migration:migrate --allow-no-migration --no-interaction