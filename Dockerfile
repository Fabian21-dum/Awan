FROM php:8.2-apache

# Install ekstensi PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Enable rewrite
RUN a2enmod rewrite

# Install envsubst
RUN apt-get update && apt-get install -y gettext-base

# Copy semua file
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port default (Cloud Run menggunakan $PORT)
EXPOSE 8080
ENV PORT 8080

# Replace ${PORT} di apache.conf dan jalankan Apache foreground
CMD envsubst '${PORT}' < /var/www/html/apache.conf > /etc/apache2/sites-enabled/000-default.conf && apache2-foreground
