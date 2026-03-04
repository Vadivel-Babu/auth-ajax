FROM php:8.2-apache

# Install system dependencies needed for mysqli, pdo_mysql, zip, etc.
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        zip \
        gd

# Enable Apache mod_rewrite (for pretty URLs if needed)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]