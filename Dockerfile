

# Arguments defined in docker-compose.yml
ARG php_version

FROM php:$php_version-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libxml2-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

#Copy php.ini
# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

#Install XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug 

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/manu manu
RUN mkdir -p /home/manu/.composer && \
    chown -R manu:manu /home/manu

# Set working directory
WORKDIR /var/www

USER manu
