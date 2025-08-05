# 위카옥션 v2 - 프로젝트 문서 통합 인덱스

**프로젝트명**: 위카옥션 (WeCar Auction) v2  
**기술 스택**: Laravel 10 + MySQL + Docker + Alpine.js  
**문서 생성일**: 2025-08-05  
**문서 버전**: 1.0

---

## 📖 문서 개요

이 인덱스는 위카옥션 v2 프로젝트의 모든 문서를 체계적으로 정리하여 개발자가 필요한 정보를 빠르게 찾을 수 있도록 구성되었습니다.

---

## 🗂️ 문서 분류 및 목록

### 📋 1. 프로젝트 개요 문서

#### 1.1 기본 정보
| 문서명 | 파일 경로 | 설명 | 대상 |
|--------|-----------|------|------|
| **프로젝트 README** | `/README.md` | 기본 설치 및 설정 가이드 | 개발자 전체 |
| **Claude 개발 가이드** | `/CLAUDE.md` | AI 개발자를 위한 상세 가이드 | AI 개발자 |
| **프로젝트 네비게이션** | `/PROJECT_NAVIGATION.md` | 전체 프로젝트 구조 가이드 | 신규 개발자 |
| **통합 인덱스** | `/PROJECT_INDEX.md` | 이 문서 | 모든 사용자 |

#### 1.2 상태 및 분석
| 문서명 | 파일 경로 | 설명 | 업데이트 |
|--------|-----------|------|----------|
| **기능 명세서** | `/ALL_FUNC_250704.md` | 전체 기능 및 보안 분석 (927줄) | 2024-07-04 |
| **API 테스트 리포트** | `/API_TEST_REPORT.md` | 종합 API 테스트 결과 | 2025-08-05 |

### 🔧 2. 기술 문서

#### 2.1 API 문서
| 문서명 | 파일 경로 | 설명 | 상태 |
|--------|-----------|------|------|
| **API 문서** | `/API_DOCUMENTATION.md` | 전체 API 엔드포인트 가이드 | ✅ 완료 |

**주요 API 카테고리**:
- 🔐 인증 및 권한 (Laravel Sanctum)
- 👥 사용자 관리 (회원가입/로그인)
- 🚗 경매 시스템 (등록/조회/상태관리)
- 💰 입찰 시스템 (입찰하기/내역조회)
- 💳 결제 시스템 (나이스페이먼츠)
- 📝 게시판 시스템 (공지/리뷰/문의)
- 🛠️ 관리자 API
- 🔗 외부 API 연동

#### 2.2 데이터베이스 문서
| 문서명 | 파일 경로 | 설명 | 상태 |
|--------|-----------|------|------|
| **데이터베이스 스키마** | `/DATABASE_SCHEMA.md` | DB 구조 및 관계 다이어그램 | ✅ 완료 |

**주요 테이블**:
- **Users**: 사용자 정보 (인증, 권한)
- **Auctions**: 경매 정보 (차량, 상태, 탁송)
- **Bids**: 입찰 정보 (가격, 사용자)
- **Dealers**: 딜러 정보 (사업자등록)
- **Payments**: 결제 정보 (나이스페이)
- **Boards/Articles**: 게시판 시스템

#### 2.3 서비스 아키텍처
| 문서명 | 파일 경로 | 설명 | 상태 |
|--------|-----------|------|------|
| **서비스 문서** | `/SERVICES_DOCUMENTATION.md` | 비즈니스 로직 및 서비스 구조 | ✅ 완료 |

**주요 서비스**:
- **AuctionService**: 경매 핵심 비즈니스 로직
- **UserService**: 사용자 관리 및 인증
- **BidService**: 입찰 관리
- **외부 연동**: NiceDNR, Carmerece, Aligo SMS

---

## 🎯 사용 시나리오별 문서 가이드

### 🆕 신규 개발자 온보딩
1. **시작**: `/README.md` → 기본 설치 및 설정
2. **구조 파악**: `/PROJECT_NAVIGATION.md` → 전체 프로젝트 구조
3. **기능 이해**: `/ALL_FUNC_250704.md` → 비즈니스 요구사항
4. **API 학습**: `/API_DOCUMENTATION.md` → API 사용법
5. **데이터 이해**: `/DATABASE_SCHEMA.md` → DB 구조

### 🔧 기능 개발
1. **API 개발**: `/API_DOCUMENTATION.md` → 엔드포인트 명세
2. **비즈니스 로직**: `/SERVICES_DOCUMENTATION.md` → 서비스 패턴
3. **데이터 모델링**: `/DATABASE_SCHEMA.md` → 테이블 구조
4. **테스트**: `/API_TEST_REPORT.md` → 테스트 사례

### 🐛 버그 수정 및 문제 해결
1. **보안 이슈**: `/ALL_FUNC_250704.md` → 알려진 취약점
2. **API 문제**: `/API_TEST_REPORT.md` → 테스트 실패 사례
3. **파일 위치**: `/PROJECT_NAVIGATION.md` → 관련 파일 찾기

### 🤖 AI 개발자 (Claude)
1. **개발 권한**: `/CLAUDE.md` → 작업 권한 및 규칙
2. **프로젝트 상태**: `/ALL_FUNC_250704.md` → 현재 상황 파악
3. **기술 스택**: `/API_DOCUMENTATION.md` → 사용 기술 확인

