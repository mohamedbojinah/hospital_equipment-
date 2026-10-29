FROM webdevops/php-nginx:8.2

# Set Nginx document root to Laravel's public directory
ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
ENV PHP_DATE_TIMEZONE=UTC

WORKDIR /app

# Copy custom Nginx FastCGI buffer configurations
COPY docker/nginx/fastcgi_buffers.conf /opt/docker/etc/nginx/vhost.common.d/fastcgi_buffers.conf
COPY docker/nginx/fastcgi_buffers.conf /opt/docker/etc/nginx/conf.d/fastcgi_buffers.conf

# Copy application files
COPY . .

# Install composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R application:application .
