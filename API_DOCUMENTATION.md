# 위카옥션 v2 - API 문서

**생성일**: 2025-08-05  
**버전**: v2  
**환경**: Laravel 10 + Laravel Sanctum

---

## 📋 목차

1. [인증 및 권한](#인증-및-권한)
2. [사용자 관리 API](#사용자-관리-api)
3. [경매 시스템 API](#경매-시스템-api)
4. [입찰 시스템 API](#입찰-시스템-api)
5. [결제 시스템 API](#결제-시스템-api)
6. [게시판 시스템 API](#게시판-시스템-api)
7. [관리자 API](#관리자-api)
8. [외부 API 연동](#외부-api-연동)
9. [오류 처리](#오류-처리)

---

## 🔐 인증 및 권한

### 인증 시스템
- **Laravel Sanctum** 기반 API 토큰 인증
- **세션 기반** 웹 인증 (웹 UI용)
- **소셜 로그인** 지원 (카카오, 네이버, 구글)

### 권한 레벨
- **Guest**: 비회원 (경매 조회만 가능)
- **User**: 일반 회원 (입찰, 경매 참여)
- **Dealer**: 딜러 회원 (경매 등록, 판매)
- **Admin**: 관리자 (시스템 관리)

### 인증 엔드포인트

#### 로그인
```http
POST /login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password",
  "remember": true
}
```

**응답 (성공)**:
```json
{
  "success": true,
  "message": "로그인 성공",
  "user": {
    "id": 1,
    "name": "사용자명",
    "email": "user@example.com",
    "role": "user"
  },
  "token": "sanctum_token_here"
}
```

#### 로그아웃
```http
POST /logout
Authorization: Bearer {token}
```

#### 소셜 로그인
```http
GET /auth/{provider}/redirect
```
**지원 Provider**: `kakao`, `naver`, `google`

---

## 👥 사용자 관리 API

### 회원가입
```http
POST /api/users
Content-Type: application/json

{
  "user": {
    "name": "홍길동",
    "email": "hong@example.com",
    "phone": "01012345678",
    "password": "password123!",
    "password_confirmation": "password123!",
    "isCheckPrivacy": 1
  }
}
```

**응답**:
```json
{
  "success": true,
  "message": "회원가입이 완료되었습니다",
  "user": {
    "id": 1,
    "name": "홍길동",
    "email": "hong@example.com",
    "phone": "01012345678",
    "created_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 사용자 정보 조회
```http
GET /api/users/me
Authorization: Bearer {token}
```

### 사용자 정보 수정
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "새로운 이름",
  "phone": "01087654321"
}
```

---

## 🚗 경매 시스템 API

### 경매 목록 조회
```http
GET /api/auctions
Authorization: Bearer {token}
```

**쿼리 파라미터**:
- `page`: 페이지 번호 (기본값: 1)
- `per_page`: 페이지당 항목 수 (기본값: 20)
- `status`: 경매 상태 필터 (`diagnosis`, `progress`, `completed`, `failed`)
- `car_model`: 차종 필터
- `min_price`: 최소 가격
- `max_price`: 최대 가격

**응답**:
```json
{
  "data": [
    {
      "id": 1,
      "title": "2020년 현대 아반떼",
      "description": "상태 양호한 차량입니다",
      "status": "progress",
      "current_price": 15000000,
      "start_price": 12000000,
      "end_time": "2025-01-10T15:00:00.000000Z",
      "car_model": "아반떼",
      "car_year": 2020,
      "car_mileage": 50000,
      "car_thumbnail": "uploads/cars/thumbnail.jpg",
      "seller": {
        "id": 2,
        "name": "딜러명"
      },
      "bid_count": 15,
      "highest_bid": {
        "amount": 15000000,
        "bidder": "김**"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100
  }
}
```

### 경매 상세 조회
```http
GET /api/auctions/{id}
Authorization: Bearer {token}
```

### 경매 등록 (딜러 전용)
```http
POST /api/auctions
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "title": "2020년 현대 아반떼",
  "description": "상태 양호한 차량",
  "start_price": 12000000,
  "car_model": "아반떼",
  "car_brand": "현대",
  "car_year": 2020,
  "car_mileage": 50000,
  "car_engine_type": "가솔린",
  "car_transmission": "자동",
  "car_fuel": "가솔린",
  "car_accident": "무사고",
  "personal_id_number": "주민등록번호",
  "files[]": [이미지 파일들]
}
```

### 경매 상태 변경
```http
PUT /api/auctions/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "progress",
  "end_time": "2025-01-10T15:00:00"
}
```

**경매 상태 라이프사이클**:
1. `diagnosis` - 진단 중
2. `progress` - 경매 진행 중
3. `completed` - 낙찰 완료
4. `delivery` - 배송 중
5. `finished` - 거래 완료
6. `failed` - 유찰

---

## 💰 입찰 시스템 API

### 입찰하기
```http
POST /api/bids
Authorization: Bearer {token}
Content-Type: application/json

{
  "auction_id": 1,
  "bid_amount": 15500000
}
```

**응답**:
```json
{
  "success": true,
  "message": "입찰이 완료되었습니다",
  "bid": {
    "id": 123,
    "auction_id": 1,
    "user_id": 1,
    "bid_amount": 15500000,
    "created_at": "2025-01-01T10:30:00.000000Z"
  },
  "auction": {
    "current_price": 15500000,
    "bid_count": 16
  }
}
```

### 입찰 내역 조회
```http
GET /api/bids
Authorization: Bearer {token}
```

**쿼리 파라미터**:
- `auction_id`: 특정 경매의 입찰 내역
- `user_id`: 특정 사용자의 입찰 내역

---

## 💳 결제 시스템 API

### 결제 정보 조회
```http
GET /api/payment
Authorization: Bearer {token}
```

**쿼리 파라미터**:
- `auction_id`: 경매 ID (필수)

### 결제 요청
```http
POST /api/payments
Authorization: Bearer {token}
Content-Type: application/json

{
  "auction_id": 1,
  "payment_method": "card",
  "amount": 15500000
}
```

### 결제 완료 처리
```http
PUT /api/payments/{id}/complete
Authorization: Bearer {token}
Content-Type: application/json

{
  "payment_key": "nice_payment_key",
  "status": "completed"
}
```

---

## 📝 게시판 시스템 API

### 게시판 목록
```http
GET /api/boards
```

### 게시글 목록
```http
GET /api/boards/{board_id}/articles
```

**쿼리 파라미터**:
- `page`: 페이지 번호
- `search`: 검색어
- `category`: 카테고리 ID

### 게시글 작성
```http
POST /api/boards/{board_id}/articles
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "게시글 제목",
  "content": "게시글 내용",
  "category_id": 1
}
```

### 댓글 작성
```http
POST /api/articles/{article_id}/comments
Authorization: Bearer {token}
Content-Type: application/json

{
  "content": "댓글 내용",
  "parent_id": null
}
```

---

## 🛠️ 관리자 API

### 사용자 관리
```http
GET /api/admin/users
Authorization: Bearer {admin_token}
```

### 경매 관리
```http
GET /api/admin/auctions
Authorization: Bearer {admin_token}
```

### 경매 승인/거부
```http
PUT /api/admin/auctions/{id}/approve
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "status": "approved",
  "note": "승인 사유"
}
```

---

## 🔗 외부 API 연동

### 차량 정보 조회 (NiceDNR)
```http
POST /api/external/car-info
Authorization: Bearer {token}
Content-Type: application/json

{
  "car_number": "12가3456",
  "owner_name": "홍길동",
  "owner_birth": "19900101"
}
```

### 차량 시세 조회 (Carmerece)
```http
GET /api/external/car-price
Authorization: Bearer {token}
```

**쿼리 파라미터**:
- `model`: 차종
- `year`: 연식
- `mileage`: 주행거리

### SMS 발송
```http
POST /api/external/sms
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "phone": "01012345678",
  "message": "발송할 메시지",
  "type": "notification"
}
```

---

## ⚠️ 오류 처리

### 표준 오류 응답
```json
{
  "success": false,
  "message": "오류 메시지",
  "errors": {
    "field_name": ["구체적인 오류 설명"]
  },
  "code": "ERROR_CODE"
}
```

### 주요 오류 코드

| HTTP Status | 코드 | 설명 |
|-------------|------|------|
| 400 | INVALID_REQUEST | 잘못된 요청 |
| 401 | UNAUTHORIZED | 인증 실패 |
| 403 | FORBIDDEN | 권한 없음 |
| 404 | NOT_FOUND | 리소스 없음 |
| 422 | VALIDATION_ERROR | 유효성 검사 실패 |
| 429 | RATE_LIMIT_EXCEEDED | 요청 한도 초과 |
| 500 | INTERNAL_ERROR | 서버 오류 |

### 유효성 검사 오류 예시
```json
{
  "success": false,
  "message": "입력값을 확인해주세요",
  "errors": {
    "user.email": ["이메일 형식이 올바르지 않습니다"],
    "user.password": ["비밀번호는 8자 이상이어야 합니다"]
  }
}
```

---

## 📊 API 성능 지표

### 응답 시간 기준
- **일반 조회**: < 1초
- **대량 데이터**: < 3초
- **파일 업로드**: < 10초

### Rate Limiting
- **API 호출**: 분당 100회
- **로그인 시도**: 분당 5회
- **파일 업로드**: 분당 10회

---

## 🔒 보안 고려사항

### API 보안
- ✅ HTTPS 강제 사용
- ✅ API 토큰 기반 인증
- ✅ Rate Limiting 적용
- ✅ 입력값 유효성 검사
- ✅ SQL Injection 방어
- ⚠️ CSRF 보호 (테스트 환경에서만 우회)

### 데이터 보안
- ✅ 비밀번호 해시화
- ✅ 민감정보 마스킹
- ✅ 파일 업로드 제한
- ⚠️ 개인정보 암호화 (일부만 적용)

---

## 📚 참고 자료

- [Laravel 10 공식 문서](https://laravel.com/docs/10.x)
- [Laravel Sanctum 문서](https://laravel.com/docs/10.x/sanctum)
- [Spatie Permission 문서](https://spatie.be/docs/laravel-permission)

---

*본 문서는 2025-08-05 기준으로 작성되었으며, API 변경사항에 따라 지속적으로 업데이트됩니다.*