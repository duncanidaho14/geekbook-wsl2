.PHONY: help
# Variables
DOCKER = docker
DOCKER_COMPOSE = docker-compose
EXEC = $(DOCKER) exec -w /var/www/project www_geek_book
EXEC2 = $(DOCKER) exec -w /etc www_geek_book
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer
NPM = $(EXEC) npm
NPX = $(EXEC) npx
SYMFONY_CONSOLE = $(PHP) bin/console

# Colors
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

## Symfony 📖  -----------------------------------------------

## App 👍      -----------------------------------------------

init: ## 💥 Init the project  
	$(MAKE) start
	$(MAKE) composer-install
	$(MAKE) npm-install
	$(MAKE) ssl
	$(MAKE) https
	@$(call GREEN,"The application is available at: https://app1.traefik.me/.")
	
cache-clear: ## Clear cache
	$(SYMFONY_CONSOLE) cache:clear

ssl: ## Install ssl
	$(EXEC2) mkdir -p /ssl/root_ca/{certs,crl,newcerts,private}
	$(EXEC2) mkdir -p /ssl/core_ca/{certs,crl,newcerts,private}
	$(EXEC2) touch /ssl/root_ca/index.txt
	$(EXEC2) touch /ssl/core_ca/index.txt
	$(EXEC2) touch /ssl/root_ca/serial
	$(EXEC2) touch /ssl/core_ca/serial
	$(EXEC2) cd /ssl openssl genrsa -out client.key 4096
	$(EXEC2) cd /ssl openssl req -new -x509 -text -key client.key -out client.cert
	$(EXEC2) chmod -R 600 /ssl/root_ca/private
	$(EXEC2) cd /ssl/core_ca openssl req -newkey rsa:8192 -sha256 -keyout private/core_ca.key -out core_ca.req
	$(EXEC2) cd /ssl openssl ca -rand_serial -extensions CORE_CA -in core_ca.req -out core_ca.pem
	$(EXEC2) cd /ssl openssl x509 -serial -noout -in core_ca.pem | cut -d= -f2 > serial
	$(EXEC2) chmod -R 600 private/
	$(EXEC2) cd /ssl openssl req -newkey rsa:4096 -sha256 -keyout cle-privee.key -out cle-publique.req
	$(EXEC2) cd /ssl openssl ca -name core_ca -in cle-publique.req -out certificat.pem
	$(EXEC2) cd /ssl openssl req -newkey rsa:4096 -sha256 -keyout cle-privee.key -out cle-publique.req
	$(EXEC2) cd /ssl openssl ca -name core_ca -extensions SERVER_SSL -in cle-publique.req -out certificat.pem
	$(EXEC2) cd /ssl/certs/ chmod o+r myca.pem ln -s myca.pem `openssl x509 -hash -noout -in myca.pem`.0

https: ## Install ca
	$(EXEC) symfony server:ca:install

## Test 💯 ------------------------

.PHONY: tests
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

composer-install: ## Install dependencies
	$(COMPOSER) install

composer-update: ## Update dependencies
	$(COMPOSER) update

## —— 🐈 NPM —————————————————————————————————————————————————————————————————

npm-install: ## Install all npm dependencies
	$(NPM) install
	$(NPX) tailwindcss init -p

npm-update: ## Update all npm dependencies
	$(NPM) update

npm-watch: ## Update all npm dependencies
	$(NPM) run watch

##--- 🖥️   Database -------------------------------------------------------------------
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
	$(SYMFONY_CONSOLE) d:f:l --no-interaction

fixtures: ## Alias : database-fixtures-load
	$(MAKE) database-fixtures-load

## —— 🔢  Others ——-------------------------------------------------------------------------------------------
help: ## List of commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


