#!/bin/sh
set -e

echo "==> [Entrypoint] Memulai setup aplikasi..."

# -------------------------------------------------------
# 0. Fix permission volume (Docker buat volume dengan root)
# -------------------------------------------------------
chown -R www-data:www-data /var/www/html/database /var/www/html/storage
chmod -R 775 /var/www/html/database /var/www/html/storage

# -------------------------------------------------------
# 1. Setup .env jika belum ada
# -------------------------------------------------------
if [ ! -f /var/www/html/.env ]; then
    echo "==> [Entrypoint] Membuat .env dari .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# -------------------------------------------------------
# 2. Generate APP_KEY jika belum ada atau kosong
# -------------------------------------------------------
APP_KEY_VALUE=$(grep -E '^APP_KEY=' /var/www/html/.env | cut -d '=' -f2)
if [ -z "$APP_KEY_VALUE" ]; then
    echo "==> [Entrypoint] Generating APP_KEY..."
    php artisan key:generate --force
fi

# -------------------------------------------------------
# 3. Populate shared public volume (jika masih kosong)
# -------------------------------------------------------
if [ ! -f /var/www/html/public/index.php ]; then
    echo "==> [Entrypoint] Menyalin file public ke volume bersama..."
    cp -r /public-init/. /var/www/html/public/
    chown -R www-data:www-data /var/www/html/public
fi

# -------------------------------------------------------
# 4. Buat file SQLite jika belum ada
# -------------------------------------------------------
DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
if [ ! -f "$DB_PATH" ]; then
    echo "==> [Entrypoint] Membuat database SQLite: $DB_PATH"
    touch "$DB_PATH"
    chown www-data:www-data "$DB_PATH"
fi

# -------------------------------------------------------
# 5. Jalankan migrasi database
# -------------------------------------------------------
echo "==> [Entrypoint] Menjalankan migrasi database..."
php artisan migrate --force

# -------------------------------------------------------
# 6. Buat symlink storage jika belum ada
# -------------------------------------------------------
if [ ! -L /var/www/html/public/storage ]; then
    echo "==> [Entrypoint] Membuat storage symlink..."
    php artisan storage:link
fi

# -------------------------------------------------------
# 7. Optimasi Laravel (config, routes, views cache)
# -------------------------------------------------------
echo "==> [Entrypoint] Optimasi Laravel..."
php artisan optimize

echo "==> [Entrypoint] Setup selesai! Menjalankan: $@"
exec "$@"
