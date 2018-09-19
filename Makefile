local-setup:
	@chmod +x docker/scripts/start.sh
	@cp .env.example .env
	@docker-compose -f docker-compose.yml -f dev.docker-compose.yml up -d --build
	@docker-compose exec --user=nginx app composer install
	@echo "\n\nUnit tests"
	@docker-compose exec --user=nginx app vendor/bin/phpunit --testsuite V1-Unit
	@echo "\n\nIntegration tests"
	@docker-compose exec --user=nginx app vendor/bin/phpunit --testsuite V1-Feature
	@echo "\nInstalling NodeJs dependencies"
	@docker-compose exec --user=nginx app yarn install
	@echo "\nGenerating API documentation"
	@docker-compose exec --user=nginx app yarn v1-docs
	@echo "\n\nVisit http://localhost/api/v1/docs to see documentation"
	@echo "\nApplication is up and running!"
