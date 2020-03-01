FROM php:7.4-fpm
MAINTAINER RANDEF

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

ENV S6_VERSION=1.22.1.0

# Install selected extensions and other stuff
RUN curl -fsSL https://github.com/just-containers/s6-overlay/releases/download/v${S6_VERSION}/s6-overlay-amd64.tar.gz | tar xzf - -C / \
    && apt-get update \
    && apt-get -y --no-install-recommends install zip unzip libzip-dev libpq-dev zlib1g-dev libicu-dev g++ \
    && docker-php-ext-install intl zip bcmath pdo_pgsql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git flow
RUN apt-get update \
    && apt-get install git-flow

ADD etc/ /etc/

ENTRYPOINT ["/init"]
