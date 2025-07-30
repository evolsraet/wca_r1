#!/bin/sh
set -e

# Supervisor 로그 디렉토리 생성
mkdir -p /var/log/supervisor

# 첫 실행 시 composer install
if [ ! -d "vendor" ]; then
    echo "Installing composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# npm 의존성 설치 및 빌드
if [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    npm install
fi

echo "Building frontend assets..."
npm run build

# 캐시 클리어 및 재생성
echo "Clearing and rebuilding caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 스토리지 디렉토리 권한 설정
echo "Setting permissions..."
chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Supervisor 로그 디렉토리 생성
mkdir -p /var/log/supervisor

# Supervisor 실행
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf