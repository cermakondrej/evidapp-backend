#!/bin/bash

php bin/console doc:data:drop --if-exists --force
php bin/console doc:data:crea
php bin/console doc:sch:crea
php bin/console doctrine:fixtures:load --no-interaction