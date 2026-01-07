FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Node.js y NPM (Versión 20.x)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Obtener Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar el proyecto
COPY . .

# Instalar dependencias y compilar assets
RUN composer install --no-interaction --optimize-autoloader
RUN npm install && npm run build

# Permisos para Windows/WSL
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

# Hacemos un link para las imagenes
RUN php artisan storage:link


# Comando para iniciar el servidor interno de PHP 
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]