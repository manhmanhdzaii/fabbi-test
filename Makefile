# docker compose
up:
	docker compose up -d

down:
	docker compose down

app-start:
	cp .env.example .env
	docker compose up -d

app-build:
	docker compose up -d --build

app-restart:
	docker compose down
	docker compose up -d --build

app-connect:
	docker exec -it fabbi-test-app bash

db-connect:
	docker exec -it fabbi-test-app bash

app-install:
	cp .env.example .env
	make app-setup
	make app-clear

app-setup:
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan jwt:secret
	docker compose exec app php artisan migrate
	docker compose exec app php artisan db:seed
	docker compose exec app php artisan storage:link
	docker compose exec app php artisan cache:clear

app-init:
	docker compose exec app php artisan migrate
	docker compose exec app php artisan db:seed

init-db:
	docker exec -it fabbi-test-app php artisan migrate:fresh

autoload:
	composer dump-autoload

route:
	php artisan route:list

app-clear:
	php artisan config:cache
	php artisan config:clear
	php artisan route:clear
	php artisan optimize:clear

