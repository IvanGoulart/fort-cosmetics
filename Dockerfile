# 🧱 Imagem base com PHP 8.3 e extensões recomendadas
FROM php:8.3-fpm

# 📦 Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 🧰 Instala o Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🏠 Define diretório de trabalho
WORKDIR /var/www

# 📋 Copia apenas os arquivos do Laravel (que estão em src/)
COPY src/ ./

# 🧹 Ajusta permissões de cache e logs
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

# ⚙️ Expõe a porta (informativo)
EXPOSE 8080

# 🔑 Gera APP_KEY automaticamente se não existir e limpa cache
RUN php artisan key:generate --force || true \
 && php artisan config:clear || true

# 🚀 Comando padrão para rodar o Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
