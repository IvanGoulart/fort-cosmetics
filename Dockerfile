# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

WORKDIR /app

# Copia apenas arquivos do frontend
COPY src/package*.json ./
COPY src/vite.config.js ./
RUN npm ci

COPY src .
RUN npm run build

# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP CLI)
# ---------------------------
FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# COPIA O LARAVEL CORRETAMENTE
COPY src/ ./

# Copia build Vite
COPY --from=frontend /app/public/build ./public/build/

# Instala deps PHP
RUN composer install --no-dev --optimize-autoloader \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENV PORT=8080

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
