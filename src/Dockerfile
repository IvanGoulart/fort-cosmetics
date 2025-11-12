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

# 📋 Copia os arquivos da aplicação (o Docker Compose já monta o volume)
COPY . /var/www

# 🧹 Ajusta permissões de cache e logs
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# ⚙️ Exemplo: expõe porta (caso queira rodar php artisan serve)
EXPOSE 8000

# 🧩 Comando padrão (usado pelo container "app")

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
