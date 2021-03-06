install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 ./app/Http/Controllers/UrlController.php

start:
	php artisan serve

setup:
	cp -n .env.example .env || true
	cp -n .env.example .env.testing || true
	php artisan key:gen --ansi
	php artisan key:generate --env=testing
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm install

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku

lint-fix:
	composer phpcbf

compose:
	docker-compose up

compose-test:
	docker-compose run web make test

compose-bash:
	docker-compose run web bash

compose-setup: compose-build
	docker-compose run web make setup

compose-build:
	docker-compose build

compose-db:
	docker-compose exec db psql -U postgres

compose-down:
	docker-compose down -v