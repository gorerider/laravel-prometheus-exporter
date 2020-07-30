FROM php:7.4-cli

RUN mkdir /app
WORKDIR /app

RUN apt-get update && apt-get install -y zip unzip libzstd-dev bash

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer global require "hirak/prestissimo:^0.3"

# install opcache
RUN docker-php-ext-install opcache \
    && echo 'opcache.enable_cli=1' > /usr/local/etc/php/conf.d/enable_opcache_cli.ini

# install APCu and enable for CLI
RUN yes '' | pecl install apcu \
    && docker-php-ext-enable apcu \
    && echo 'apc.enable_cli=1' > /usr/local/etc/php/conf.d/enable-apcu-cli.ini

# install redis extension with all comporession support
RUN pecl install igbinary zstd \
    && yes 'no' | pecl install lzf \
    && yes 'yes' | pecl install redis \
    && docker-php-ext-enable igbinary zstd lzf redis
