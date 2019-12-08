MIN_MAKE_VERSION := 3.82

ifneq ($(MIN_MAKE_VERSION),$(firstword $(sort $(MAKE_VERSION) $(MIN_MAKE_VERSION))))
$(error GNU Make $(MIN_MAKE_VERSION) or higher required)
endif

.DEFAULT_GOAL:=help

##@ Development
.PHONY: run cli install

run: ## Run application
	docker-compose up -d

cli: ## Go inside docker app
	docker exec -it evidapp bash

install: ## Install application dependencies
	docker exec evidapp composer install

##@ Standards and Analysis
.PHONY: run-phpcs run-phpcs-fix run-phpstan run-phpmd

run-phpcs: ## Run PHP Coding Standards Checker
	docker exec evidapp composer cs

run-phpcs-fix: ## Run PHP Coding Standards Fixer
	docker exec evidapp composer csf

run-phpstan: ## Run PHPStan
	docker exec evidapp composer phpstan

run-phpmd: ## Run PHP Mess Detector
	docker exec evidapp composer md

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-25s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
