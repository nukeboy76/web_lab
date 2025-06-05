FROM php:8.1-apache

# Copy website source
COPY . /var/www/html/

# Ensure guestbook data directory is writable
RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data \
    && chmod -R 777 /var/www/html/data

EXPOSE 80
CMD ["apache2-foreground"]
