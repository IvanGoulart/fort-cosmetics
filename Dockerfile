# 🧱 1. Imagem base
FROM php:8.3-fpm

# 📦 2. Instala dependências do sistema + Node.js
RUN apt-get update && apt-get install -y \
    git unzip curl nodejs npm \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 🎼 3. Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🏠 4. Define diretório de trabalho
WORKDIR /var/www

# 📋 5. Copia o código Laravel
COPY src/ /var/www/

# 🧩 6. Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# 🎨 7. Instala dependências frontend (Vite) e gera assets
RUN if [ -f package.json ]; then \
      npm install && npm run build; \
    else \
      echo "Nenhum package.json encontrado, pulando build frontend"; \
    fi

# ⚙️ 9. Gera chave, cache e roda migrations
RUN php artisan key:generate --force || true \
    && php artisan config:cache || true \
    && php artisan migrate --force || true

# 🚪 10. Expõe porta e inicia Laravel
EXPOSE 8000
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
