# 위카옥션 v2 - 서비스 클래스 및 비즈니스 로직 문서

**생성일**: 2025-08-05  
**Laravel 버전**: 10.x  
**아키텍처**: Service Layer Pattern

---

## 📋 목차

1. [서비스 아키텍처 개요](#서비스-아키텍처-개요)
2. [핵심 서비스 클래스](#핵심-서비스-클래스)
3. [외부 API 연동 서비스](#외부-api-연동-서비스)
4. [유틸리티 서비스](#유틸리티-서비스)
5. [Job 큐 시스템](#job-큐-시스템)
6. [서비스 간 의존성](#서비스-간-의존성)

---

## 🏗️ 서비스 아키텍처 개요

### 서비스 레이어 패턴
- **Controller**: HTTP 요청/응답 처리
- **Service**: 비즈니스 로직 구현
- **Model**: 데이터 액세스 레이어
- **Job**: 비동기 작업 처리

### CrudTrait 기반 공통 구조
대부분의 서비스는 `App\Traits\CrudTrait`를 사용하여 일관된 CRUD 패턴을 구현합니다.

```php
// 공통 메서드 구조
protected function beforeProcess($method, $request, $id = null)
protected function middleProcess($method, $request, $result, $id = null)  
protected function afterProcess($method, $request, $result, $id = null)
```

---

## 🎯 핵심 서비스 클래스

### 1. AuctionService
**파일**: `app/Services/AuctionService.php`  
**역할**: 경매 시스템의 핵심 비즈니스 로직

#### 주요 기능
- **경매 상태 관리**: ask → diagnosis → progress → completed → delivery → finished
- **권한 기반 액세스 제어**: 관리자, 딜러, 일반 사용자별 권한 분리
- **비동기 작업 연동**: Job을 통한 알림 발송 및 상태 변경
- **외부 API 통합**: 차량 정보, 시세, 탁송 서비스 연동

#### 주요 메서드
```php
// 경매 등록 전 권한 검증
protected function beforeProcess($method, $request, $id = null)

// 경매 상태별 데이터 필터링
protected function middleProcess($method, $request, $result, $id = null)

// 경매 완료 후 Job 실행
protected function afterProcess($method, $request, $result, $id = null)
```

#### 연동 Job 클래스
- `AuctionDiagJob` - 진단 완료 알림
- `AuctionIngJob` - 경매 시작 알림
- `AuctionDoneJob` - 낙찰 완료 알림
- `AuctionCancelJob` - 경매 취소 알림
- `TaksongAddJob` - 탁송 등록 알림

#### 상태 관리 플로우
```
ask (신청) → diagnosis (진단중) → progress (경매진행) 
    ↓
completed (낙찰완료) → delivery (배송중) → finished (거래완료)
    ↓
failed (유찰)
```

### 2. UserService
**파일**: `app/Services/UserService.php`  
**역할**: 사용자 관리 및 인증 로직

#### 주요 기능
- **회원가입 처리**: 일반/소셜 로그인 지원
- **역할 기반 권한 관리**: user, dealer, admin 역할 할당
- **데이터 검증**: 이메일, 전화번호, 비밀번호 유효성 검사
- **소셜 로그인 연동**: 카카오, 네이버, 구글

#### 핵심 메서드
```php
public function store(Request $request)
{
    DB::beginTransaction();
    try {
        $data = $request->input('user');
        $data['socialLogin'] = $data['socialLogin'] ?? false;
        $data['role'] = $data['role'] ?? 'user';
        
        // 사용자 생성 및 역할 할당
        $user = User::create($data);
        $user->assignRole($data['role']);
        
        // 회원가입 완료 Job 실행
        UserRegisteredJob::dispatch($user);
        
        DB::commit();
        return $user;
    } catch (\Exception $e) {
        DB::rollback();
        throw $e;
    }
}
```

### 3. BidService
**파일**: `app/Services/BidService.php`  
**역할**: 입찰 관리 및 경매 참여 로직

#### 주요 기능
- **입찰 권한 관리**: 딜러만 입찰 가능
- **입찰 데이터 필터링**: 사용자별 입찰 내역 조회
- **실시간 입찰 상태 업데이트**: Job을 통한 비동기 처리

#### 권한별 조회 로직
```php
protected function middleProcess($method, $request, $result, $id = null)
{
    if (auth()->user()->hasPermissionTo('act.admin')) {
        // 관리자: 모든 입찰 조회
    } elseif (auth()->user()->hasPermissionTo('act.dealer')) {
        // 딜러: 본인 입찰만 조회 (완료된 것 제외)
        $result->where('user_id', auth()->user()->id)
               ->where(function ($query) {
                   $query->where('status', '!=', 'done')
                         ->orWhereNull('status');
               });
    } else {
        // 일반 사용자: 본인이 등록한 경매의 입찰만 조회
        $result->whereHas('auction', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }
}
```

### 4. DealerService
**파일**: `app/Services/DealerService.php`  
**역할**: 딜러 관리 및 승인 프로세스

#### 주요 기능
- **딜러 등록 신청 처리**
- **사업자 정보 검증**
- **승인/거부 상태 관리**
- **딜러 권한 부여**

---

## 🔗 외부 API 연동 서비스

### 1. NiceDNRService
**파일**: `app/Services/NiceDNRService.php`  
**역할**: 나이스디앤알 차량정보 조회

#### 기능
- 차량번호로 차량 정보 조회
- 소유자 정보 확인
- 차량 이력 조회

### 2. CarmerceService  
**파일**: `app/Services/CarmerceService.php`  
**역할**: 카머스 차량시세 조회

#### 기능
- 실시간 차량 시세 조회
- 모델별/연식별 시세 데이터
- 소매/도매 시세 구분

### 3. AligoService
**파일**: `app/Services/AligoService.php`  
**역할**: Aligo SMS 발송 서비스

#### 기능
- SMS/LMS 메시지 발송
- 템플릿 메시지 관리
- 발송 결과 추적

### 4. NiceApiService
**파일**: `app/Services/NiceApiService.php`  
**역할**: 나이스페이먼츠 결제 연동

#### 기능
- 결제 요청 처리
- 결제 상태 확인
- 결제 취소/환불

---

## 🛠️ 유틸리티 서비스

### 1. MediaService
**파일**: `app/Services/MediaService.php`  
**역할**: 파일 업로드 및 미디어 관리

#### 기능
- 이미지 업로드 및 리사이징
- 파일 저장소 관리 (Spatie MediaLibrary)
- 썸네일 생성

### 2. ConfigService
**파일**: `app/Services/ConfigService.php`  
**역할**: 시스템 설정 관리

### 3. KoreanHolidays
**파일**: `app/Services/KoreanHolidays.php`  
**역할**: 한국 공휴일 계산

#### 기능
- 공휴일 판정
- 영업일 계산
- 날짜 유틸리티

---

## ⚙️ Job 큐 시스템

### 경매 관련 Job
- `AuctionDiagJob` - 진단 완료 알림
- `AuctionIngJob` - 경매 시작 알림  
- `AuctionDoneJob` - 낙찰 완료 알림
- `AuctionCancelJob` - 경매 취소 알림
- `AuctionCohosenJob` - 낙찰자 선택 알림
- `AuctionBidStatusJob` - 입찰 상태 변경 알림

### 결제 관련 Job
- `AuctionTotalDepositJob` - 보증금 입금 알림
- `AuctionTotalAfterFeeJob` - 수수료 정산 알림
- `AuctionAfterFeeDonJob` - 수수료 지급 완료 알림
- `AuctionTotalDepositMissJob` - 보증금 미입금 알림

### 사용자 관련 Job
- `UserRegisteredJob` - 회원가입 완료 알림
- `UaerDealerStatusJob` - 딜러 상태 변경 알림

### 탁송 관련 Job
- `TaksongAddJob` - 탁송 등록 알림

---

## 🔄 서비스 간 의존성

### 주요 의존성 관계
```
AuctionService
├── NiceDNRService (차량정보 조회)
├── CarmerceService (시세 조회)  
├── MediaService (이미지 업로드)
├── TaksongService (탁송 처리)
└── [Multiple Jobs] (비동기 알림)

UserService
├── DealerService (딜러 등록)
├── AligoService (SMS 인증)
└── UserRegisteredJob (가입 알림)

BidService
├── AuctionService (경매 상태 확인)
└── AuctionBidStatusJob (입찰 알림)

PaymentService (추정)
├── NiceApiService (결제 처리)
├── AuctionService (낙찰 확인)
└── [Payment Jobs] (결제 알림)
```

### 트랜잭션 관리
대부분의 서비스는 데이터베이스 트랜잭션을 사용하여 데이터 무결성을 보장합니다.

```php
// 일반적인 트랜잭션 패턴
DB::beginTransaction();
try {
    // 비즈니스 로직 실행
    $result = $this->performBusinessLogic($data);
    
    // Job 큐 실행 (트랜잭션 완료 후)
    SomeJob::dispatch($result);
    
    DB::commit();
    return $result;
} catch (\Exception $e) {
    DB::rollback();
    Log::error('Service Error: ' . $e->getMessage());
    throw $e;
}
```

---

## 📈 성능 및 최적화

### 캐싱 전략
- **Redis**: 실시간 입찰 데이터 캐싱
- **Session**: 사용자별 임시 데이터
- **HTTP Cache**: 외부 API 응답 캐싱

### 비동기 처리
- **Queue**: 알림 발송, 이메일 전송
- **Job**: 무거운 연산 작업 분리
- **Event**: 상태 변경 시 자동 처리

### 로깅 및 모니터링
- **Log 채널**: 서비스별 로그 분리
- **Error Tracking**: 예외 상황 추적
- **Performance**: 응답 시간 모니터링

---

## 🔒 보안 고려사항

### 권한 기반 액세스 제어
```php
// 예시: 경매 조회 권한 검증
if (!auth()->user()->hasPermissionTo('view.auction')) {
    throw new UnauthorizedException('권한이 없습니다.');
}
```

### 데이터 검증
- **Request Validation**: Laravel Form Request 사용
- **Service Layer Validation**: 추가 비즈니스 규칙 검증
- **Database Constraints**: DB 레벨 무결성 보장

### API 보안
- **Rate Limiting**: 서비스별 호출 제한
- **Authentication**: Sanctum 토큰 기반 인증
- **Authorization**: 세밀한 권한 제어

---

## 🔧 개발 가이드라인

### 서비스 클래스 작성 규칙
1. **CrudTrait 사용**: 일관된 CRUD 패턴 적용
2. **트랜잭션 관리**: 데이터 무결성 보장
3. **예외 처리**: 명확한 에러 메시지와 로깅
4. **Job 분리**: 무거운 작업은 큐로 분리
5. **테스트 작성**: 서비스별 단위 테스트 필수

### 명명 규칙
- **서비스 클래스**: `{Domain}Service.php`
- **Job 클래스**: `{Domain}{Action}Job.php`
- **메서드명**: camelCase, 명확한 동작 표현

---

*본 문서는 2025-08-05 기준으로 작성되었으며, 서비스 구조 변경에 따라 지속적으로 업데이트됩니다.*