COMPOSE := docker compose

up:
	${COMPOSE} up --build -d

install:
	${COMPOSE} run composer install

test:
	${COMPOSE} run app php bin/phpunit

migration:
	${COMPOSE} run app php bin/console make:migration

migrate:
	${COMPOSE} run app php bin/console doctrine:migration:migrate