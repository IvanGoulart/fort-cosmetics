# 🏠 Define diretório de trabalho
WORKDIR /var/www

# 📋 Copia o código Laravel
COPY src/ /var/www/

# 🧩 Instala dependências do Laravel (backend PHP)
RUN composer install --no-dev --optimize-autoloader

# 🧱 Instala Node.js + npm (para rodar o Vite build)
RUN apt-get install -y nodejs npm

# 📦 Instala dependências do frontend e gera os assets
RUN npm install && npm run build

# 🧾 Copia o arquivo .env se existir localmente (ou ignora se não houver)
COPY src/.env /var/www/.env || true

# ⚙️ Gera APP_KEY, cache e migrations
RUN php artisan key:generate --force || true \
    && php artisan config:cache || true \
    && php artisan migrate --force || true

# 🚪 Expõe a porta e inicia o servidor Laravel
EXPOSE 8000
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
