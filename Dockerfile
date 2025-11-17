# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

WORKDIR /app

COPY src/package*.json ./
COPY src/vite.config.js ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY src .
RUN npm run build


# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP CLI)
# ---------------------------
FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia projeto Laravel
COPY src .

# Copia o build do Vite para public
COPY --from=frontend /app/public/build ./public/build

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Permissões necessárias
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www

# Copia e habilita o entrypoint

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]