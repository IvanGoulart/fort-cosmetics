# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

WORKDIR /app

COPY src/package*.json ./
COPY src/vite.config.js ./
RUN npm ci

COPY src .
RUN npm run build


# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP-FPM)
# ---------------------------
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia projeto Laravel
COPY src ./

# Copia build do Vite
COPY --from=frontend /app/public/build ./public/build

# Instala PHP deps
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Railway usa a variável PORT automaticamente
ENV PORT=8080

# PHP-FPM escuta em 0.0.0.0:8080
RUN sed -i 's|listen = /run/php/php8.3-fpm.sock|listen = 0.0.0.0:8080|' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 8080

CMD ["php-fpm", "-F"]
