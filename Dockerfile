FROM php:8.2-apache

# 1. Install all required system libraries first
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

# 2. Configure & install PHP extensions
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

# Enable Apache mod_rewrite (optional but good for PHP apps)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (cache layer)
COPY composer.json composer.lock* ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy the rest of the application
COPY . .

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]