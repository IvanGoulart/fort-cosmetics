# 🧱 1. Imagem base
FROM php:8.3-fpm

# 📦 2. Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 🎼 3. Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🏠 4. Define o diretório de trabalho
WORKDIR /var/www

# 📋 5. Copia o código Laravel (agora sim!)
COPY src/ /var/www/

# 🧩 6. Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# ⚙️ 7. Gera chave, limpa cache e executa migrations
RUN php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan migrate --force || true

# 🚪 8. Expõe a porta e inicia o servidor
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
