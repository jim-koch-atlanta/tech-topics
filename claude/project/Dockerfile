FROM php:8.2-apache

WORKDIR /var/www/html

# Enable required Apache modules
RUN a2enmod rewrite headers expires

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    imagemagick libmagickwand-dev ghostscript \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install imagick && docker-php-ext-enable imagick

# Copy configuration files
COPY php.ini /usr/local/etc/php/php.ini
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy application code
COPY src/ .

# Create directories and set permissions
RUN mkdir -p uploads/{incoming,transformed} sessions /tmp/{php_sessions,gif_uploads/transformed} && \
    chown -R www-data:www-data . && \
    chmod 755 . && \
    chmod 775 uploads sessions && \
    chmod 777 /tmp/{php_sessions,gif_uploads/transformed}

EXPOSE 80

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

CMD ["apache2-foreground"]
