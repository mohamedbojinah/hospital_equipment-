FROM webdevops/php-nginx:8.2

# Set Nginx document root to Laravel's public directory
ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
ENV PHP_DATE_TIMEZONE=UTC

WORKDIR /app

# Copy application files
COPY . .

# Install composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R application:application .

# Note: The database migration will be handled manually or via a script, 
# but for now we let the container start automatically on port 80.
