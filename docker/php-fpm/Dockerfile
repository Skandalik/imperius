FROM php:7.1-fpm

MAINTAINER Hugon Sknadaj <hugon94@gmail.com>

COPY ./sources/sources.list /etc/apt/sources.list

RUN apt-get update -qq && \
    apt-get install -y python && \
    apt-get install -y python3.6 && \
    apt-get install -y \
        tar \
        wget \
        bash \
        g++ \
        make \
        musl-dev \
        libmosquitto-dev \
        libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*

RUN pecl install imagick && docker-php-ext-enable imagick

RUN pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable --ini-name 20-apcu.ini apcu

RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-enable --ini-name 05-opcache.ini opcache

RUN pecl install Mosquitto-alpha \
    && docker-php-ext-enable mosquitto

RUN docker-php-ext-install \
    sockets \
    pdo pdo_mysql \
    gd \
    opcache

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis


RUN pecl install xdebug
COPY ./etc/xdebug.ini /xdebug.ini

COPY ./etc/apcu.ini /usr/local/etc/php/conf.d/apcu.ini
COPY ./etc/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# ensure 'web' user exists
RUN set -x \
	&& adduser web --uid 1000 --disabled-password --gecos ""

COPY ./entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN php-config --extension-dir | xargs ls -la

RUN ls -la /etc/

CMD ["php-fpm"]