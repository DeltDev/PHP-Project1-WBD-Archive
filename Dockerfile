FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

RUN a2enmod rewrite

COPY ./php/php.ini /usr/local/etc/php/php.ini

RUN sed -i 's#/var/www/html#/var/www/html/src#' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/src

COPY ./php /var/www/html/

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
