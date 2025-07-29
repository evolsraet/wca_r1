#!/bin/sh

# ===========================================
# 위카옥션 컨테이너 시작 스크립트
# ===========================================

set -e

# 환경 변수 기본값
CONTAINER_ROLE=${CONTAINER_ROLE:-app}

echo "Starting container with role: $CONTAINER_ROLE"

# 공통 초기화
init_common() {
    echo "Performing common initialization..."
    
    # 디렉토리 권한 설정
    if [ "$(id -u)" = "0" ]; then
        chown -R sail:sail /var/www/html/storage
        chown -R sail:sail /var/www/html/bootstrap/cache
        chmod -R 775 /var/www/html/storage
        chmod -R 775 /var/www/html/bootstrap/cache
    fi
    
    # Laravel 초기화 (앱 컨테이너에서만)
    if [ "$CONTAINER_ROLE" = "app" ]; then
        echo "Initializing Laravel application..."
        
        # 환경 설정 캐시 지우기
        php artisan config:clear || true
        php artisan route:clear || true
        php artisan view:clear || true
        php artisan cache:clear || true
        
        # 키 생성 (필요시)
        if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
            echo "Generating application key..."
            php artisan key:generate --force
        fi
        
        # 저장소 링크 생성
        php artisan storage:link || true
        
        echo "Laravel initialization completed"
    fi
}

# 역할별 실행
case $CONTAINER_ROLE in
    app)
        echo "Starting PHP-FPM application server..."
        init_common
        exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
        ;;
        
    queue)
        echo "Starting queue worker..."
        init_common
        
        # 큐 워커 시작 전 잠시 대기 (앱 서버 시작 대기)
        sleep 10
        
        echo "Starting Laravel queue worker..."
        exec php artisan queue:work \
            --verbose \
            --tries=3 \
            --timeout=90 \
            --max-jobs=1000 \
            --max-time=3600
        ;;
        
    scheduler)
        echo "Starting scheduler..."
        init_common
        
        # 스케줄러 시작 전 잠시 대기
        sleep 15
        
        echo "Starting Laravel scheduler..."
        while true; do
            php artisan schedule:run --verbose --no-interaction
            sleep 60
        done
        ;;
        
    *)
        echo "Unknown container role: $CONTAINER_ROLE"
        exit 1
        ;;
esac