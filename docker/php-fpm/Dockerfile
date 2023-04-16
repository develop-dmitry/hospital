FROM php:8.1-fpm

ARG PHP_INI_DIR
ARG WWWGROUP
ARG WWWUSER
ARG APP_MODE

COPY ./conf.d/php.ini $PHP_INI_DIR/conf.d/php.ini

RUN if [ "$APP_MODE" = "develop"]; then \
        COPY ./conf.d/docker-php-ext-xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini; \
    fi

RUN apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
    	libpng-dev \
    	libonig-dev \
    	libzip-dev \
        libcurl4-openssl-dev \
        libpq-dev \
    && pecl install xdebug redis \
    && docker-php-ext-install -j$(nproc) iconv mbstring zip curl pgsql pdo_pgsql \
    && docker-php-ext-enable xdebug redis \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -sLS https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && mkdir /var/www/.npm \
    && chown ${WWWUSER}:${WWWGROUP} /var/www/.npm \
    && usermod -u ${WWWUSER} www-data && groupmod -g ${WWWGROUP} www-data

USER "${WWWUSER}:${WWWGROUP}"

EXPOSE 9000

WORKDIR /var/www/html

CMD ["php-fpm"]
