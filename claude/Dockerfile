# Multi-stage build for GIF transformation web app
FROM php:8.2-apache AS base

# Set working directory
WORKDIR /var/www/html

# Enable Apache modules
RUN a2enmod rewrite && \
    a2enmod proxy && \
    a2enmod proxy_fcgi && \
    a2enmod headers && \
    a2enmod expires

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    imagemagick \
    libmagickwand-dev \
    ghostscript \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    gd \
    && pecl install imagick && \
    docker-php-ext-enable imagick

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy PHP configuration
COPY php.ini /usr/local/etc/php/php.ini

# Copy Apache configuration
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Create required directories with proper permissions
RUN mkdir -p /var/www/html/uploads/incoming && \
    mkdir -p /var/www/html/uploads/transformed && \
    mkdir -p /var/www/html/sessions && \
    mkdir -p /var/www/html/public && \
    mkdir -p /tmp/php_sessions && \
    mkdir -p /tmp/gif_uploads/transformed

# Copy application code
COPY src/ /var/www/html/

# Set proper permissions - writable directories for uploads and sessions
RUN chown -R www-data:www-data /var/www/html && \
    chmod 755 /var/www/html && \
    chmod 775 /var/www/html/uploads && \
    chmod 775 /var/www/html/uploads/incoming && \
    chmod 775 /var/www/html/uploads/transformed && \
    chmod 775 /var/www/html/sessions && \
    chmod 777 /tmp/php_sessions && \
    chmod 777 /tmp/gif_uploads && \
    chmod 777 /tmp/gif_uploads/transformed && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    test -f /var/www/html/public/index.php && chmod 755 /var/www/html/public/index.php || true && \
    test -d /var/www/html/api && find /var/www/html/api -type f -name "*.php" -exec chmod 755 {} \; || true && \
    chmod 775 /var/www/html/uploads /var/www/html/uploads/incoming /var/www/html/uploads/transformed /var/www/html/sessions

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port
EXPOSE 80

# Set Apache environment variables
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APACHE_LOG_DIR=/var/log/apache2
ENV APACHE_PID_FILE=/var/run/apache2/apache2.pid
ENV APACHE_RUN_DIR=/var/run/apache2
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

# Start Apache in foreground
CMD ["apache2-foreground"]
