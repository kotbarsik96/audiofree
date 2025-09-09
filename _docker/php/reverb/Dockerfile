FROM php:8.2-cli

# FROM php:8.2-fpm

WORKDIR /var/www/audiofree

# Install necessary PHP extensions
RUN docker-php-ext-install pcntl pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application
COPY . .

CMD ["php", "artisan", "reverb:start"]