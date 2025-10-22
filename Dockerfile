FROM bitnami/laravel:10

USER root

WORKDIR /app

# Copy only necessary files first
COPY composer.json composer.lock* ./

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist || true

# Copy application code
COPY . .

# Complete composer installation
RUN composer dump-autoload --optimize || true

# Setup environment
RUN if [ ! -f .env ]; then cp .env.example .env; fi && \
    php artisan key:generate --force || true

# Fix permissions - only for directories that need it
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    mkdir -p bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
