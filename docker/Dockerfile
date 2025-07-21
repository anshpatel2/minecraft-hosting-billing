FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    build-base \
    curl \
    freetype-dev \
    git \
    icu-dev \
    jpeg-dev \
    libpng-dev \
    libzip-dev \
    nginx \
    nodejs \
    npm \
    oniguruma-dev \
    supervisor \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        intl \
        mbstring \
        mysqli \
        pdo_mysql \
        zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copy configuration files
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 9000

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
