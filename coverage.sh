#!/usr/bin/env bash

docker run -v $(pwd):/var/www/html code_coverage ./vendor/bin/phpunit --coverage-text
