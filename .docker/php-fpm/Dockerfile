FROM php:7.3-fpm
MAINTAINER RANDEF

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

# Install selected extensions and other stuff
RUN apt-get update \
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

