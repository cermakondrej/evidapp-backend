MIN_MAKE_VERSION := 3.82

ifneq ($(MIN_MAKE_VERSION),$(firstword $(sort $(MAKE_VERSION) $(MIN_MAKE_VERSION))))
$(error GNU Make $(MIN_MAKE_VERSION) or higher required)
endif

.DEFAULT_GOAL:=help

##@ Development
.PHONY: run rund down cli install

run: ## Run application
	docker-compose up

rund: ## Run application in detached mode
	docker-compose up -d

down: ## Kill and remove application containers
	docker-compose down

cli: ## Go inside docker app
	docker exec -it evidapp bash

install: ## Install application dependencies
	docker exec evidapp composer install

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
