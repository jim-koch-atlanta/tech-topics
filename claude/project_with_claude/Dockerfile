FROM php:8.2-apache

# Install ImageMagick and other dependencies
RUN apt-get update && apt-get install -y \
    imagemagick \
    libmagickwand-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install opcache

# Install Imagick PHP extension
RUN pecl install imagick && docker-php-ext-enable imagick

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY src/ /var/www/html/
COPY composer.json /var/www/html/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create temp directory for GIF processing
RUN mkdir -p /var/www/html/tmp && chown www-data:www-data /var/www/html/tmp

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80
