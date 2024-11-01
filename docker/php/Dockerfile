FROM php:8.3.0-fpm

# Install necessary packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql intl mbstring zip gd

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure Xdebug
COPY ./conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install PCOV for code coverage
RUN pecl install pcov \
    && docker-php-ext-enable pcov

# Set up non-root user for better security
RUN useradd -m -d /home/symfony -s /bin/bash symfony
USER symfony

# Set working directory
WORKDIR /var/www/symfony