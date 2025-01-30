FROM mariuszstroz/php8.3-fpm-alpine

WORKDIR /app

# Copy composer files first
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install

# Copy application files
COPY . .
#COPY .env.dist .env

# Copy PHP config files
#COPY etc/infrastructure/php /usr/local/etc/php/

# Final composer tasks
#RUN chmod -R 777 /app/runtime

EXPOSE 8080