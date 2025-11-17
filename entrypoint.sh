#!/bin/sh
set -e

echo "⏳ Aguardando o banco de dados iniciar..."

until php -r "
try {
    new PDO(
        getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
} catch (Exception \$e) {
    fwrite(STDERR, 'Banco ainda não disponível: ' . \$e->getMessage() . PHP_EOL);
    exit(1);
}
"; do
  sleep 3
done

echo "✅ Banco de dados disponível!"

# Permissões
chmod -R 775 storage bootstrap/cache || true

# Migrations
php artisan migrate --force || true
