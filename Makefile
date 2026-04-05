# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test phpstan phpcsfixer phpunit prepare-test-db

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

up-debug: ## Start the docker hub in detached mode (no logs) with xdebug
	@XDEBUG_MODE=develop,debug $(DOCKER_COMP) up --detach --wait

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the FrankenPHP container
	@$(PHP_CONT) sh

bash: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

test: prepare-test-db ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)

composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

phpunit: ## Run phpunit, pass the parameter "c=" to add options, example: make phpunit c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(PHP_CONT) php bin/phpunit $(c)

phpstan: ## Run phpstan static analysis
	@$(PHP_CONT) php vendor/bin/phpstan analyse --memory-limit=512M

phpcsfixer: ## Run PHP-CS-Fixer, pass the parameter "c=" to add options, example: make phpcsfixer c="--dry-run --diff"
	@$(eval c ?=)
	@$(PHP_CONT) php vendor/bin/php-cs-fixer fix $(c)

prepare-test-db: ## Drop and recreate test database with fresh migrations
	@$(DOCKER_COMP) exec database psql -U app -d postgres -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = 'app_test' AND pid <> pg_backend_pid();" || true
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/console doctrine:database:drop --if-exists --force
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/console doctrine:database:create
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/console doctrine:migrations:migrate --no-interaction
