#!/bin/sh
set -e

echo "⏳ Aguardando o banco de dados iniciar..."

# Espera o banco responder antes de continuar
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

# Garante que as pastas de cache tenham permissão correta
chmod -R 775 storage bootstrap/cache || true

# Executa migrações (ignora falha caso já estejam aplicadas)
php artisan migrate --force || true

# Inicia o servidor Laravel
echo "🚀 Iniciando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
