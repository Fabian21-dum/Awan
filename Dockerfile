FROM php:8.2-apache

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Rewrite
RUN a2enmod rewrite

# Copy app & config
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Port
ENV PORT 8080
EXPOSE 8080

# Start Apache in foreground
CMD ["apache2-foreground"]
