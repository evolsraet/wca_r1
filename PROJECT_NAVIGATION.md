# 위카옥션 v2 - 프로젝트 네비게이션 가이드

**생성일**: 2025-08-05  
**프로젝트 구조**: Laravel 10 + MySQL + Docker

---

## 🗂️ 프로젝트 디렉토리 구조

```
wca_v2/
├── 📁 app/                          # 애플리케이션 코어
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/          # 컨트롤러
│   │   │   ├── 📁 Api/              # API 컨트롤러
│   │   │   ├── 📁 Auth/             # 인증 컨트롤러
│   │   │   └── 📁 Web/              # 웹 컨트롤러
│   │   ├── 📁 Middleware/           # 미들웨어
│   │   └── 📁 Requests/             # Form Request 클래스
│   ├── 📁 Models/                   # Eloquent 모델
│   ├── 📁 Services/                 # 비즈니스 로직 서비스
│   ├── 📁 Jobs/                     # 큐 작업 클래스
│   ├── 📁 Notifications/            # 알림 클래스
│   └── 📁 Traits/                   # 공통 트레이트
├── 📁 database/
│   ├── 📁 migrations/               # 데이터베이스 마이그레이션
│   ├── 📁 seeders/                  # 시드 데이터
│   └── 📁 factories/                # 모델 팩토리
├── 📁 resources/
│   ├── 📁 v1/                       # 구버전 (Vue.js) - 사용 중단
│   └── 📁 v2/                       # 신버전 (Blade + Alpine.js)
│       ├── 📁 views/                # Blade 템플릿
│       ├── 📁 js/                   # JavaScript 파일
│       └── 📁 sass/                 # SCSS 스타일시트
├── 📁 routes/
│   ├── 📄 api.php                   # API 라우트
│   ├── 📄 web.php                   # 메인 웹 라우트
│   └── 📄 web.v2.php                # v2 웹 라우트
├── 📁 storage/
│   ├── 📁 app/public/               # 업로드 파일
│   └── 📁 logs/                     # 로그 파일
├── 📁 tests/                        # 테스트 코드
│   ├── 📁 Feature/                  # 기능 테스트
│   └── 📁 Unit/                     # 단위 테스트
└── 📁 vendor/                       # Composer 패키지
```

---

## 📚 주요 문서 위치

### 🔑 핵심 프로젝트 문서
| 문서명 | 파일 경로 | 설명 |
|--------|-----------|------|
| **프로젝트 개요** | `/README.md` | 기본 설치 및 설정 가이드 |
| **Claude 개발 가이드** | `/CLAUDE.md` | 개발자를 위한 상세 가이드 |
| **기능 명세서** | `/ALL_FUNC_250704.md` | 전체 기능 및 보안 분석 |
| **API 테스트 리포트** | `/API_TEST_REPORT.md` | API 테스트 결과 종합 |

### 📖 새로 생성된 문서 (2025-08-05)
| 문서명 | 파일 경로 | 설명 |
|--------|-----------|------|
| **API 문서** | `/API_DOCUMENTATION.md` | 전체 API 엔드포인트 가이드 |
| **데이터베이스 스키마** | `/DATABASE_SCHEMA.md` | DB 구조 및 관계 다이어그램 |
| **서비스 문서** | `/SERVICES_DOCUMENTATION.md` | 비즈니스 로직 및 서비스 구조 |
| **프로젝트 네비게이션** | `/PROJECT_NAVIGATION.md` | 이 문서 |

---

## 🎯 주요 기능별 파일 위치

### 🚗 경매 시스템
| 구성요소 | 파일 경로 | 역할 |
|----------|-----------|------|
| **경매 모델** | `app/Models/Auction.php` | 경매 데이터 모델 |
| **경매 서비스** | `app/Services/AuctionService.php` | 경매 비즈니스 로직 |
| **경매 API** | `app/Http/Controllers/Api/AuctionController.php` | 경매 API 컨트롤러 |
| **경매 웹** | `app/Http/Controllers/Web/AuctionController.php` | 경매 웹 컨트롤러 |
| **경매 테이블** | `database/migrations/*create_auctions_table.php` | 경매 데이터베이스 구조 |

