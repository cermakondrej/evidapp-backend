MIN_MAKE_VERSION := 3.82

ifneq ($(MIN_MAKE_VERSION),$(firstword $(sort $(MAKE_VERSION) $(MIN_MAKE_VERSION))))
$(error GNU Make $(MIN_MAKE_VERSION) or higher required)
endif

run: ## Generate JSON Schema from API Blueprint(s)
	docker-compose up -d

cli: ## Go inside docker app
	docker exec -it evidapp-backend-php-fpm bash