---

## 🏗️ 프로젝트 아키텍처 요약

### 🎨 Frontend
- **v2** (현재): Blade + Alpine.js + Bootstrap 5.3
- **v1** (구버전): Vue.js 3 (사용 중단)

### ⚙️ Backend
- **Framework**: Laravel 10 + PHP 8.2
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Queue**: Redis + Laravel Queue
- **Media**: Spatie MediaLibrary
- **Permissions**: Spatie Permission

### 🐳 Infrastructure
- **Development**: Laravel Sail (Docker)
- **Build**: Vite
- **Testing**: Pest PHP

### 🔗 External APIs
- **NiceDNR**: 차량정보 조회
- **Carmerece**: 차량시세 확인
- **NicePay**: 결제 처리
- **Aligo**: SMS 발송
- **Social Login**: 카카오, 네이버, 구글

---

## 📊 프로젝트 현황 (2025-08-05 기준)

### ✅ 완료된 기능
- **기본 경매 시스템**: 등록, 입찰, 낙찰 프로세스
- **사용자 관리**: 회원가입, 로그인, 권한 관리
- **결제 연동**: 나이스페이먼츠 기본 연동
- **게시판**: 공지사항, 리뷰, 문의 게시판
- **외부 API**: 차량정보, 시세, SMS 발송

### ⚠️ 개선 필요 사항
- **보안 취약점**: 4개 심각, 3개 중간 수준 이슈
- **테스트 환경**: Pest 설정 문제로 일부 테스트 실패
- **성능 최적화**: 대용량 데이터 처리 개선 필요
- **문서화**: API 문서 자동화 필요

### 🚨 즉시 수정 필요
1. **PaymentController null 참조 오류** (app/Http/Controllers/Api/PaymentController.php:29)
2. **Mass Assignment 취약점** (app/Models/User.php:45)
3. **Database Factory 스키마 불일치**
4. **Rate Limiting 정책 수립**

---

## 📋 문서 관리 가이드

### 📝 문서 업데이트 규칙
1. **코드 변경 시**: 관련 문서 동시 업데이트
2. **새 기능 추가**: API_DOCUMENTATION.md 업데이트
3. **DB 변경**: DATABASE_SCHEMA.md 업데이트
4. **보안 이슈**: ALL_FUNC_250704.md 또는 CLAUDE.md 업데이트

### 🔄 문서 버전 관리
- **Git 커밋**: 모든 문서 변경사항 추적
- **날짜 표기**: 각 문서 상단에 최종 업데이트 날짜
- **변경 로그**: 주요 변경사항은 별도 기록

### 📖 문서 작성 표준
- **마크다운**: 모든 문서는 .md 형식
- **구조화**: 목차, 섹션, 테이블 활용
- **코드 블록**: 실제 코드 예시 포함
- **링크**: 관련 문서 간 상호 참조

---

## 🔍 빠른 참조

### 🚀 개발 시작
```bash
# 1. 프로젝트 클론 및 설정
git pull
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed

# 2. 프론트엔드 빌드
./vendor/bin/sail npm run dev:v2
```

### 🔧 주요 명령어
```bash
# 테스트 실행
./vendor/bin/sail artisan test

# 로그 확인
./vendor/bin/sail logs

# 큐 실행
./vendor/bin/sail artisan queue:work

# 캐시 클리어
./vendor/bin/sail artisan cache:clear
```

### 📞 문제 해결
1. **500 에러**: 로그 확인 → 캐시 클리어 → 권한 확인
2. **DB 연결 오류**: .env 설정 → 마이그레이션 확인
3. **큐 문제**: queue:restart → queue:failed 확인

---

## 📞 지원 및 연락처

### 📚 추가 자료
- [Laravel 10 공식 문서](https://laravel.com/docs/10.x)
- [Alpine.js 공식 문서](https://alpinejs.dev/)
- [Bootstrap 5 공식 문서](https://getbootstrap.com/docs/5.3/)

### 🔧 개발 도구
- **IDE**: VSCode 권장
- **디버깅**: Laravel Telescope (/telescope)
- **API 테스트**: Postman/Insomnia
- **DB 관리**: phpMyAdmin/TablePlus

---

## 📈 향후 계획

### 단기 목표 (1개월)
- [ ] 보안 취약점 수정 완료
- [ ] API 문서 자동화 구축
- [ ] 테스트 커버리지 80% 달성
- [ ] 성능 최적화 1차 완료

### 중기 목표 (3개월)
- [ ] 마이크로서비스 아키텍처 검토
- [ ] 실시간 알림 시스템 구축
- [ ] 모바일 앱 API 준비
- [ ] CI/CD 파이프라인 구축

### 장기 목표 (6개월)
- [ ] 클라우드 인프라 마이그레이션
- [ ] AI 기반 차량 평가 시스템
- [ ] 블록체인 기반 거래 검증
- [ ] 글로벌 서비스 확장

---

*본 인덱스는 위카옥션 v2 프로젝트의 전체 문서를 효율적으로 관리하고 접근할 수 있도록 구성되었습니다. 프로젝트 진행에 따라 지속적으로 업데이트됩니다.*

---

**📌 중요 공지**: 개발 작업 시 반드시 관련 문서를 먼저 확인하고, 변경사항이 있을 때는 해당 문서를 함께 업데이트해 주세요.