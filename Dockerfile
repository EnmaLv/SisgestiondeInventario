FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP
# Se añaden libwebp-dev, libfreetype6-dev y libjpeg62-turbo-dev para soporte de imágenes avanzado
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libwebp-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar la extensión GD para indicarle a PHP que soporte WebP, Freetype (fuentes TTF) y JPEG
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

# Instalar extensiones de PHP necesarias para Laravel (incluyendo el GD ya configurado)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Node.js y NPM (Versión 20.x)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Obtener Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Exponer el puerto de Laravel (8000) y de Vite (5731)
EXPOSE 8000 5731

# Comando para iniciar el servidor interno de PHP
CMD ["composer", "run", "dev"]