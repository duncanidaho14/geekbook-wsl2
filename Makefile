.PHONY: help
# Variables
DOCKER = docker
DOCKER_COMPOSE = docker-compose
EXEC = $(DOCKER) exec -it -w /var/www/html/project www_geekbook_app
EXEC2 = $(DOCKER) exec -it -w /etc/ssl/traefik www_geekbook_app
fixer = $(DOCKER) exec -w /var/www/html/project/tools/php-cs-fixer www_geekbook_app php ./vendor/bin/php-cs-fixer
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer
NPM = $(EXEC) npm
NPX = $(EXEC) npx
SYMFONY_CONSOLE = $(PHP) bin/console

# Colors
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

#	$(EXEC) curl -H Host:whoami.docker.localhost http://127.0.0.1

## Symfony 📖  -----------------------------------------------

## App 👍      -----------------------------------------------

init: ## 💥 Init the project  
	$(MAKE) start
	$(MAKE) composer-install
	$(MAKE) npm-install
	$(MAKE) https
	$(MAKE) cs-fixer
	@$(call GREEN,"The application is available at: https://gkbook.traefik.me/.")
	
cache-clear: ## Clear cache
	$(SYMFONY_CONSOLE) cache:clear

cs-fixer: ## Install PHP CS FIXER
	$(EXEC) mkdir -p tools/php-cs-fixer
	$(COMPOSER) require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer
	$(MAKE) php-cs

https: ## Install ca
	$(EXEC) symfony server:ca:install
	$(EXEC) mkcert -install
	
	
## Test 💯 ------------------------

.PHONY: tests

php-cs: ## php cs fixer
	$(fixer) fix ./../../src --dry-run

tests: ## Run all tests
	$(PHP) bin/phpunit --testdox tests/Unit/
	$(PHP) bin/phpunit --testdox tests/Func/
	$(PHP) bin/phpunit --testdox tests/E2E/

database-init-test: ## Init database for test
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists --env=test
	$(SYMFONY_CONSOLE) d:d:c --env=test
	$(SYMFONY_CONSOLE) d:m:m --no-interaction --env=test
	$(SYMFONY_CONSOLE) d:f:l --no-interaction --env=test

unit-test: ## Run unit tests
	$(MAKE) database-init-test
	$(PHP) bin/phpunit --testdox tests/Unit/

functional-test: ## Run functional tests
	$(MAKE) database-init-test
	$(PHP) bin/phpunit --testdox tests/Func/

# PANTHER_NO_HEADLESS=1 ./bin/phpunit --filter LikeTest --debug to debug with Chrome
e2e-test: ## Run E2E tests
	$(MAKE) database-init-test
	$(PHP) bin/phpunit --testdox tests/E2E/

## Docker 💁   -----------------------------------------------------------------------
.PHONY: start
start: ## Start app
	$(MAKE) docker-start

docker-start: 
	$(DOCKER_COMPOSE) up --build -d

stop: ## Stop app
	$(MAKE) docker-stop
docker-stop: 
	$(DOCKER_COMPOSE) stop
	@$(call RED,"The containers are now stopped.")

## Composer 🎵   ----------------------------------------------------------------------
.PHONY: composer
composer-install: ## Install dependencies
	$(COMPOSER) install

composer-update: ## Update dependencies
	$(COMPOSER) update

## —— 🐈 NPM —————————————————————————————————————————————————————————————————
.PHONY: npm
npm-install: ## Install all npm dependencies
	$(NPM) install
	$(NPX) tailwindcss init -p

npm-update: ## Update all npm dependencies
	$(NPM) update

npm-watch: ## Watch files
	$(NPM) run watch

npm-build: ## Build files
	$(NPM) run build

##--- 🖥️   Database -------------------------------------------------------------------
.PHONY: db
database-init: ## Init database
	$(MAKE) database-drop
	$(MAKE) database-create
	$(MAKE) database-migrate
	$(MAKE) database-fixtures-load

database-drop: ## Create database
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists

database-create: ## Create database
	$(SYMFONY_CONSOLE) d:d:c --if-not-exists

database-remove: ## Drop database
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists

database-migration: ## Make migration
	$(SYMFONY_CONSOLE) make:migration

migration: ## Alias : database-migration
	$(MAKE) database-migration

database-migrate: ## Migrate migrations
	$(SYMFONY_CONSOLE) d:m:m --no-interaction

migrate: ## Alias : database-migrate
	$(MAKE) database-migrate

database-fixtures-load: ## Load fixtures
	$(SYMFONY_CONSOLE) d:f:l -n --purge-with-truncate --no-interaction

fixtures: ## Alias : database-fixtures-load
	$(MAKE) database-fixtures-load -n --purge-with-truncate

##--- 🀄   Messenger consume ---------------------------------------------------------------------------------
.PHONY: messenger
messenger: ## Consuming message
	$(PHP) bin/console messenger:consume async -vvv

##---	 DockerHub build ------------------------------------------------------------------------------------
.PHONY: prod
hub-build: ## Docker build hub
	docker image build -f ./docker/Dockerfile -t duncanidaho/geekbook:latest .

hub-push:  ## Docker push hub
	docker push duncanidaho/geekbook:latest

## —— 🔢  Others ——-------------------------------------------------------------------------------------------
help: ## List of commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


