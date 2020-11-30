FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libmcrypt-dev \
    && pecl install xdebug-2.9.8 \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt xdebug \
    && docker-php-ext-install -j$(nproc) iconv mbstring bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
