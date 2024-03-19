# Imagen oficial de PHP 8.2
#FROM php:8.2-fpm
FROM elrincondeisma/octane:latest

# Instala Composer
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

ENV COMPOSER_ALLOW_SUPERUSER=1

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos del proyecto Laravel al contenedor
COPY . .

RUN rm -rf /app/vendor

RUN rm -rf /app/composer.lock

# Instala las dependencias de Composer
RUN composer install

RUN composer require laravel/octane spiral/roadrunner

# Copia el archivo de configuración de ejemplo .env
#COPY .env.example .env

RUN mkdir -p /app/storage/logs
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear
RUN php artisan octane:install --server="swoole"
CMD php artisan octane:start --server="swoole" --host="0.0.0.0"