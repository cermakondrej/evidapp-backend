MIN_MAKE_VERSION := 3.82

ifneq ($(MIN_MAKE_VERSION),$(firstword $(sort $(MAKE_VERSION) $(MIN_MAKE_VERSION))))
$(error GNU Make $(MIN_MAKE_VERSION) or higher required)
endif

run: ## Generate JSON Schema from API Blueprint(s)
	docker-compose up -d

cli: ## Go inside docker app
	docker exec -it evidapp-backend-php-fpm bash

run-phpcs: ## Run PHP Coding Standards Checker
	docker exec evidapp-backend-php-fpm composer cs

run-phpcs-fix: ## Run PHP Coding Standards Fixer
	docker exec evidapp-backend-php-fpm composer csf

run-phpstan: ## Run PHPStan
	docker exec evidapp-backend-php-fpm composer phpstan

