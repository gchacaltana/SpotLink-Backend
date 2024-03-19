# Imagen oficial de PHP 8.2
FROM php:8.2-fpm

# Instala las dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Instala Composer
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

ENV COMPOSER_ALLOW_SUPERUSER=1

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos del proyecto Laravel al contenedor
COPY shortURLServiceAPI .

RUN rm -rf /app/vendor

RUN rm -rf /app/composer.lock

# Instala las dependencias de Composer
RUN composer install

#RUN composer require laravel/octane spiral/roadrunner

# Copia el archivo de configuración de ejemplo .env
#COPY .env.example .env

RUN mkdir -p /app/storage/logs
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear

# Genera la clave de la aplicación
RUN php artisan key:generate

# Expone el puerto 8000 para acceder a la aplicación
EXPOSE 8000

# Ejecuta el servidor de desarrollo de Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000