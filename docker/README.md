# 위카옥션 운영환경 Docker 구성

이 디렉터리는 위카옥션 Laravel 애플리케이션의 운영환경 Docker 구성을 포함합니다.

## 구조

```
docker/
├── Dockerfile.prod         # Laravel 애플리케이션 컨테이너
├── Dockerfile.nginx        # Nginx 웹서버 컨테이너
├── start.sh                # 컨테이너 시작 스크립트
├── php/
│   ├── php.ini             # PHP 설정
│   └── php-fpm.conf        # PHP-FPM 설정
├── nginx/
│   ├── nginx.conf          # Nginx 메인 설정
│   └── conf.d/
│       └── wca.conf        # 가상호스트 설정
├── supervisor/
│   ├── supervisord.conf    # Supervisor 메인 설정
│   └── laravel.conf        # Laravel 프로세스 설정
├── mysql/
│   ├── my.cnf              # MySQL 설정
│   └── init/
│       └── 01-init.sql     # 초기화 SQL
├── redis/
│   └── redis.conf          # Redis 설정
└── ssl/
    ├── generate-cert.sh    # SSL 인증서 생성 스크립트
    ├── cert.pem           # SSL 인증서 (생성 후)
    └── key.pem            # SSL 개인키 (생성 후)
```

## 컨테이너 역할

- **app**: PHP-FPM 애플리케이션 서버
- **web**: Nginx 웹서버 (리버스 프록시)
- **queue**: Laravel 큐 워커
- **scheduler**: Laravel 스케줄러
- **mariadb**: MariaDB 데이터베이스 (선택적)
- **redis**: Redis 캐시/세션 저장소 (선택적)

## 주요 특징

### 1. 보안
- 비루트 사용자로 실행
- SSL/TLS 지원
- 보안 헤더 설정
- 속도 제한 (Rate Limiting)
- 민감한 파일 차단

### 2. 성능
- OPcache 활성화
- Gzip 압축
- 정적 파일 캐싱
- 최적화된 PHP-FPM 설정

### 3. 모니터링
- 헬스체크 엔드포인트
- 상태 페이지
- 구조화된 로깅
- 슬로우 쿼리 로그

### 4. 확장성
- 다중 큐 워커
- 프로파일 기반 구성
- 환경별 설정 분리

## 사용법

### 1. 환경 설정
```bash
# .env 파일 복사 및 수정
cp .env.example.prod .env
```

### 2. SSL 인증서 생성 (개발용)
```bash
cd docker/ssl
chmod +x generate-cert.sh
./generate-cert.sh yourdomain.com
```

### 3. 배포 실행
```bash
chmod +x run-prod.sh
./run-prod.sh deploy
```

## 환경 변수

### Docker 관련
- `USE_INTERNAL_DB`: 내부 MariaDB 사용 여부 (true/false)
- `USE_INTERNAL_REDIS`: 내부 Redis 사용 여부 (true/false)
- `WWWUSER`: 컨테이너 사용자 ID (기본: 1000)
- `WWWGROUP`: 컨테이너 그룹 ID (기본: 1000)

### 네트워크
- `APP_PORT`: HTTP 포트 (기본: 80)
- `HTTPS_PORT`: HTTPS 포트 (기본: 443)
- `SSL_CERT_PATH`: SSL 인증서 경로

## 프로파일

Docker Compose 프로파일을 사용하여 필요한 서비스만 실행:

- `internal-db`: 내부 MariaDB 컨테이너
- `internal-redis`: 내부 Redis 컨테이너
- `mail-dev`: 개발용 Mailpit 서버

## 주요 명령어

### 배포
```bash
./run-prod.sh deploy    # 전체 배포
./run-prod.sh restart   # 컨테이너 재시작
./run-prod.sh status    # 상태 확인
./run-prod.sh logs      # 로그 확인
./run-prod.sh rollback  # 롤백
```

### 직접 Docker Compose 사용
```bash
# 서비스 시작
docker compose -f docker-compose.prod.yml up -d

# 특정 프로파일과 함께 시작
COMPOSE_PROFILES=internal-db,internal-redis docker compose -f docker-compose.prod.yml up -d

# 로그 확인
docker compose -f docker-compose.prod.yml logs -f

# 서비스 중지
docker compose -f docker-compose.prod.yml down
```

## 모니터링

### 헬스체크 엔드포인트
- `http://localhost/health`: 기본 헬스체크
- `http://localhost/fpm-status`: PHP-FPM 상태
- `http://localhost/fpm-ping`: PHP-FPM 핑

### 로그 위치
- Nginx: `/var/log/nginx/`
- PHP-FPM: `/var/log/`
- Laravel: `/var/www/html/storage/logs/`

## 보안 고려사항

### 운영환경 체크리스트
- [ ] SSL 인증서 교체 (신뢰할 수 있는 CA 발급)
- [ ] 환경별 비밀번호 변경
- [ ] 방화벽 설정
- [ ] 백업 전략 수립
- [ ] 모니터링 설정
- [ ] 로그 로테이션 설정

### 기본 보안 설정
- HTTPS 강제 리다이렉트
- 보안 헤더 적용
- 속도 제한 설정
- 민감한 파일 차단
- 비루트 사용자 실행

## 문제 해결

### 일반적인 문제
1. **권한 문제**: `WWWUSER`와 `WWWGROUP` 확인
2. **포트 충돌**: `.env`의 포트 설정 확인
3. **메모리 부족**: Docker 리소스 한계 증가
4. **SSL 오류**: 인증서 경로 및 권한 확인

### 로그 확인
```bash
# 모든 서비스 로그
docker compose -f docker-compose.prod.yml logs

# 특정 서비스 로그
docker compose -f docker-compose.prod.yml logs app
docker compose -f docker-compose.prod.yml logs web
```

## 성능 튜닝

### PHP-FPM
- `pm.max_children`: 동시 프로세스 수
- `pm.start_servers`: 시작 프로세스 수
- `pm.max_requests`: 재시작 전 최대 요청 수

### MySQL
- `innodb_buffer_pool_size`: InnoDB 버퍼 풀 크기
- `query_cache_size`: 쿼리 캐시 크기
- `max_connections`: 최대 연결 수

### Redis
- `maxmemory`: 최대 메모리 사용량
- `maxmemory-policy`: 메모리 정책
- `maxclients`: 최대 클라이언트 연결 수