FROM alpine:3.6

MAINTAINER Hugon Sknadaj <hugon94@gmail.com>

COPY ./sources/sources.list /etc/apt/sources.list

RUN apk --update add \
        grep \
        php7 \
        php7-bcmath \
        php7-dom \
        php7-ctype \
        php7-curl \
        php7-fileinfo \
        php7-iconv \
        php7-intl \
        php7-json \
        php7-mbstring \
        php7-mcrypt \
        php7-opcache \
        php7-openssl \
        php7-phar \
        php7-posix \
        php7-session \
        php7-zip \
        php7-zlib \
        php7-xml \
        curl \
        tar \
        git \
        zip \
        php7-redis \
        php7-pcntl \
        php7-sockets \
        php7-pdo \
        php7-pdo_mysql \
        php7-gmp \
        php7-imagick \
        php7-xdebug \
        php7-simplexml \
        php7-tokenizer \
        php7-xmlwriter \
        php7-pear \
        php7-dev \
        gcc \
        g++ \
        make \
        mosquitto-dev

RUN pecl install Mosquitto-alpha \
    && echo "extension=mosquitto.so" > /etc/php7/php.ini

# Memory Limit
RUN echo "memory_limit=-1" > /etc/php7/conf.d/memory-limit.ini

RUN echo "xdebug.remote_port=9001" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=192.168.65.1" >> /etc/php7/conf.d/xdebug.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /code

ENTRYPOINT ["php", "bin/console"]
