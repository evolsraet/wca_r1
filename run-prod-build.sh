#!/bin/bash

# ===========================================
# 위카옥션 운영환경 배포 스크립트
# ===========================================

# TODO: 무중단 배포 추가

set -e

echo "🚀 위카옥션 운영환경 배포 시작..."

# 1. 기존 컨테이너 중지
echo "📦 기존 컨테이너 중지 중..."
docker compose -f docker-compose.prod.yml down

# 2. 최신 코드 다운로드
echo "📥 최신 코드 다운로드 중..."
git pull

# 3. Docker 이미지 재빌드 (Node.js/npm 포함)
echo "🔨 Docker 이미지 빌드 중..."
docker compose -f docker-compose.prod.yml build --no-cache

# 4. 컨테이너 시작
echo "🔥 컨테이너 시작 중..."
docker compose -f docker-compose.prod.yml up -d

# 5. 데이터베이스 마이그레이션
echo "🗄️ 데이터베이스 마이그레이션 중..."
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 6. npm 의존성 설치 및 빌드 (Node.js/npm은 이미지에 포함됨)
echo "📦 npm 의존성 설치 중..."
docker compose -f docker-compose.prod.yml exec app npm install

echo "⚡ Vite 빌드 실행 중..."
docker compose -f docker-compose.prod.yml exec app npm run build

# 7. Laravel 최적화
echo "⚡ Laravel 최적화 중..."
docker compose -f docker-compose.prod.yml exec app php artisan optimize

# 8. 큐 재시작
echo "🔄 큐 워커 재시작 중..."
docker compose -f docker-compose.prod.yml restart queue

echo "✅ 배포 완료!"