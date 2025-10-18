.PHONY: help build up down restart logs shell artisan composer test migrate seed fresh optimize clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build Docker containers
	docker-compose build

up: ## Start Docker containers
	docker-compose up -d

down: ## Stop Docker containers
	docker-compose down

restart: ## Restart Docker containers
	docker-compose restart

logs: ## View Docker logs
	docker-compose logs -f

logs-app: ## View application logs
	docker-compose logs -f app

logs-nginx: ## View nginx logs
	docker-compose logs -f nginx

shell: ## Access application container shell
	docker-compose exec app bash

artisan: ## Run artisan command (use: make artisan CMD="migrate")
	docker-compose exec app php artisan $(CMD)

composer: ## Run composer command (use: make composer CMD="install")
	docker-compose exec app composer $(CMD)

test: ## Run tests
	docker-compose exec app php artisan test

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

seed: ## Run database seeders
	docker-compose exec app php artisan db:seed

fresh: ## Fresh migration with seed
	docker-compose exec app php artisan migrate:fresh --seed

optimize: ## Optimize Laravel application
	docker-compose exec app php artisan optimize

clear: ## Clear all caches
	docker-compose exec app php artisan optimize:clear

clean: ## Stop containers and remove volumes
	docker-compose down -v

setup: ## Initial setup (build, start, migrate, seed)
	cp .env.docker .env
	docker-compose build
	docker-compose up -d
	sleep 10
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate --seed
	@echo "Setup complete! Access the app at http://localhost:8080"
	@echo "Login: admin@example.com / password"

rebuild: ## Rebuild and restart containers
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d

status: ## Show container status
	docker-compose ps

supervisor: ## Check supervisor status
	docker-compose exec app supervisorctl status

queue-restart: ## Restart queue workers
	docker-compose exec app supervisorctl restart queue-worker:*

scheduler-restart: ## Restart scheduler
	docker-compose exec app supervisorctl restart scheduler

backup: ## Backup SQLite database
	docker-compose exec app cp /var/www/html/database/database.sqlite /var/www/html/database/database.backup.sqlite
	@echo "Database backed up to database/database.backup.sqlite"

restore: ## Restore SQLite database from backup
	docker-compose exec app cp /var/www/html/database/database.backup.sqlite /var/www/html/database/database.sqlite
	@echo "Database restored from backup"
