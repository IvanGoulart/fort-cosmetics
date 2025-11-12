# 🧱 Imagem base com PHP 8.3 e extensões recomendadas
FROM php:8.3-fpm

# 📦 Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 🧰 Instala o Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🏠 Define diretório de trabalho
WORKDIR /var/www

# 📋 Copia a aplicação Laravel (que está dentro de /src)
COPY src/ ./

# 📦 Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# 🔑 Gera chave e limpa cache
RUN php artisan key:generate --force && php artisan config:cache && php artisan migrate --force || true
# ⚙️ Expor porta dinâmica
EXPOSE 8080

# 🚀 Comando padrão
CMD php artisan serve --host=0.0.0.0 --port=$PORT
