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

# 📋 Copia o código Laravel (que está em src/)
COPY src/ ./

# 📦 Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# 🔑 Gera APP_KEY e limpa cache (não quebra se já existir)
RUN php artisan key:generate --force || true \
 && php artisan config:clear || true

# ⚙️ Expor a porta dinâmica do Railway
EXPOSE 8080

# 🚀 Comando padrão para rodar o Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
