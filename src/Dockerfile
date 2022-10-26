FROM php:8.1-apache

# Install php extensions and its dependencies
RUN docker-php-ext-install pdo pdo_mysql
# Enable mod_rewrite module
RUN a2enmod rewrite

ENV DB_SERVER="host.docker.internal"
