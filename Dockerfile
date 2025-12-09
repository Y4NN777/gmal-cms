# Multi-stage build for Laravel + Vue application
FROM php:8.2-fpm as php

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Create .env file from example (Render will override with environment variables)
RUN cp .env.example .env

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js and build frontend
FROM node:20 as node

WORKDIR /var/www
COPY --from=php /var/www /var/www

RUN npm ci && npm run build

# Final stage
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    nginx \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

WORKDIR /var/www

# Copy built application
COPY --from=node /var/www /var/www

# Copy nginx configuration
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create startup script with error handling
RUN echo '#!/bin/bash\n\
set -e\n\
echo "Starting application..."\n\
\n\
# Wait for database to be ready\n\
echo "Waiting for database..."\n\
sleep 5\n\
\n\
# Run Laravel setup commands\n\
echo "Running artisan commands..."\n\
php artisan cache:clear || echo "Cache clear skipped"\n\
php artisan config:cache || { echo "Config cache failed"; exit 1; }\n\
php artisan route:cache || { echo "Route cache failed"; exit 1; }\n\
php artisan view:cache || { echo "View cache failed"; exit 1; }\n\
php artisan migrate --force || { echo "Migration failed"; exit 1; }\n\
php artisan db:seed --class=AdminUserSeeder --force || echo "Admin seeder skipped"\n\
php artisan storage:link || echo "Storage link already exists"\n\
\n\
echo "Starting supervisor..."\n\
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' > /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
