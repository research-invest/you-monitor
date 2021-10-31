init: up monitor-init
up:
	docker-compose up -d
monitor-init:
	docker-compose run --rm monitor-php composer install
	migrate
	docker-compose run --rm monitor-php php artisan db:seed
migrate:
	docker-compose run --rm monitor-php php artisan migrate --force
