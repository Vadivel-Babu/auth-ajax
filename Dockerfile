FROM php:8.2-apache

# 1. Update and install all required system libraries
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

# 2. Configure GD separately (needs freetype & jpeg)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# 3. Install extensions one by one (safer, no parallel build issues)
RUN docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install json \
    && docker-php-ext-install openssl \
    && docker-php-ext-install curl \
    && docker-php-ext-install bcmath

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for layer caching)
COPY composer.json composer.lock* ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy the rest of the application
COPY . .

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]