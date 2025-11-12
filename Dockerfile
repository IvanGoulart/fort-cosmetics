# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

WORKDIR /app

COPY src/package*.json ./
COPY src/vite.config.js ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY src .
RUN npm ci --omit=dev && npm run build

# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP)
# ---------------------------
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY src .
COPY --from=frontend /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["entrypoint.sh"]
