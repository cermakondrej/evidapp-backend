MIN_MAKE_VERSION := 3.82

env=dev
compose=docker-compose -f docker-compose.yml -f docker-compose.$(env).yml

export compose
export env

ifneq ($(MIN_MAKE_VERSION),$(firstword $(sort $(MAKE_VERSION) $(MIN_MAKE_VERSION))))
$(error GNU Make $(MIN_MAKE_VERSION) or higher required)
endif

.DEFAULT_GOAL:=help

##@ Development
.PHONY: start stop rebuild erase build artifact composer-update up db

.PHONY: start
start: erase build up db ## Clean current environment, recreate dependencies and spin up again

.PHONY: stop
stop: ## Stop environment
	$(compose) stop

.PHONY: rebuild
rebuild: start ## Same as start

.PHONY: erase
erase: ## Stop and delete containers, clean volumes.
		$(compose) stop
		docker-compose rm -v -f

.PHONY: build
build: ## Build environment and initialize composer and project dependencies
	$(compose) build
	$(compose) run --rm php sh -lc 'COMPOSER_MEMORY_LIMIT=-1 composer install'

.PHONY: artifact
artifact: ## Build production artifact
	docker-compose -f docker-compose.prod.yml build

.PHONY: composer-update
composer-update: ## Update project dependencies
	$(compose) run --rm php sh -lc 'xoff;COMPOSER_MEMORY_LIMIT=-1 composer update'

.PHONY: up
up: ## Spin up environment
	$(compose) up -d

.PHONY: db
db: ## Recreate database
	$(compose) exec -T php sh -lc './bin/console d:d:d --force'
	$(compose) exec -T php sh -lc './bin/console d:d:c'
	$(compose) exec -T php sh -lc './bin/console d:m:m -n'

.PHONY: bash
bash: ## Go Inside
	$(compose) exec php bash

##@ Testing
.PHONY: test

test: ## Run all test suite
	docker exec evidapp composer test

##@ Standards and Analysis
.PHONY: phpcs phpcsf phpstan phpmd

phpcs: ## Run PHP Coding Standards Checker
	docker exec evidapp composer cs

phpcs-fix: ## Run PHP Coding Standards Fixer
	docker exec evidapp composer csf

phpstan: ## Run PHPStan
	docker exec evidapp composer phpstan

phpmd: ## Run PHP Mess Detector
	docker exec evidapp composer md

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-25s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
