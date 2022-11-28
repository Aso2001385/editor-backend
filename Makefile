# for Windows
UNAME=$(shell uname)
PREFIX=winpty
ifeq (${UNAME}, Darwin)
	PREFIX=
endif

default:
	@echo Please specify target name!
up:
	docker compose up -d
build:
	docker compose build --no-cache --force-rm
init:
	cp .env.example .env
	cp ./swagger/docs/swagger.yml.example ./swagger/docs/swagger.yml
	mkdir -p ./infrastructure/docker/php/bash/psysh
	touch ./infrastructure/docker/php/bash/.bash_history
	@make build
	@make up
	${PREFIX} docker compose exec app chmod -R 777 storage bootstrap/cache
	@make composer-install
	${PREFIX} docker compose exec app cp .env.example .env
	${PREFIX} docker compose exec app php artisan key:generate
	@make fresh-seed
remake:
	@make destroy
	@make init
stop:
	docker compose stop
down:
	docker compose down
restart:
	@make down
	@make up
rebuild:
	@make down
	@make build
	@make up
destroy:
	docker compose down --rmi all --volumes
destroy-volumes:
	docker compose down --volumes
ps:
	docker compose ps
logs:
	${PREFIX} docker compose logs --follow
app:
	${PREFIX} docker compose exec app bash

# laravel short commands
migrate:
	${PREFIX} docker compose exec app php artisan migrate
fresh:
	${PREFIX} docker compose exec app php artisan migrate:fresh
fresh-seed:
	${PREFIX} docker compose exec app php artisan migrate:fresh --seed
composer-install:
	${PREFIX} docker compose exec app composer install
dump-autoload:
	${PREFIX} docker compose exec app composer dump-autoload
test:
	${PREFIX} docker compose exec app php artisan test
# swagger
swagger:
	${PREFIX} docker compose exec swagger-watch ./node_modules/.bin/swagger-merger -i ../src/index.yml
	mv -f swagger/src/swagger.yml  swagger/docs/swagger.yml