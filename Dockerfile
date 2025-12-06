FROM php:8.2-apache

WORKDIR /var/www

RUN apt-get update \
 && apt-get install -y libpq-dev unzip git libyaml-dev \
 && docker-php-ext-install pdo pdo_pgsql pgsql \
 && pecl install yaml \
 && docker-php-ext-enable yaml \
 && a2enmod rewrite

COPY public /var/www/html
COPY . /var/www/

RUN chown -R www-data:www-data /var/www \
 && chmod -R 755 /var/www

EXPOSE 80
