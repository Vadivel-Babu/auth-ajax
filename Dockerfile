FROM php:8.2-apache

# Clean apt cache after each RUN to keep image small
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Configure GD first (special case)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install extensions ONE BY ONE (no && chain, no -j parallel)
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install json
RUN docker-php-ext-install openssl
RUN docker-php-ext-install curl
RUN docker-php-ext-install bcmath

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for caching
COPY composer.json composer.lock* ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy the rest of the application
COPY . .

# Expose port
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]