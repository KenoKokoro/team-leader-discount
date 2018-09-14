local-setup:
	@chmod +x docker/scripts/start.sh
	@cp .env.example .env
	@docker-compose -f docker-compose.yml -f dev.docker-compose.yml up -d --build
	@docker-compose exec --user=nginx app composer install
	@echo "Application is up and running!"

composer:
	@docker-compose exec --user=nginx app composer $(arg)

artisan:
	@docker-compose exec --user=nginx app php artisan $(arg)
