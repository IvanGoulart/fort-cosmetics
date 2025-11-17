# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

WORKDIR /app

COPY src/package*.json ./
RUN npm ci

COPY src .
RUN npm run build

# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP-FPM)
# ---------------------------
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY src ./
COPY --from=frontend /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Porta padrão do Railway
ENV PORT=8080

# Ajusta PHP-FPM para escutar em 0.0.0.0:$PORT
RUN sed -i "s|listen = .*|listen = 0.0.0.0:${PORT}|" /usr/local/etc/php-fpm.d/www.conf

EXPOSE 8080

CMD ["php-fpm", "-F"]
