#!/bin/sh
set -eu

runuser -u www-data -- \
    php /usr/local/libexec/prestashop-module-installer/install-module.php
