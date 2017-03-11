#!/usr/bin/env bash

docker-compose run web composer install
docker-compose run web vendor/bin/phpunit --coverage-html=./build
