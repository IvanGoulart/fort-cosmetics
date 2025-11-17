# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

WORKDIR /app

# Copia dependências e instala
COPY package*.json ./
RUN npm ci

# Copia o restante do código
COPY . .

# Compila o Vite (gera public/build)
RUN npm run build

# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP)
# ---------------------------
FROM php:8.3-fpm

# Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia o código-fonte do Laravel
COPY . .

# Copia os assets gerados pelo Vite
COPY --from=frontend /app/public/build ./public/build

# Instala dependências PHP (sem dev)
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cacheia as configurações do Laravel
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Expõe a porta 8000 (Railway usa a variável $PORT)
EXPOSE 8000

# 🚀 Executa migrações e inicia o servidor Laravel
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}