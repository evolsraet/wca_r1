#!/bin/bash

# ===========================================
# 위카옥션 운영환경 배포 스크립트
# ===========================================

set -e

echo "🚀 위카옥션 운영환경 배포 시작..."

# 1. 기존 컨테이너 중지
echo "📦 기존 컨테이너 중지 중..."
docker compose -f docker-compose.prod.yml down

# 2. 최신 코드 다운로드
echo "📥 최신 코드 다운로드 중..."
git pull

# 3. 컨테이너 시작
echo "🔥 컨테이너 시작 중..."
docker compose -f docker-compose.prod.yml up -d

# 4. Frontend 빌드
echo "🏗️ Frontend 자산 빌드 중..."
docker compose -f docker-compose.prod.yml exec app npm run build

# 5. 데이터베이스 마이그레이션
echo "🗄️ 데이터베이스 마이그레이션 중..."
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 6. Laravel 최적화
echo "⚡ Laravel 최적화 중..."
docker compose -f docker-compose.prod.yml exec app php artisan optimize

echo "✅ 배포 완료!"