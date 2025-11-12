# ---------------------------
# 🧱 Etapa 1 — Build do frontend (Vite + Tailwind)
# ---------------------------
FROM node:20 AS frontend

# Define diretório de trabalho
WORKDIR /app

# Copia os arquivos de dependência do Node (que estão dentro da pasta src)
COPY src/package*.json ./
COPY src/vite.config.js ./

# Instala dependências
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

# Copia o restante do código da pasta src
COPY src .

# Gera o build de produção (Tailwind + Vite)
RUN npm run build

# ---------------------------
# 🐘 Etapa 2 — Backend (Laravel + PHP)
# ---------------------------
FROM php:8.3-fpm

# Instala dependências necessárias do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia o Laravel (pasta src)
COPY src .

# Copia o build gerado pelo Vite (da etapa Node)
COPY --from=frontend /app/public/build ./public/build

# Instala dependências PHP (sem as de desenvolvimento)
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Gera caches do Laravel (configurações, rotas e views)
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Expõe a porta padrão do Laravel
EXPOSE 8000

# Executa migrações e inicia o servidor Laravel
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
