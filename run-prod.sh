#!/bin/bash

# ===========================================
# 위카옥션 빠른 운영환경 배포 스크립트
# (이미지 재빌드 없이 코드만 업데이트)
# ===========================================

set -e

echo "🚀 위카옥션 빠른 배포 시작..."
echo "⏰ 시작 시간: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# 0. 현재 상태 확인
echo "📊 현재 컨테이너 상태 확인..."
docker compose -f docker-compose.prod.yml ps

# 1. 최신 코드 다운로드
echo ""
echo "📥 최신 코드 다운로드 중..."
git pull

# 2. 컨테이너가 실행 중인지 확인 후 재시작
echo ""
echo "🔄 컨테이너 재시작 중..."
if docker compose -f docker-compose.prod.yml ps | grep -q "Up"; then
    docker compose -f docker-compose.prod.yml restart
else
    echo "⚠️  컨테이너가 실행되지 않음. 시작합니다..."
    docker compose -f docker-compose.prod.yml up -d
fi

# 3. npm 의존성 업데이트 및 빌드
echo ""
echo "📦 npm 의존성 확인 중..."
# package.json이 변경되었을 때만 npm install 실행
if git diff HEAD~1 --name-only | grep -q "package.json"; then
    echo "   📌 package.json 변경 감지 - npm install 실행"
    docker compose -f docker-compose.prod.yml exec app npm install
else
    echo "   ✅ package.json 변경 없음 - 건너뜀"
fi

echo ""
echo "⚡ Vite 빌드 실행 중..."
docker compose -f docker-compose.prod.yml exec app npm run build

# 4. Laravel 캐시 클리어 및 최적화
echo ""
echo "🧹 Laravel 캐시 정리 및 최적화..."
# 캐시 클리어를 한 번에 실행
docker compose -f docker-compose.prod.yml exec app bash -c "
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan cache:clear && \
    php artisan optimize && \
    echo '✅ Laravel 최적화 완료'
"

# 5. 큐 재시작
echo ""
echo "🔄 큐 워커 재시작 중..."
docker compose -f docker-compose.prod.yml restart queue scheduler

# 6. 헬스 체크
echo ""
echo "🏥 서비스 상태 확인..."
sleep 3
response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:${APP_PORT:-8080})
if [ "$response" = "200" ]; then
    echo "   ✅ 웹 서비스 정상 (HTTP $response)"
else
    echo "   ⚠️  웹 서비스 확인 필요 (HTTP $response)"
fi

echo ""
echo "✅ 빠른 배포 완료!"
echo "⏰ 완료 시간: $(date '+%Y-%m-%d %H:%M:%S')"
echo "🌐 서비스 URL: http://localhost:${APP_PORT:-8080}"