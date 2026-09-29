# syntax=docker/dockerfile:1

ARG PS_VERSION_TAG=9

FROM prestashop/prestashop:${PS_VERSION_TAG}

ARG HOST_UID=1000
ARG HOST_GID=1000

ENV COMPOSER_HOME=/var/www/.composer

RUN groupmod --gid "${HOST_GID}" www-data \
    && usermod --uid "${HOST_UID}" --gid "${HOST_GID}" www-data

RUN mkdir "${COMPOSER_HOME}" && chown www-data:www-data "${COMPOSER_HOME}"

RUN chown -R www-data:www-data /tmp/data-ps/prestashop/

COPY --link --from=mlocati/php-extension-installer \
    /usr/bin/install-php-extensions \
    /usr/local/bin/install-php-extensions

RUN install-php-extensions @composer xdebug

COPY --link --chmod=644 --chown=www-data:www-data \
    tmp/defines_custom.inc.php \
    /tmp/defines_custom.inc.php

COPY --link \
    php/99-xdebug.ini \
    /usr/local/etc/php/conf.d/99-xdebug.ini

COPY --link --chmod=755 \
    tmp/pre-install-scripts/10-composer.sh \
    /tmp/pre-install-scripts/

COPY --link --chmod=755 \
    tmp/post-install-scripts/10-install-module.sh \
    /tmp/post-install-scripts/

COPY --link --chmod=755 \
    usr/local/libexec/install-module.php \
    /usr/local/libexec/prestashop-module-installer/install-module.php
