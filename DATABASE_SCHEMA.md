# 위카옥션 v2 - 데이터베이스 스키마 문서

**생성일**: 2025-08-05  
**데이터베이스**: MySQL  
**Laravel 버전**: 10.x

---

## 📋 목차

1. [데이터베이스 개요](#데이터베이스-개요)
2. [핵심 테이블](#핵심-테이블)
3. [관계 다이어그램](#관계-다이어그램)
4. [인덱스 및 최적화](#인덱스-및-최적화)
5. [마이그레이션 히스토리](#마이그레이션-히스토리)

---

## 🗃️ 데이터베이스 개요

### 주요 도메인
- **사용자 관리**: 회원, 딜러, 관리자
- **경매 시스템**: 차량 등록, 입찰, 낙찰
- **결제 시스템**: 결제 처리, 수수료 관리
- **게시판**: 공지사항, 리뷰, 문의
- **외부 연동**: 차량정보, 시세, SMS

### 테이블 분류
- **인증 & 권한**: users, roles, permissions, user_sns
- **경매 핵심**: auctions, bids, dealers
- **결제**: payments, auctions_payments
- **콘텐츠**: boards, articles, comments, posts
- **외부 데이터**: nice_dnr_datas, nice_carhistorys
- **시스템**: sessions, jobs, failed_jobs, telescope_entries

---

## 📊 핵심 테이블

### 1. Users 테이블
**경로**: `database/migrations/2014_10_12_000000_create_users_table.php`

```sql
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT '이름',
  email VARCHAR(255) UNIQUE NOT NULL COMMENT '이메일',
  email_verified_at TIMESTAMP NULL COMMENT '이메일 확인',
  password VARCHAR(255) NOT NULL COMMENT '비밀번호',
  remember_token VARCHAR(100) NULL,
  phone VARCHAR(20) NULL COMMENT '전화번호',
  status VARCHAR(20) DEFAULT 'active' COMMENT '상태',
  penalty_until TIMESTAMP NULL COMMENT '제재 해제일',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL
);
```

**관계**:
- `hasMany(Auction)` - 사용자가 등록한 경매
- `hasMany(Bid)` - 사용자의 입찰 내역
- `hasMany(Article)` - 작성한 게시글
- `hasMany(Comment)` - 작성한 댓글
- `hasOne(Dealer)` - 딜러 정보 (딜러인 경우)

### 2. Auctions 테이블
**경로**: `database/migrations/2024_03_25_064619_create_auctions_table.php`

```sql
CREATE TABLE auctions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  unique_number INTEGER UNIQUE NOT NULL COMMENT '고유번호',
  auction_type BOOLEAN NOT NULL COMMENT '경매 타입',
  user_id BIGINT UNSIGNED NOT NULL COMMENT '소유자 ID',
  owner_name VARCHAR(255) NOT NULL COMMENT '소유자명',
  company_name VARCHAR(255) NULL COMMENT '회사명',
  car_no VARCHAR(255) NOT NULL COMMENT '차량번호',
  status VARCHAR(255) DEFAULT 'ask' COMMENT '진행상태',
  
  -- 진단 관련
  diag_id BIGINT UNSIGNED NULL COMMENT '진단 ID',
  diag_check_at TIMESTAMP NULL COMMENT '진단확인일',
  diag_first_at TIMESTAMP NULL COMMENT '진단희망 날짜1',
  diag_second_at TIMESTAMP NULL COMMENT '진단희망 날짜2',
  
  -- 주소 정보
  region VARCHAR(255) NULL COMMENT '지역',
  addr_post VARCHAR(255) NULL COMMENT '우편번호',
  addr1 VARCHAR(255) NULL COMMENT '주소',
  addr2 VARCHAR(255) NULL COMMENT '상세주소',
  dest_addr_post VARCHAR(255) NULL COMMENT '도착지 우편번호',
  dest_addr1 VARCHAR(255) NULL COMMENT '도착지 주소',
  dest_addr2 VARCHAR(255) NULL COMMENT '도착지 상세주소',
  
  -- 계좌 정보
  bank VARCHAR(255) NULL COMMENT '은행',
  account VARCHAR(255) NULL COMMENT '계좌번호',
  account_name VARCHAR(255) NULL COMMENT '예금주',
  
  -- 차량 정보
  car_maker VARCHAR(255) NULL COMMENT '차량제조사',
  car_model VARCHAR(255) NULL COMMENT '차량모델',
  car_model_sub VARCHAR(255) NULL COMMENT '차량모델 서브',
  car_grade VARCHAR(255) NULL COMMENT '차량등급',
  car_grade_sub VARCHAR(255) NULL COMMENT '차량등급 서브',
  car_year VARCHAR(255) NULL COMMENT '차량연식',
  car_first_reg_date TIMESTAMP NULL COMMENT '최초등록일',
  car_mission VARCHAR(255) NULL COMMENT '차량미션',
  car_fuel VARCHAR(255) NULL COMMENT '차량연료',
  car_engine_type VARCHAR(255) NULL COMMENT '엔진타입',
  car_km VARCHAR(255) NULL COMMENT '주행거리',
  car_thumbnail VARCHAR(255) NULL COMMENT '썸네일',
  car_status VARCHAR(255) NULL COMMENT '차량상태',
  
  -- 시세 정보
  car_price_now VARCHAR(255) NULL COMMENT '소매 시세',
  car_price_now_whole VARCHAR(255) NULL COMMENT '도매 시세',
  
  -- 경매 관련
  hope_price INTEGER UNSIGNED NULL COMMENT '희망가',
  final_price INTEGER UNSIGNED NULL COMMENT '낙찰가',
  final_at TIMESTAMP NULL COMMENT '경매마감일',
  choice_at TIMESTAMP NULL COMMENT '선택일',
  done_at TIMESTAMP NULL COMMENT '완료일',
  bid_id BIGINT UNSIGNED NULL COMMENT '낙찰 입찰 ID',
  
  -- 수수료
  success_fee INTEGER UNSIGNED NULL COMMENT '성공수수료',
  diag_fee INTEGER UNSIGNED NULL COMMENT '진단수수료',
  total_fee INTEGER UNSIGNED NULL COMMENT '총비용',
  
  -- 탁송 정보
  taksong_id VARCHAR(255) NULL COMMENT '탁송 ID',
  taksong_status VARCHAR(255) NULL COMMENT '탁송상태',
  taksong_wish_at TIMESTAMP NULL COMMENT '탁송희망일',
  taksong_courier_fee VARCHAR(255) NULL COMMENT '탁송요금',
  taksong_courier_name VARCHAR(255) NULL COMMENT '기사명',
  taksong_courier_mobile VARCHAR(255) NULL COMMENT '기사전화번호',
  taksong_departure_address TEXT NULL COMMENT '출발지',
  taksong_departure_mobile VARCHAR(255) NULL COMMENT '출발지 전화번호',
  taksong_dest_address TEXT NULL COMMENT '도착지',
  taksong_dest_mobile VARCHAR(255) NULL COMMENT '도착지 전화번호',
  taksong_departure_at DATETIME NULL COMMENT '출발시간',
  taksong_dest_at DATETIME NULL COMMENT '도착시간',
  
  -- 기타
  memo TEXT NULL COMMENT '고객 메모',
  memo_digician TEXT NULL COMMENT '평가사 의견',
  personal_id_number VARCHAR(255) NULL COMMENT '주민등록번호',
  verified BOOLEAN DEFAULT 0 COMMENT '검증여부',
  hit INTEGER UNSIGNED DEFAULT 0 COMMENT '조회수',
  
  -- 플래그
  is_reauction BOOLEAN DEFAULT 0 COMMENT '재경매여부',
  is_deposit VARCHAR(255) NULL COMMENT '입금여부',
  is_taksong VARCHAR(255) NULL COMMENT '탁송상태',
  is_biz BOOLEAN DEFAULT 0 COMMENT '법인차량',
  is_accident BOOLEAN DEFAULT 0 COMMENT '사고차량',
  is_business_owner BOOLEAN DEFAULT 0 COMMENT '사업자 소유',
  is_agree BOOLEAN DEFAULT 0 COMMENT '동의여부',
  has_uploaded_name_change_file BOOLEAN DEFAULT 0 COMMENT '명의변경서류 업로드여부',
  
  -- 연락처
  customTel1 VARCHAR(255) NULL COMMENT '고객 전화번호1',
  customTel2 VARCHAR(255) NULL COMMENT '고객 전화번호2',
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (bid_id) REFERENCES bids(id) ON DELETE CASCADE,
  INDEX idx_car_no (car_no),
  INDEX idx_status (status)
);
```

**관계**:
- `belongsTo(User)` - 경매 등록자
- `hasMany(Bid)` - 입찰 내역
- `belongsTo(Bid)` - 낙찰 입찰
- `hasMany(Media)` - 첨부 이미지들
- `hasMany(AuctionLog)` - 상태 변경 로그

**상태 라이프사이클**:
1. `ask` - 신청
2. `diagnosis` - 진단 중
3. `progress` - 경매 진행
4. `completed` - 낙찰 완료
5. `delivery` - 배송 중
6. `finished` - 거래 완료
7. `failed` - 유찰

### 3. Bids 테이블
**경로**: `database/migrations/2024_03_25_064620_create_bids_table.php`

```sql
CREATE TABLE bids (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  auction_id BIGINT UNSIGNED NOT NULL COMMENT '경매 ID',
  user_id BIGINT UNSIGNED NOT NULL COMMENT '입찰자 ID',
  status VARCHAR(255) NULL COMMENT '상태',
  price INTEGER UNSIGNED NOT NULL COMMENT '입찰가격',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (auction_id) REFERENCES auctions(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_status (status)
);
```

**관계**:
- `belongsTo(Auction)` - 입찰 대상 경매
- `belongsTo(User)` - 입찰자

### 4. Dealers 테이블
**경로**: `database/migrations/2024_03_25_005624_create_dealers_table.php`

```sql
CREATE TABLE dealers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL COMMENT '사용자 ID',
  business_name VARCHAR(255) NULL COMMENT '상호명',
  business_identity_number VARCHAR(255) NULL COMMENT '사업자등록번호',
  business_license_file VARCHAR(255) NULL COMMENT '사업자등록증',
  business_address VARCHAR(255) NULL COMMENT '사업장 주소',
  business_phone VARCHAR(255) NULL COMMENT '사업장 전화번호',
  representative_name VARCHAR(255) NULL COMMENT '대표자명',
  bank_name VARCHAR(255) NULL COMMENT '은행명',
  account_number VARCHAR(255) NULL COMMENT '계좌번호',
  account_holder VARCHAR(255) NULL COMMENT '예금주',
  status VARCHAR(20) DEFAULT 'pending' COMMENT '승인상태',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**관계**:
- `belongsTo(User)` - 딜러 사용자

### 5. Payments 테이블
**경로**: `database/migrations/2025_01_08_154824_payments_table.php`

```sql
CREATE TABLE payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL COMMENT '결제자 ID',
  auction_id BIGINT UNSIGNED NULL COMMENT '경매 ID',
  amount DECIMAL(10,2) NOT NULL COMMENT '결제금액',
  currency VARCHAR(3) DEFAULT 'KRW' COMMENT '통화',
  status VARCHAR(20) DEFAULT 'pending' COMMENT '결제상태',
  payment_method VARCHAR(50) NULL COMMENT '결제수단',
  payment_key VARCHAR(255) NULL COMMENT '결제키',
  transaction_id VARCHAR(255) NULL COMMENT '거래ID',
  paid_at TIMESTAMP NULL COMMENT '결제완료일',
  cancelled_at TIMESTAMP NULL COMMENT '취소일',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (auction_id) REFERENCES auctions(id) ON DELETE SET NULL
);
```

### 6. Boards & Articles 테이블

**Boards 테이블**:
```sql
CREATE TABLE boards (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT '게시판명',
  slug VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL 슬러그',
  description TEXT NULL COMMENT '설명',
  is_active BOOLEAN DEFAULT 1 COMMENT '활성상태',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Articles 테이블**:
```sql
CREATE TABLE articles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  board_id BIGINT UNSIGNED NOT NULL COMMENT '게시판 ID',
  user_id BIGINT UNSIGNED NOT NULL COMMENT '작성자 ID',
  title VARCHAR(255) NOT NULL COMMENT '제목',
  content LONGTEXT NOT NULL COMMENT '내용',
  hit INTEGER UNSIGNED DEFAULT 0 COMMENT '조회수',
  is_notice BOOLEAN DEFAULT 0 COMMENT '공지여부',
  is_secret BOOLEAN DEFAULT 0 COMMENT '비밀글여부',
  status VARCHAR(20) DEFAULT 'published' COMMENT '상태',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (board_id) REFERENCES boards(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 🔗 관계 다이어그램

### 핵심 관계
```
Users (1) ----< (M) Auctions
Users (1) ----< (M) Bids  
Users (1) ----o (1) Dealers
Auctions (1) ----< (M) Bids
Auctions (1) ----o (1) Bid (낙찰)
Auctions (1) ----< (M) Payments
Auctions (1) ----< (M) Media
Boards (1) ----< (M) Articles
Articles (1) ----< (M) Comments
Users (1) ----< (M) Articles
Users (1) ----< (M) Comments
```

### ERD 요약
- **Users**: 시스템의 중심 엔티티
- **Auctions**: 경매 정보와 차량 정보를 포함하는 복합 엔티티
- **Bids**: 경매와 사용자를 연결하는 중간 엔티티
- **Dealers**: Users의 확장 정보
- **Payments**: 결제 관련 독립 엔티티

---

## 📈 인덱스 및 최적화

### 주요 인덱스
```sql
-- Users 테이블
INDEX idx_users_email (email)
INDEX idx_users_status (status)

-- Auctions 테이블  
INDEX idx_auctions_car_no (car_no)
INDEX idx_auctions_status (status)
INDEX idx_auctions_user_id (user_id)
INDEX idx_auctions_final_at (final_at)

-- Bids 테이블
INDEX idx_bids_auction_id (auction_id)
INDEX idx_bids_user_id (user_id)  
INDEX idx_bids_status (status)
INDEX idx_bids_created_at (created_at)

-- Articles 테이블
INDEX idx_articles_board_id (board_id)
INDEX idx_articles_user_id (user_id)
INDEX idx_articles_status (status)
INDEX idx_articles_created_at (created_at)
```

### 성능 최적화 권장사항
1. **경매 목록 조회**: `status`, `final_at` 복합 인덱스 고려
2. **입찰 내역**: `auction_id`, `created_at` 복합 인덱스
3. **사용자별 경매**: `user_id`, `status` 복합 인덱스
4. **파티셔닝**: 대용량 데이터 시 날짜별 파티셔닝 고려

---

## 📝 마이그레이션 히스토리

### 초기 구조 (2014-2024)
- `2014_10_12_000000` - Users 기본 구조
- `2019_12_14_000001` - Personal Access Tokens (Sanctum)
- `2022_09_30_172105` - Permission Tables (Spatie)

### 경매 시스템 (2024년 3월)
- `2024_03_25_005624` - Dealers 테이블
- `2024_03_25_064619` - Auctions 테이블 (핵심)
- `2024_03_25_064620` - Bids 테이블
- `2024_03_25_064700` - Reviews 테이블

### 게시판 시스템 (2024년 7월)
- `2024_07_12_161426` - Boards 테이블
- `2024_07_12_163649` - Articles 테이블
- `2024_07_25_170458` - Comments 테이블

### 결제 시스템 (2025년 1월)
- `2025_01_08_154824` - Payments 테이블
- `2025_01_23_134429` - Auctions_Payments 관계 테이블

### 외부 연동 (2025년 3-4월)
- `2025_03_24_125201` - Nice DNR 데이터
- `2025_03_25_160758` - Nice 차량이력
- `2025_03_19_170321` - User SNS 연동

### 최근 개선사항 (2025년 5-6월)
- `2025_05_19_212803` - 진단 정보 테이블
- `2025_05_20_160858` - 경매 로그 테이블
- `2025_06_26_174201` - 탁송 상태 추가

---

## 🔧 데이터베이스 운영

### 백업 정책
- **일일 백업**: 전체 데이터베이스
- **실시간 복제**: 읽기 전용 슬레이브
- **주요 테이블**: Auctions, Bids, Users 우선

### 모니터링 대상
- **슬로우 쿼리**: 1초 이상 쿼리
- **락 대기**: 입찰 시 동시성 이슈
- **용량 증가**: Media, Logs 테이블

### 데이터 정리
- **Telescope 데이터**: 30일 보관
- **Soft Delete**: 1년 후 영구 삭제
- **로그 테이블**: 6개월 보관

---

*본 문서는 2025-08-05 기준으로 작성되었으며, 스키마 변경에 따라 지속적으로 업데이트됩니다.*