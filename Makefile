COMPOSE := docker compose

up:
	${COMPOSE} up --build -d

install:
	${COMPOSE} run composer install