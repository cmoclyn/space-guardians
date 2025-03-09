COMPOSE := docker compose
COMPOSER := ${COMPOSE} run composer
PHP := ${COMPOSE} run app php

up:
	${COMPOSE} up --build -d

bash:
	${COMPOSE} exec app bash

init: up migrate

install:
	${COMPOSER} install

require:
	${COMPOSER} require

test:
	${PHP} bin/console doctrine:migrations:migrate --env=test --allow-no-migration --no-interaction
#${PHP} bin/console doctrine:fixtures:load  --env=test
	${PHP} bin/phpunit

migration:
	${PHP} bin/console make:migration

migrate:
	${PHP} bin/console doctrine:migration:migrate --allow-no-migration --no-interaction