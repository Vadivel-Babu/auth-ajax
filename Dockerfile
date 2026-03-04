FROM php:8.2-apache

# 1. Update package index
RUN apt-get update

# 2. Install all required system libraries
RUN apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libssl-dev \
    pkg-config \
    unzip \
    git \
    libcurl4-openssl-dev

# 3. Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        zip \
        gd \
        mbstring \
        json \
        openssl \
        curl \
        bcmath

# Enable Apache rewrite module (optional but useful)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for caching
COPY composer.json composer.lock* ./

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy the rest of the app
COPY . .

# Expose port
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]