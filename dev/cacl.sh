#!/bin/bash

if [ "$1" == "prod" ]; then
	rm -R -f var/cache/* var/log/*
	php bin/console cache:clear --env=prod --no-debug
	chmod -R 777 var/cache var/log
elif [ "$1" == "dev" ]; then
    php bin/console cache:clear
    chmod -R 777 var/cache var/log
else
	echo "parameter expected: dev | prod"
fi
