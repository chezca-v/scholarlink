# Use PHP 8.2 CLI image
FROM php:8.2-cli

# Install required system packages, Node.js, and PostgreSQL dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory inside the server
WORKDIR /app

# Copy your actual project files into the server
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader

# Install Node dependencies and build the Vite frontend
RUN npm install
RUN npm run build

# Start the Laravel server and run database migrations automatically
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}