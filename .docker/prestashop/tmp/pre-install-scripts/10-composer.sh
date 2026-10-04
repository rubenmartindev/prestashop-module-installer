#!/bin/sh
set -eu

runuser -u www-data -- composer install \
    --working-dir=/var/www/html/modules/modulefixture \
    --no-interaction \
    --no-progress \
    --prefer-dist
