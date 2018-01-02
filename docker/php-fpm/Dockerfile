FROM php:7.1-fpm

MAINTAINER Hugon Sknadaj <hugon94@gmail.com>

COPY ./sources/sources.list /etc/apt/sources.list

RUN apt-get update -qq && \
    apt-get install -y python && \
    apt-get install -y python3.6 && \
    apt-get install -y \
        ca-certificates \
        openssl \
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
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini

RUN docker-php-ext-configure opcache --enable-opcache
ADD ./etc/php-opcache.ini /usr/local/etc/php/conf.d/zz-nextcloud-opcache.ini

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


# Xdebug
# here is the installation
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
# here I'm copying the config file we will discuss in the next section
COPY ./etc/xdebug.ini /usr/local/etc/php/conf.d/

# ensure 'web' user exists
RUN set -x \
	&& adduser web --uid 1000 --disabled-password --gecos ""

COPY ./scripts/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN php-config --extension-dir | xargs ls -la

RUN ls -la /etc/

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]