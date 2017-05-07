FROM php:7.1-apache

EXPOSE 80

COPY . /var/www/

RUN usermod -u 1000 www-data; \
    a2enmod rewrite; \
    chown -R www-data:www-data /var/www/var/
