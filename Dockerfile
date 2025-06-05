FROM php:8.1-apache

# install mysql client to allow initialization script to run SQL
RUN apt-get update \
    && apt-get install -y default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# enable mysqli extension for PHP
RUN docker-php-ext-install mysqli

# Copy website source
COPY . /var/www/html/

COPY docker-entrypoint.sh /usr/local/bin/

# Ensure guestbook data directory is writable
RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data \
    && chmod -R 777 /var/www/html/data \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]
