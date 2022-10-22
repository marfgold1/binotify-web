FROM php:8.0-apache

# Install php extensions and its dependencies
RUN docker-php-ext-install pdo pdo_mysql

ENV DB_SERVER="host.docker.internal"