### 💰 입찰 시스템
| 구성요소 | 파일 경로 | 역할 |
|----------|-----------|------|
| **입찰 모델** | `app/Models/Bid.php` | 입찰 데이터 모델 |
| **입찰 서비스** | `app/Services/BidService.php` | 입찰 비즈니스 로직 |
| **입찰 API** | `app/Http/Controllers/Api/BidController.php` | 입찰 API 컨트롤러 |

### 👥 사용자 관리
| 구성요소 | 파일 경로 | 역할 |
|----------|-----------|------|
| **사용자 모델** | `app/Models/User.php` | 사용자 데이터 모델 |
| **사용자 서비스** | `app/Services/UserService.php` | 사용자 비즈니스 로직 |
| **인증 컨트롤러** | `app/Http/Controllers/Auth/` | 로그인/회원가입 |
| **딜러 서비스** | `app/Services/DealerService.php` | 딜러 관리 로직 |

### 💳 결제 시스템
| 구성요소 | 파일 경로 | 역할 |
|----------|-----------|------|
| **결제 모델** | `app/Models/Payment.php` | 결제 데이터 모델 |
| **나이스페이** | `app/Services/NiceApiService.php` | 나이스페이먼츠 연동 |
| **결제 API** | `app/Http/Controllers/Api/PaymentController.php` | 결제 API |

### 📝 게시판 시스템
| 구성요소 | 파일 경로 | 역할 |
|----------|-----------|------|
| **게시판 모델** | `app/Models/Board.php` | 게시판 데이터 모델 |
| **게시글 모델** | `app/Models/Article.php` | 게시글 데이터 모델 |
| **게시판 서비스** | `app/Services/BoardService.php` | 게시판 비즈니스 로직 |
| **댓글 서비스** | `app/Services/CommentService.php` | 댓글 관리 로직 |

---

## 🔗 외부 연동 서비스

### 🚙 차량 정보 서비스
| 서비스명 | 파일 경로 | 역할 |
|----------|-----------|------|
| **나이스DNR** | `app/Services/NiceDNRService.php` | 차량정보 조회 |
| **카머스** | `app/Services/CarmerceService.php` | 차량시세 조회 |
| **차량이력** | `app/Services/CarHistoryService.php` | 차량이력 관리 |

### 📱 통신 서비스
| 서비스명 | 파일 경로 | 역할 |
|----------|-----------|------|
| **Aligo SMS** | `app/Services/AligoService.php` | SMS 발송 |
| **소셜로그인** | `app/Services/` | 카카오/네이버/구글 |

### 🚚 물류 서비스
| 서비스명 | 파일 경로 | 역할 |
|----------|-----------|------|
| **탁송 서비스** | `app/Services/TaksongService.php` | 차량 운송 관리 |

---

## ⚙️ 설정 및 환경 파일

### 🔧 환경 설정
| 파일명 | 경로 | 설명 |
|--------|------|------|
| **환경 변수** | `.env` | 데이터베이스, API 키 등 |
| **환경 템플릿** | `.env.example` | 환경 변수 템플릿 |
| **Docker 설정** | `docker-compose.yml` | Laravel Sail 설정 |

### 📋 라우트 설정
| 파일명 | 경로 | 설명 |
|--------|------|------|
| **API 라우트** | `routes/api.php` | API 엔드포인트 |
| **웹 라우트** | `routes/web.php` | 메인 웹 라우트 |
| **v2 라우트** | `routes/web.v2.php` | v2 웹 라우트 |

### 🛠️ 설정 파일
| 파일명 | 경로 | 설명 |
|--------|------|------|
| **앱 설정** | `config/app.php` | 기본 애플리케이션 설정 |
| **데이터베이스** | `config/database.php` | DB 연결 설정 |
| **권한 관리** | `config/permission.php` | Spatie Permission |
| **미디어** | `config/media-library.php` | 파일 업로드 설정 |

---

## 🧪 테스트 파일 구조

