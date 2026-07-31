.PHONY: help install up down restart shell test lint stan pint pest fresh migrate seed assets

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Instala o projeto do zero (deps + chave + migrações)
	docker compose exec laravel.test composer install
	docker compose exec laravel.test php artisan key:generate
	docker compose exec laravel.test php artisan migrate --seed

up: ## Sobe os containers
	docker compose up -d

down: ## Derruba os containers
	docker compose down

restart: ## Reinicia containers
	docker compose restart

shell: ## Acessa o container Laravel
	docker compose exec laravel.test bash

migrate: ## Roda migrations
	docker compose exec laravel.test php artisan migrate

seed: ## Roda seeders
	docker compose exec laravel.test php artisan db:seed

fresh: ## Reset completo do banco
	docker compose exec laravel.test php artisan migrate:fresh --seed

test: ## Roda testes (Pest)
	docker compose exec laravel.test php artisan test

pint: ## Roda Pint (lint PSR-12)
	docker compose exec laravel.test vendor/bin/pint

stan: ## Roda Larastan (análise estática)
	docker compose exec laravel.test vendor/bin/phpstan analyse --memory-limit=1G

lint: pint stan ## Lint completo

assets: ## Build de frontend
	docker compose exec laravel.test npm run build
