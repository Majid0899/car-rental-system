FROM php:8.2-apache

# Install PHP extensions (optional)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files into container
COPY . /var/www/html/

# Give permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
