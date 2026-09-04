# syntax=docker/dockerfile:1

ARG PS_VERSION_TAG=9

FROM prestashop/prestashop:${PS_VERSION_TAG}

ARG HOST_UID=1000
ARG HOST_GID=1000

COPY --link --from=mlocati/php-extension-installer:2 \
    /usr/bin/install-php-extensions \
    /usr/local/bin/

RUN install-php-extensions @composer xdebug

RUN groupmod --gid "${HOST_GID}" www-data \
    && usermod --uid "${HOST_UID}" --gid "${HOST_GID}" www-data \
    && install -d \
        --owner=www-data \
        --group=www-data \
        --mode=755 \
        /var/www/.composer

COPY --link \
    .docker/prestashop/php/xdebug.ini \
    /usr/local/etc/php/conf.d/zz-xdebug.ini

COPY --link --chmod=755 \
    .docker/prestashop/tmp/pre-install-scripts/10-composer.sh \
    /tmp/pre-install-scripts/

COPY --link --chmod=755 \
    .docker/prestashop/tmp/pre-install-scripts/20-permissions.sh \
    /tmp/pre-install-scripts/

COPY --link --chmod=755 \
    .docker/prestashop/tmp/post-install-scripts/10-install-module.sh \
    /tmp/post-install-scripts/

COPY --link --chmod=755 \
    .docker/prestashop/usr/local/libexec/install-module.php \
    /usr/local/libexec/prestashop-module-installer/install-module.php
