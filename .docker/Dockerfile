FROM php:8.3-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      procps \
      git \
      unzip \
      git-core \
      curl \
      build-essential \
      openssl \
      libssl-dev \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      supervisor \
      cron \
      sudo \
      libzip-dev \
      && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
      && docker-php-ext-configure mysqli \
      && docker-php-ext-configure intl \
      && docker-php-ext-install \
      pdo\
      mysqli \
      pdo_mysql \
      sockets \
      intl \
      opcache \
      zip \
      && rm -rf /tmp/* \
      && rm -rf /var/list/apt/* \
      && rm -rf /var/lib/apt/lists/* \
      && apt-get clean

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN pecl install mongodb apcu && docker-php-ext-enable mongodb apcu

COPY ./xdebug/debug.ini "${PHP_INI_DIR}/conf.d"

COPY --from=composer:2.0 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin

RUN install-php-extensions gd pdo_mysql bcmath zip intl opcache curl

RUN composer global require phpunit/phpunit && ln -s /var/www/html/vendor/bin/phpunit /usr/local/bin/phpunit