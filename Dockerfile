# Set the base image for subsequent instructions
FROM php:7.4-fpm

COPY ./src/composer.lock ./src/composer.json /var/www/html/

WORKDIR /var/www/html

# Update packages

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs netcat libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev nodejs git libzip-dev \
    && apt-get clean

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

RUN npm install -g cross-env

# Add user for Laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy app folder
COPY ./src /var/www/html

# Copy permisions
COPY --chown=www:www . /var/www/html
RUN chown -R www-data:www-data /var/www/html

USER www

CMD ["bash", "./docker-entrypoint.sh"]

