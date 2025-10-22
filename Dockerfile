FROM serversideup/php:8.2-fpm-apache

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || true

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy .env if needed
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate application key
RUN php artisan key:generate --force || true

# Clear and cache config
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 80
