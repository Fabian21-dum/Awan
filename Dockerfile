FROM php:8.2-apache

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable rewrite
RUN a2enmod rewrite

# Copy app & config
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Forward env PORT to apache.conf
ENV PORT 8080
EXPOSE 8080

# Replace ${PORT} in apache.conf at runtime and start apache
CMD envsubst '${PORT}' < /etc/apache2/sites-available/000-default.conf > /etc/apache2/sites-enabled/000-default.conf && apache2-foreground