### 📊 테스트 디렉토리
```
tests/
├── 📁 Feature/                      # 기능 테스트
│   ├── 📁 Api/                      # API 테스트
│   │   ├── 📄 UserApiTest.php       # 사용자 API 테스트
│   │   ├── 📄 AuctionApiTest.php    # 경매 API 테스트
│   │   ├── 📄 SecurityTest.php      # 보안 테스트
│   │   └── 📄 PerformanceTest.php   # 성능 테스트
│   └── 📁 Auth/                     # 인증 테스트
└── 📁 Unit/                         # 단위 테스트
```

### 🎮 테스트 실행 명령어
```bash
# 전체 테스트 실행
./vendor/bin/sail artisan test

# 특정 테스트 파일 실행
./vendor/bin/sail artisan test tests/Feature/Api/UserApiTest.php

# 성능 테스트만 실행
./vendor/bin/sail artisan test --filter=Performance
```

---

## 🗄️ 데이터베이스 파일

### 📊 마이그레이션 파일
- **위치**: `database/migrations/`
- **명명 규칙**: `YYYY_MM_DD_HHMMSS_description.php`
- **주요 테이블**:
  - `2014_10_12_000000_create_users_table.php`
  - `2024_03_25_064619_create_auctions_table.php`
  - `2024_03_25_064620_create_bids_table.php`

### 🌱 시드 파일
- **위치**: `database/seeders/`
- **실행**: `./vendor/bin/sail artisan db:seed`

---

## 📱 프론트엔드 구조

### 🎨 v2 프론트엔드 (현재 사용)
```
resources/v2/
├── 📁 views/                        # Blade 템플릿
│   ├── 📁 layouts/                  # 레이아웃 템플릿
│   ├── 📁 auctions/                 # 경매 페이지
│   ├── 📁 auth/                     # 인증 페이지
│   └── 📁 board/                    # 게시판 페이지
├── 📁 js/                           # JavaScript 파일
└── 📁 sass/                         # SCSS 스타일시트
```

### ⚡ 빌드 명령어
```bash
# 개발 환경 빌드
./vendor/bin/sail npm run dev:v2

# 프로덕션 빌드
./vendor/bin/sail npm run build:v2
```

---

## 🚀 개발 워크플로우

### 🛠️ 개발 환경 시작
```bash
# 1. 컨테이너 시작
./vendor/bin/sail up -d

# 2. 의존성 설치
./vendor/bin/sail composer install

# 3. 환경 설정
cp .env.example .env

# 4. 데이터베이스 마이그레이션
./vendor/bin/sail artisan migrate:fresh --seed

# 5. 프론트엔드 빌드
./vendor/bin/sail npm run dev:v2
```

### 🔍 디버깅 및 로그
```bash
# Laravel 로그 확인
./vendor/bin/sail logs

# 큐 작업 확인
./vendor/bin/sail artisan queue:work

# 실패한 큐 확인
./vendor/bin/sail artisan queue:failed
```

---

## 📞 문제 해결 가이드

### 🆘 일반적인 문제
1. **500 에러**: `./vendor/bin/sail artisan config:clear`
2. **권한 문제**: `./vendor/bin/sail artisan storage:link`
3. **큐 문제**: `./vendor/bin/sail artisan queue:restart`
4. **캐시 문제**: `./vendor/bin/sail artisan cache:clear`

### 🔧 개발 도구
- **Telescope**: `/telescope` (디버깅 툴)
- **Horizon**: `/horizon` (큐 모니터링)
- **API 문서**: `/api/documentation` (예정)

---

## 📋 체크리스트

### ✅ 개발 전 확인사항
- [ ] Docker 환경 정상 작동
- [ ] 데이터베이스 연결 확인
- [ ] 환경 변수 설정 완료
- [ ] 권한 설정 확인
- [ ] 테스트 환경 구축

### ✅ 배포 전 확인사항
- [ ] 전체 테스트 통과
- [ ] 보안 취약점 수정
- [ ] 성능 최적화 완료
- [ ] 로그 설정 확인
- [ ] 백업 정책 수립

---

*본 문서는 위카옥션 v2 프로젝트의 전체 구조를 이해하고 효율적으로 개발할 수 있도록 돕는 가이드입니다. 프로젝트 구조 변경 시 지속적으로 업데이트됩니다.*