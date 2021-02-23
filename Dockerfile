FROM php:7.4-fpm-alpine

# https://wiki.alpinelinux.org/wiki/Setting_the_timezone
ARG TZ='UTC'


RUN echo "${TZ}" && apk --update add tzdata && \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone && \
    apk del tzdata

RUN apk add  --update --no-cache \
    icu-libs \
    libintl \
    bash \
    curl \
    libcurl \
    wget \
    git \
    zlib-dev \
    libzip-dev \
    zip \
    icu-dev \
    gettext-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libxslt-dev \
    autoconf \
    gcc \
    build-base

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#install extentions
RUN php -m && \
    docker-php-ext-configure bcmath --enable-bcmath && \
    docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ && \
    docker-php-ext-configure gettext && \
    docker-php-ext-configure intl --enable-intl && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-configure soap && \
    docker-php-ext-install exif \
        opcache \
        xsl \
        bcmath \
        gd \
        gettext \
        intl \
        opcache \
        pcntl \
        soap \
        zip \
        calendar

# install Xdebug
ARG XDEBUG=3.0.0
RUN if [ ${XDEBUG} != false ]; then \
  # Install the xdebug extension
  pecl install xdebug-${XDEBUG}  && \
  docker-php-ext-enable xdebug \
;fi

# Clean
RUN rm -rf /var/cache/apk/* && docker-php-source delete

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

ARG USER=enjoys
ARG UID=1000
RUN adduser --system ${USER} --uid ${UID}

ARG WORKDIR="/var/www"
WORKDIR ${WORKDIR}

USER ${USER}
