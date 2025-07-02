# 위카옥션(WCA) v2 프로젝트 개요

## 🚨 현재 상황 - 긴급
프론트엔드 및 백엔드 담당 개발자가 **인수인계 없이 동시 퇴사**한 상황입니다.
- 프로젝트 문서화 부재
- 시스템 아키텍처 설명 없음
- API 명세서 없음
- 개발 환경 설정 가이드 없음
- 배포 프로세스 문서 없음
- 코드 주석 최소화 상태

## 프로젝트 정보
- **프로젝트명**: 위카옥션 v2 (wca_v2)
- **프레임워크**: Laravel (PHP) with Laravel Sail
- **개발 환경**: Docker 기반 (Laravel Sail)
- **목적**: 중고차 경매 플랫폼

## 기술 스택
### 백엔드
- Laravel Framework
- PHP (Docker 컨테이너)
- MySQL (Docker 컨테이너)
- Sanctum (API 인증)
- Laravel Sail (Docker 개발 환경)

### 프론트엔드 (v2)
- Blade 템플릿 엔진
- Alpine.js
- Bootstrap/Tailwind CSS (추정)
- JavaScript

## 디렉토리 구조
```
wca_v2/
├── app/               # Laravel 애플리케이션 코어
│   ├── Http/         # 컨트롤러, 미들웨어
│   ├── Models/       # Eloquent 모델
│   ├── Services/     # 비즈니스 로직
│   └── Jobs/         # 큐 작업
├── routes/           # 라우팅 파일
│   ├── api.php      # API 라우트 (그대로 사용)
│   ├── web.php      # 웹 라우트 메인
│   └── web.v2.php   # v2 웹 라우트
├── resources/
│   ├── v1/          # 구버전 (Vue.js) - 무시
│   └── v2/          # 신버전 (Blade + Alpine.js)
└── database/        # 마이그레이션, 시더
```

## 주요 기능
1. **경매 시스템** (Auction)
   - 차량 경매 등록/조회/입찰
   - 경매 상태 관리
   - 차량 정보 조회

2. **회원 관리** (User)
   - 회원가입/로그인
   - 소셜 로그인
   - 권한 관리

3. **입찰 시스템** (Bid)
   - 입찰 참여
   - 입찰 내역 관리

4. **게시판** (Board/Article)
   - 공지사항
   - 리뷰
   - 댓글

5. **결제 시스템** (Payment)
   - 결제 처리
   - 결제 내역

6. **기타 기능**
   - 차량 이력 조회
   - 진단 서비스
   - 탁송 서비스
   - 명의이전 서비스

## 현재 상태 점검 필요 사항

### 1. 백엔드 점검
- [ ] API 엔드포인트 작동 여부
- [ ] 데이터베이스 연결 상태
- [ ] 인증 시스템 정상 작동
- [ ] 외부 API 연동 상태 (진단, 탁송, 결제 등)
- [ ] 큐 작업 처리 상태
- [ ] 에러 로그 확인

### 2. 프론트엔드 점검 (v2)
- [ ] 페이지 렌더링 오류
- [ ] JavaScript 콘솔 에러
- [ ] API 호출 실패
- [ ] 404 페이지
- [ ] Alpine.js 컴포넌트 오류
- [ ] 리소스 로딩 실패

### 3. 통합 점검
- [ ] 라우트 매칭 오류
- [ ] CSRF 토큰 문제
- [ ] 세션/쿠키 문제
- [ ] 파일 업로드 기능
- [ ] 권한 체크 오류

## Gemini 초기 작업 가이드

### Phase 0: Docker/Sail 환경 확인
```bash
# 1. Sail 상태 확인
./vendor/bin/sail ps
./vendor/bin/sail up -d

# 2. 컨테이너 로그 확인
./vendor/bin/sail logs
```

### Phase 1: 현황 파악
```bash
# 1. Laravel 환경 확인 (Sail 사용)
./vendor/bin/sail artisan --version
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:list

# 2. 에러 로그 확인
./vendor/bin/sail exec laravel.test tail -f storage/logs/laravel.log

# 3. 데이터베이스 상태
./vendor/bin/sail artisan migrate:status

# 4. 큐 워커 상태
./vendor/bin/sail artisan queue:failed
```

### Phase 2: 프론트엔드 오류 수집
1. 모든 v2 라우트 접속하여 오류 확인
2. 브라우저 개발자 도구에서 콘솔 에러 수집
3. 네트워크 탭에서 실패한 API 요청 확인
4. 누락된 리소스 파일 확인

### Phase 3: 백엔드 API 테스트
1. Postman/Insomnia로 주요 API 엔드포인트 테스트
2. 인증이 필요한 API와 필요없는 API 구분
3. 응답 형식 및 에러 메시지 확인

### Phase 4: 우선순위 정리
1. 치명적 오류 (사이트 접속 불가)
2. 기능 오류 (주요 기능 작동 불가)
3. UI/UX 오류 (표시 오류, 스타일 깨짐)
4. 성능 이슈

## 파악해야 할 중요 정보
### 1. 환경 변수 및 설정
- `.env` 파일 내용 파악
- 외부 API 키 (결제, 진단, 탁송 등)
- 데이터베이스 접속 정보
- 메일/SMS 발송 설정

### 2. 배포 환경
- 프로덕션 서버 정보
- 배포 프로세스
- CI/CD 파이프라인
- 도메인 및 SSL 설정

### 3. 외부 연동 서비스
- 결제 시스템 (PG사)
- 차량 진단 API
- 탁송 서비스 API
- SMS 발송 서비스 (Aligo)
- 소셜 로그인 (카카오, 네이버 등)

## 주의사항
- **v1 디렉토리는 무시** (구버전 Vue.js)
- **v2 디렉토리만 집중** (현재 사용 중인 Blade + Alpine.js)
- 인수인계 문서가 없으므로 코드 분석을 통해 기능 파악 필요
- 외부 API 연동 부분은 특히 주의 (자격증명, API 키 등)
- **모든 명령어는 Sail을 통해 실행** (`./vendor/bin/sail` 사용)

## 연락처 및 참고사항
- 프로젝트 위치: `/Users/evolsraet/web/20240200-위카옥션/wca_v2`
- 개발 환경: macOS (darwin)
- Shell: zsh
