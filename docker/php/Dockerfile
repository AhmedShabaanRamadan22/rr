# ---- STAGE 1: Build Node.js dependencies ----
FROM node:22-alpine AS node-build

# Set working dir
WORKDIR /var/www/html

# Copy code
COPY . .

# Install packages from the lock file
RUN npm ci

# Build the static files
RUN npm run build
RUN npm run build-rtl

# ---- STAGE 2: Production image ----
FROM php:8.4-fpm-alpine

# Only install necessary runtime packages
RUN apk update && apk add --no-cache \
    curl \
    linux-headers \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libxslt-dev \
    oniguruma-dev \
    pkgconf \
    zip \
    unzip \
    supervisor

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
        calendar \
        mysqli \
        xsl \
        sockets

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Remove the .env file
RUN rm .env

# Copy manifest.json for the static files mapping
COPY --from=node-build /var/www/html/public/build/manifest.json /var/www/html/public/build/manifest.json

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install

# Copy Supervisor configuration file
COPY docker/php/supervisor.conf /etc/supervisor.conf

# Create Supervisor log directory
RUN mkdir -p /var/log/supervisor

# Copy container startup (entrypoint) script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

# Make entrypoint script executable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Add the sqlite database file if needed for local testing
RUN touch database/database.sqlite

# Ensure correct file permissions for the web server user
RUN chown -R www-data:www-data bootstrap database vendor
RUN chmod -R 775 bootstrap database vendor

# Start everything via Supervisor
CMD ["/usr/local/bin/entrypoint.sh"]