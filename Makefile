SHELL := /bin/bash
DB_USER := root
DB_PASSWORD := root
DB_NAME := messenger_test

up:
	docker-compose up -d
stop:
	docker-compose stop
down:
	docker-compose stop
	docker-compose down
.PHONY: up stop down

db:
	docker exec -it messenger_db_1 mysql -u${DB_USER} -p${DB_PASSWORD} ${DB_NAME}
.PHONY: db