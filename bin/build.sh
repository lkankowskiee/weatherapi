#!/bin/bash

docker-compose up -d
docker-compose exec php-fpm composer install
docker-compose exec php-fpm php bin/console doctrine:schema:create
