FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer:latest --link /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

RUN set -eux; \
    install-php-extensions \
		apcu \
		intl \
		opcache \
		zip \
    ;

RUN apk update && \
    apk add --no-cache jq

RUN apk add libpng-dev libjpeg-turbo-dev freetype-dev
RUN apk update
RUN docker-php-ext-install gd

# Install nodejs npm
RUN apk update && apk add --no-cache \
    nodejs \
    npm

# Instalar Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

ARG CACHEBUST

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]
