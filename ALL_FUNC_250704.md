# 위카옥션 v2 전체 기능 맵 (프론트→백엔드 시간흐름) - [ ] 보안 강화

> 최종 수정: 2025-07-25  
> 보안 취약점 검토 및 보완 버전 - [ ] 소스코드 전체 검토 완료  
> 기존 문서 백업: ALL_FUNC_250704_backup_20250725.md  

> 주의: 프론트엔드는 PHP Blade 뷰이므로 세션은 백엔드에서 관리됨. 별도의 토큰 저장 없음.  
> 개발환경에서 모든 알림은 mail (mailpit) 으로 사용. 실제로는 메일+알리고(알림톡) 으로 진행.

## 0. 별도 작업
- [ ] 결제 시스템 붙이기 : 나이스페이먼츠 신청완료
  - [ ] 가상계좌(벌크채번 -> API발급 가능한지 : 미뤄도됨)
  - [ ] 에스크로 
- [ ] 8월중
  - [ ] 외부 API 확인 (테스트 완료되었으나 서비스 연기 중)
    - [ ] 나이스DNR 차량정보 / 코드에프 주민번호로 사업자여부조회
- [ ] 알리고 연동 (템플릿 등록 및 알리고 API)

## 1. 인증 및 회원 관리

### 1-1. 로그인
- [ ] 로그인 페이지 접속 (`GET /v2/login`)
- [ ] 이메일/비밀번호 입력
- [ ] 소셜 로그인 선택 (카카오/네이버)
- [ ] 로그인 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /v2/login` 폼 제출
  - [ ] AuthenticatedSessionController@login 호출
  - [ ] 사용자 상태 확인
    - [ ] ask: "현재 심사중입니다" 에러
    - [ ] warning1: "3일 이용금지" 에러 (최초로그인 시 penalty_until 할당)
    - [ ] warning2: "30일 이용금지" 에러 (최초로그인 시 penalty_until 할당)
    - [ ] expulsion: "영구정지" 에러
  - [ ] penalty_until 필드가 있고, 패널티 기간이 설정되어 있는 경우 확인
    - [ ] 패널티 기간이 아직 지나지 않은 경우 로그인 제한
  - [ ] 비밀번호 검증
  - [ ] 🟡 **보안취약점**: 세션 고정 공격 위험 (session()->regenerate() 누락)
  - [ ] 세션 생성 및 리다이렉트

### 1-2. 회원가입
- [ ] 회원가입 페이지 접속 (`GET /v2/register`)
  - [ ] `register.blade` 는 모든 회원가입폼이 유저,딜러,관리자 / 사용자모드,관리자모드 분기에 따라 갈린다.
  - [ ] 🔴 **XSS취약점**: `resources/views/v2/partials/common.blade.php:14-16`
    ```blade
    window.userId = '{!! $userId !!}'; // 이스케이프 안됨
    // 수정필요: window.userId = @json($userId);
    ```
- [ ] 회원 타입 선택 (일반/딜러)
- [ ] 기본정보 입력 (이름, 전화번호, 이메일, 비밀번호)
- [ ] 딜러인 경우 추가정보 입력
  - [ ] 주소, 사업자번호, 대표자명 등 현재 필수요소
  - [ ] 파일: 사진, 사업자등록증, 매도용인감증명서, 위임장
  - [ ] 🟡 **보안취약점**: 파일 업로드 검증 부족 (`components/forms/fileUpload.blade.php`)
    ```blade
    accept="*/*" <!-- [ ] 모든 파일 타입 허용 -->
    <!-- [ ] 수정필요: 허용 확장자 명시, 서버 사이드 MIME 타입 검증 -->
    ```
- [ ] 약관 동의 체크
- [ ] 가입 버튼 클릭
- [ ] **🔧 백엔드 처리**: FormData로 전체 데이터 수집
  - [ ] `POST /api/users` 요청
  - [ ] Api\UserController@store 호출
  - [ ] 🔴 **Mass Assignment 취약점**: `app/Models/User.php:45`
    ```php
    protected $guarded = []; // 모든 필드 할당 가능
    // 수정필요: protected $fillable = ['name', 'email', 'phone', ...];
    ```
  - [ ] 발리데이션 : 중복 체크 (전화번호, 이메일)
  - [ ] 파일 업로드 처리
  - [ ] User 생성 (status: 'ask')
  - [ ] 역할 부여 (user/dealer)
  - [ ] 알림: UserStartJob (관리자에게 가입 승인 요청)
  - [ ] 🔴 **하드코딩 취약점**: 알림 발송 시 관리자 ID 하드코딩
    ```php
    // AuctionStartJobAdmin - [ ] User::find(2) 하드코딩
    // 수정필요: config('app.admin_user_id') 또는 role 기반 조회
    ```

### 1-3. 소셜 로그인 
- [ ] 소셜 로그인 버튼 클릭 (카카오/네이버)
- [ ] **🔧 백엔드 처리**: `GET /auth/{provider}/redirect`
  - [ ] SocialLoginController@redirect 호출
  - [ ] OAuth 인증 페이지로 리다이렉트
  - [ ] 사용자 동의 후 콜백
  - [ ] `GET /auth/{provider}/callback`
  - [ ] SocialLoginController@callback 호출
  - [ ] 소셜 사용자 정보 획득
  - [ ] UserSns 테이블에서 기존 회원 확인
  - [ ] 기존 회원: 자동 로그인 처리
  - [ ] 🟡 **보안취약점**: 세션에 social_user 저장 시 암호화 없음
  - [ ] 신규: 세션에 social_user 저장 후 회원가입 페이지로
    - [ ] 회원가입 시 socialLogin=true로 비밀번호 자동생성

### 1-4. 비밀번호 재설정
- [ ] 비밀번호 찾기 클릭 (`GET /v2/forgot-password`)
- [ ] 전화번호 입력
- [ ] 재설정 요청 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /api/users/reset-password-link`
  - [ ] Api\UserController@resetPasswordLink 호출
  - [ ] 전화번호로 사용자 확인
  - [ ] 3시간 유효 암호화 링크 생성 (AES-256)
  - [ ] 알림: ResetPasswordLinkJob (SMS 발송)
    - [ ] Aligo API로 링크 전송
- [ ] 사용자가 SMS 링크 클릭
- [ ] **🔧 백엔드 처리**: `GET /v2/reset-password/{token}`
  - [ ] 토큰 복호화 및 검증
  - [ ] 새 비밀번호 입력 폼 표시
- [ ] **🔧 백엔드 처리**: `POST /v2/reset-password`
  - [ ] 비밀번호 업데이트 및 로그인

## 2. 차량 판매 (Sell)

### 2-1. 차량 조회
- [ ] 판매 메인 페이지 접속 (`GET /v2/sell`)
- [ ] 소유자명, 차량번호 입력
- [ ] 조회 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /v2/sell/result` 폼 제출
  - [ ] SellController@result 호출
  - [ ] 세션에 owner_name, car_no 저장
  - [ ] result.blade.php로 리다이렉트
  - [ ] 페이지 로드 시 차량정보 API 호출
  - [ ] 🟡 **보안취약점**: API 호출 시 Rate Limiting 없음 (무제한 호출 가능)
- [ ] **🔧 백엔드 처리**: `POST /api/auctions/car-info`
  - [ ] AuctionController@carInfo 호출
  - [ ] NiceDNRService 호출
    - [ ] 차량 기본정보 조회 (제조사, 모델, 연식 등)
    - [ ] 캐시 확인 (1일 1회 제한)
    - [ ] 🔴 **API키 노출**: `.env.example:30` 실제 API 키 노출
      ```bash
      KOR_HOLIDAY_API_KEY=lO445ftLXRJe8hl9EE6uNu86Jwm5jXS+zuiBgFAT/rRu8t/TUYkBShXhUtXYkMZf5BKrl8JX2917Wml6uxdPHQ==
      # 수정필요: 더미값으로 교체
      ```
  - [ ] CarmerceService 호출
    - [ ] 도매/소매 시세 조회
  - [ ] 중복 경매 체크 (status != 'cancel')
  - [ ] 🟡 **보안취약점**: 민감한 차량정보 로깅 (개인정보 포함)
  - [ ] 결과 반환

### 2-2. 판매 신청
- [ ] 예상가 측정 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /api/auctions/check-expected-price`
  - [ ] AuctionController@CheckExpectedPrice 호출
  - [ ] 감가상각 계산 (연식별 비율)
  - [ ] 옵션 반영
    - [ ] 사고이력: -300만원
    - [ ] 키 개수: 1개 -50만원
    - [ ] 휘 손상: -30만원
    - [ ] 광택 작업: -20만원
  - [ ] 예상가격 반환
- [ ] 판매 신청 버튼 클릭
  - [ ] apply.blade.php로 이동 (세션 데이터 전달)
  - [ ] 🔴 **XSS취약점**: DOM 기반 XSS (`auctionRegisterForm.blade.php:87`)
    ```blade
    x-html="isLoading ? '<span class=\'spinner-border spinner-border-sm me-2\'></span>처리 중...' : '공매 신청하기'"
    // 수정필요: x-text 사용 또는 안전한 HTML 처리
    ```
- [ ] 본인인증 모달 오픈
- [ ] **🔧 백엔드 처리**: 본인인증 API 호출
  - [ ] 🟡 **보안취약점**: 세션에 authenticated_owner 저장 시 변조 가능성
  - [ ] 세션에 authenticated_owner 저장
- [ ] 추가 정보 입력
  - [ ] 주소 (Daum 우편번호 API)
  - [ ] 진단 희망일 (datepicker)
  - [ ] 계좌정보 (은행 선택 모달)
  - [ ] 파일 업로드 (자동차등록증 필수)
- [ ] 판매 신청 제출
- [ ] **🔧 백엔드 처리**: FormData 생성 (모든 필드 + 파일)
  - [ ] `POST /v2/sell/apply`
  - [ ] SellController@apply 호출
  - [ ] 세션 본인인증 확인 필수
  - [ ] 파일 업로드 (MediaLibrary)
  - [ ] Auction 생성
    - [ ] status: 'ask'
    - [ ] user_id: 비로그인 시 null
  - [ ] 알림 발송:
    - [ ] AuctionStartJob: 고객에게 등록 완료 알림
    - [ ] AuctionStartJobAdmin: 관리자에게 신규 경매 알림 (🔴 User::find(2) 하드코딩)
    - [ ] AuctionDiagJobAdmin: 진단팀에게 진단 요청

## 3. 경매 시스템

### 3-1. 경매 목록 조회  
- [ ] 경매 목록 페이지 접속 (`GET /v2/auction`)
- [ ] 카테고리 탭 선택 (전체/진행중/종료)
- [ ] 검색 조건 입력
- [ ] **🔧 백엔드 처리**: API 호출로 경매 목록 로드
  - [ ] 🟡 **보안취약점**: 인증 없는 민감한 정보 접근 (`routes/api.php:107-108`)
  - [ ] 상태별 필터링:
    - [ ] 진행중: status = 'ing'
    - [ ] 종료: status in ('chosen', 'dlvr', 'done')
  - [ ] 페이지네이션 처리
  - [ ] 각 경매 카드에 표시:
    - [ ] 남은 시간 또는 종료 시간
    - [ ] 현재 입찰가
    - [ ] 입찰 수

### 3-2. 경매 상세 조회
- [ ] 경매 아이템 클릭
- [ ] **🔧 백엔드 처리**: `GET /v2/auction/{id}`
  - [ ] AuctionController@show 호출
  - [ ] 경매 정보 with 관계 로드
  - [ ] 사용자 역할별 뷰 분기:
    - [ ] guest: 제한된 정보만 표시
    - [ ] user: 판매자용 상태 표시
    - [ ] dealer: 입찰 가능 UI
  - [ ] 상태별 컴포넌트:
    - [ ] ask: "심사중" 표시
    - [ ] diag: "진단중" + 진단예정일
    - [ ] ing: 입찰 폼 + 현재 최고가 + 남은시간
    - [ ] wait: "낙찰자 선택 대기중"
    - [ ] chosen: 결제 대기 또는 결제 완료
    - [ ] dlvr: 탁송 정보
    - [ ] done: 거래 완료

### 3-3. 입찰 (딜러만)
- [ ] 딜러 로그인 상태에서 경매 상세 페이지
- [ ] 입찰가 입력 (현재가보다 높아야 함)
- [ ] 입찰 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /api/bids`
  - [ ] BidController@store 호출
  - [ ] 🟡 **보안취약점**: Rate Limiting 없음 (무제한 입찰 가능)
  - [ ] BidService 처리:
    - [ ] 경매 상태 확인 (ing만 가능)
    - [ ] 최소 입찰가 검증
    - [ ] 기존 입찰 여부 확인
  - [ ] Bid 생성 또는 업데이트
  - [ ] Auction의 current_bid_price 갱신
  - [ ] 🟡 **보안취약점**: 민감한 입찰 정보 로깅
  - [ ] 알림: AuctionBidStatusJobAsk
    - [ ] 판매자에게 "새로운 입찰가 {price}원" 알림

### 3-4. 경매 종료 및 낙찰자 선택
- [ ] 경매 종료 시간 도래 (스케줄러 또는 관리자)
> 상태 변경: ing → wait
> 판매자에게 선택 요청 알림
- [ ] 판매자가 낙찰자 선택
> 상태 변경: wait → chosen  
> 알림 발송:
  - [ ] AuctionCohosenJobUser: 판매자에게 낙찰 알림
  - [ ] AuctionCohosenJobDealer: 딜러에게 낙찰 알림
  - [ ] 미선택 딜러들에게 탈락 알림

### 3-5. 결제 처리
- [ ] 낙찰 딜러가 결제 페이지 접속
> `GET /payment/form`
> PaymentController@showPaymentForm
> Bid 정보 확인
> 가상계좌 만료일 설정 (2주)
> 🔴 **하드코딩 취약점**: 테스트 가격 100원 하드코딩
  ```php
  $amount = 100; // 테스트용 하드코딩
  // 수정필요: 환경변수로 테스트/운영 모드 분리
  ```
- [ ] 결제 진행
> 나이스페이 폼 제출
> `POST /payment/request`
> PaymentController@requestPayment  
> SHA-256 서명 검증
> 결제 승인 API 호출
- [ ] PG사 웹훅 수신
> `POST /payment/notify`
> WebhookController@paymentNotify
> 🟡 **보안취약점**: 웹훅 검증 부족 (IP 화이트리스트 없음)
> 결제 정보 DB 저장 (auctions_payments)
> 알림: AuctionTotalDepositJob
  - [ ] 판매자: "차량대금 입금 확인"
  - [ ] 딜러: "차량대금 입금 확인, 탁송지 입력 필요"
  - [ ] 탁송팀: 탁송 준비 알림

### 3-6. 탁송 및 명의이전
- [ ] 딜러가 탁송지 정보 입력
- [ ] **🔧 백엔드 처리**: 상태 변경: chosen → dlvr
  - [ ] 탁송 진행
- [ ] 탁송 완료 후 명의이전
- [ ] **🔧 백엔드 처리**: 알림: TaksongNameChangeJob
  - [ ] 판매자/딜러에게 "탁송 완료, 명의이전 진행" 알림
- [ ] 명의이전 서류 업로드
- [ ] **🔧 백엔드 처리**: `POST /api/auctions/name-change-file-upload`
  - [ ] AuctionController@nameChangeFileUpload 호출
  - [ ] 파일 업로드 (MediaLibrary)
  - [ ] 상태 변경: dlvr → done
  - [ ] 알림 발송:
    - [ ] AuctionDoneJobUser: 판매자에게 "거래 완료" 알림
    - [ ] AuctionDoneJobDealer: 딜러에게 "수수료 안내" 알림
    - [ ] TaksongNameChangeFileUploadJob: 명의이전 서류 업로드 확인

### 3-7. 경매 취소
- [ ] 관리자 또는 판매자가 경매 취소
- [ ] **🔧 백엔드 처리**: 상태 변경: * → cancel
  - [ ] 알림: AuctionCancelJob
    - [ ] 모든 입찰자에게 취소 알림
    - [ ] 판매자에게 취소 확인

## 4. 게시판 시스템

### 4-1. 게시글 목록
- [ ] 게시판 접속 (`GET /v2/board/{boardId}`)
- [ ] **🔧 백엔드 처리**: BoardController@index 호출
  - [ ] 스킨별 list.blade.php 렌더링
  - [ ] Alpine.js articleList 컴포넌트
  - [ ] 카테고리 필터
  - [ ] 검색 (제목, 내용)
  - [ ] 정렬 (작성일, 조회수)
- [ ] **🔧 백엔드 처리**: API로 게시글 목록 로드
  - [ ] 페이지네이션 처리

### 4-2. 게시글 작성
- [ ] 글쓰기 버튼 클릭
- [ ] **🔧 백엔드 처리**: `GET /v2/board/{boardId}/form`
  - [ ] BoardController@form 호출
  - [ ] 작성 권한 확인:
    - [ ] 리뷰(3): 판매자만, 경매 완료 14일 이내
    - [ ] 클레임(4): 낙찰 딜러만, 경매 완료 14일 이내
  - [ ] 리뷰/클레임의 경우 경매 선택 필수
- [ ] 내용 작성 (제목, 내용, 카테고리)
- [ ] 파일 첨부
- [ ] 등록 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /api/articles`
  - [ ] ArticleController@store 호출
  - [ ] 🟡 **보안취약점**: 입력값 검증 부족 (XSS 방지 필터 미흡)
  - [ ] 유효성 검사
  - [ ] Article 생성
  - [ ] 파일 업로드 (MediaLibrary)
  - [ ] 목록으로 리다이렉트

### 4-3. 게시글 조회
- [ ] 게시글 클릭
- [ ] **🔧 백엔드 처리**: `GET /v2/board/{boardId}/view/{articleId}`
  - [ ] BoardController@view 호출
  - [ ] 조회수 증가
  - [ ] view.blade.php 렌더링
  - [ ] 🔴 **XSS취약점**: 게시글 내용 x-html 사용
  ```blade
  <!-- [ ] view.blade.php:155 -->
  <div x-html="(article && article.content) || ''"></div>
  <!-- [ ] 수정필요: 서버에서 이스케이프 처리 또는 x-text 사용 -->
  ```
  - [ ] 🔴 **정보노출**: 사용자 정보 직접 출력
    ```blade
    <!-- [ ] view.blade.php:24 -->
    window.user = {!! auth()->user()->toJson() !!};
    <!-- [ ] 수정필요: 필요한 정보만 @json() 사용 -->
    ```
  - [ ] 댓글 목록 로드

### 4-4. 댓글 작성
- [ ] 댓글 입력
- [ ] 등록 버튼 클릭
- [ ] **🔧 백엔드 처리**: `POST /api/comments`
  - [ ] CommentController@store 호출
  - [ ] 댓글 생성
  - [ ] 실시간 댓글 추가

## 5. 관리자 시스템

### 5-1. 관리자 로그인
- [ ] 관리자 계정으로 로그인
- [ ] **🔧 백엔드 처리**: 권한 확인 (hasRole('admin'))
  - [ ] 🔴 **하드코딩 취약점**: hasPermissionTo('act.admin') 체크 시 User::find(2) 의존
  - [ ] 관리자 대시보드 접근

### 5-2. 경매 관리
- [ ] 관리자 경매 목록 (`GET /v2/admin/auction`)
- [ ] **🔧 백엔드 처리**: 전체 경매 표시 (모든 상태)
  - [ ] 검색 및 필터링
- [ ] 경매 상태 변경
- [ ] **🔧 백엔드 처리**: 진단 완료: ask → diag
  - [ ] 알림: AuctionDiagJob (고객에게 진단 대기 알림)
- [ ] **🔧 백엔드 처리**: 진단 결과 등록: diag → ing
  - [ ] 알림: AuctionIngJob (고객에게 경매 시작 알림)
- [ ] **🔧 백엔드 처리**: 경매 종료: ing → wait
  - [ ] 알림: AuctionBidStatusJobWait (선택 대기)

### 5-3. 회원 관리  
- [ ] 회원 목록 조회
- [ ] 회원 상태 변경
- [ ] **🔧 백엔드 처리**: ask → ok: 가입 승인
- [ ] **🔧 백엔드 처리**: warning1/2: 경고 처리 (3일/30일 제재)
- [ ] **🔧 백엔드 처리**: expulsion: 영구 정지
  - [ ] 상태변경 알림
- [ ] 딜러 정보 관리
  - [ ] 사업자등록증, 서류 확인

### 5-4. 게시판 관리
- [ ] 공지사항 작성/수정
- [ ] 게시글 삭제
- [ ] 댓글 관리

## 6. 주요 서비스 클래스 (상세)

### AuctionService (`app/Services/AuctionService.php`)
경매 관련 핵심 비즈니스 로직 담당
- [ ] **상태 관리**: 경매 라이프사이클 관리 (진단→진행→낙찰→배송→완료)
- [ ] **비동기 처리**: Queue를 통한 알림 발송
- [ ] **외부 API**: 차량시세, 본인인증 등 연동
- [ ] **유효성 검사**: 상태 변경 시 엄격한 조건 검사

### UserService (`app/Services/UserService.php`)
회원 관리 및 인증 로직
- [ ] 회원가입/로그인 처리
- [ ] 소셜 로그인 연동
- [ ] 권한 관리

### CarHistoryService (`app/Services/CarHistoryService.php`)
차량 이력 조회 및 관리 서비스
- [ ] 차량 등록/검사 이력 관리
- [ ] 사고 이력 조회
- [ ] 차량 상태 변경 추적

### CodefService (`app/Services/CodefService.php`)
개인사업자 확인 API 서비스
- [ ] 사업자등록번호 진위 확인
- [ ] 개인사업자 정보 조회
- [ ] 🔴 **보안취약점**: 클라이언트 시크릿 하드코딩

### OwnershipService (`app/Services/OwnershipService.php`)
명의이전 관리 서비스
- [ ] 명의이전 진행 상황 추적
- [ ] 필수 서류 확인
- [ ] 독촉 알림 자동 발송 (`sendOwnershipAlertsAuto`)

### TaksongService (`app/Services/TaksongService.php`)
탁송 서비스 통합 관리
- [ ] 탁송 업체 API 연동
- [ ] 탁송 상태 실시간 추적
- [ ] 탁송비 계산 및 정산

### ApiRequestService (`app/Services/ApiRequestService.php`)
통합 API 요청 서비스
- [ ] 외부 API 호출 통합 관리
- [ ] 요청/응답 로깅
- [ ] 에러 처리 및 재시도 로직

### MediaService (`app/Services/MediaService.php`)
파일 업로드/다운로드 서비스
- [ ] Spatie MediaLibrary 래퍼
- [ ] 파일 변환 및 썸네일 생성
- [ ] 🟡 **보안취약점**: 파일 검증 부족

## 7. 추가 컨트롤러 및 API

### WebhookController (`app/Http/Controllers/Api/WebhookController.php`)
외부 웹훅 수신 처리
- [ ] 결제 시스템 웹훅 (`paymentNotify`)
- [ ] 탁송 상태 웹훅
- [ ] 🔴 **보안취약점**: IP 화이트리스트 및 서명 검증 없음

### OwnershipController (`app/Http/Controllers/OwnershipController.php`)
명의이전 관리
- [ ] 명의이전 진행 상황 조회
- [ ] 서류 업로드 및 확인
- [ ] 완료 상태 업데이트

### DiagController (`app/Http/Controllers/DiagController.php`)
진단 관리
- [ ] 진단 일정 관리
- [ ] 진단 결과 등록
- [ ] 진단 완료 처리

### LibController (`app/Http/Controllers/LibController.php`)
라이브러리 관리
- [ ] 공통 코드 관리
- [ ] 설정값 조회
- [ ] 참조 데이터 제공

## 8. 알림 시스템 (상세)

### 8-1. 경매 등록 알림
- [ ] **AuctionStartJob**: 고객에게 "경매 등록 완료" (SMS/이메일)
- [ ] **AuctionStartJobAdmin**: 관리자에게 "새 경매 등록" (이메일)
  - [ ] 🔴 **하드코딩 취약점**: User::find(2) 하드코딩
- [ ] **AuctionDiagJobAdmin**: 진단팀에게 "진단 요청" (이메일)

### 8-2. 상태 변경 알림
- [ ] **AuctionDiagJob**: ask→diag 시 "진단 대기중"
- [ ] **AuctionIngJob**: diag→ing 시 "경매 시작"
- [ ] **AuctionBidStatusJobAsk**: 새 입찰 시 판매자에게
- [ ] **AuctionBidStatusJobWait**: ing→wait 시 "선택 대기"
- [ ] **AuctionCohosenJobUser/Dealer**: 낙찰 알림
- [ ] **AuctionCancelJob**: 취소 시 모든 입찰자

### 8-3. 결제/탁송 알림
- [ ] **AuctionTotalDepositJob**: 차량대금 입금 확인
- [ ] **TaksongNameChangeJob**: 탁송 완료 알림
- [ ] **AuctionDoneJobUser/Dealer**: 거래 완료 알림
- [ ] **TaksongNameChangeFileUploadJob**: 명의이전 서류 업로드

### 8-4. 시스템 알림
- [ ] **UserStartJob**: 신규 회원가입 (관리자)
- [ ] **ResetPasswordLinkJob**: 비밀번호 재설정 링크

### 8-5. 알림 채널
- [ ] **SMS/알림톡**: Aligo API
- [ ] **이메일**: Laravel Mail (Queue)
- [ ] **템플릿**: NotificationTemplate::getTemplate()

### 8-6. 누락된 알림 기능
- [ ] ❌ 진단 완료 알림
- [ ] ❌ 입금 독촉 알림
- [ ] ❌ 경매 마감 임박 알림
- [ ] ❌ 선택 시간 초과 알림
- [ ] ❌ 알림 수신 설정 기능

## 9. 외부 API 연동

### 9-1. 나이스 DNR (차량정보)
- [ ] API 호출: NiceDNRService::getCarInfo()
- [ ] 입력: 소유자명, 차량번호
- [ ] 반환 정보:
  - [ ] 제조사, 모델명, 연식
  - [ ] 차량형식, 연료, 배기량
  - [ ] 최초등록일, 검사유효일
- [ ] 캐시: 24시간
- [ ] 🔴 **API키 노출**: `.env.example`에 실제 키 노출

### 9-2. 카머스 (시세정보)
- [ ] API 호출: CarmerceService::getPrice()
- [ ] 입력: 모델코드, 연식, 세부모델
- [ ] 반환 정보:
  - [ ] min_price (최저가)
  - [ ] max_price (최고가)
  - [ ] market_price (시장가)
- [ ] 캐시: 24시간

### 9-3. 나이스페이 (결제)
- [ ] 결제 요청: SHA-256 서명
- [ ] 가상계좌:
  - [ ] 유효기간: 14일
  - [ ] 입금 확인: 웹훅
- [ ] 신용카드: 즉시 결제
- [ ] 취소/환불 API 지원
- [ ] **차량대금**은 가상계좌 + 에스크로 > 고객에게
- [ ] **성공수수료**는 가상계좌 > 회사계좌

### 9-4. 본인인증
- [ ] 휴대폰 본인인증 API
- [ ] 세션 저장: authenticated_owner
- [ ] 유효기간: 세션 유지 시간
- [ ] 차량 판매 신청 시 필수

### 9-5. Aligo (SMS/알림톡)
- [ ] SMS 발송: AligoService::send()
- [ ] 알림톡: 템플릿 기반
- [ ] 대량 발송 지원
- [ ] 발송 결과 확인 API

## 10. 파일 관리

### 10-1. 파일 업로드
- [ ] Spatie MediaLibrary 사용
- [ ] 컨버전 설정:
  - [ ] thumb: 150x150
  - [ ] preview: 500x500
- [ ] 파일 타입:
  - [ ] 회원: 사진, 사업자등록증, 서류
  - [ ] 경매: 자동차등록증, 사진
  - [ ] 게시판: 첨부파일
- [ ] 🟡 **보안취약점**: 파일 타입/크기 제한 검증 미비

### 10-2. 파일 다운로드  
- [ ] 보안 다운로드 (`GET /files/download/{uuid}`)
- [ ] **🔧 백엔드 처리**: Media 모델에서 UUID로 파일 확인
  - [ ] 권한 체크 (소유자 또는 관리자)
  - [ ] 🟡 **보안취약점**: 현재 구현에서 권한 체크 부족 (`routes/web.php:49-56`)
    ```php
    // 인증 없이 파일 다운로드 가능한 상태
    // 수정필요: 소유자 또는 관리자 권한 체크 강화
    ```
  - [ ] 스트리밍 다운로드
  - [ ] 다운로드 로그 기록

### 10-3. 엑셀 다운로드
- [ ] 관리자 전용 (`GET /excelDown/{resource}`)
- [ ] 지원 리소스:
  - [ ] users: 회원 목록
  - [ ] auctions: 경매 목록
  - [ ] bids: 입찰 내역
  - [ ] payments: 결제 내역

## 11. 보안 및 권한

### 11-1. 인증 (Authentication)
- [ ] PHP 세션 기반 (Blade 뷰)
- [ ] API용 Sanctum 토큰 (v1 호환)
- [ ] 로그인 후 세션에 사용자 정보 저장

### 11-2. 인가 (Authorization)
- [ ] Spatie Permission
- [ ] role:
  - [ ] 뷰에서 @can, @hasrole 사용
  - [ ] 종류
    - [ ] user: 일반 회원 (판매자)
    - [ ] dealer: 딜러 (구매자)
    - [ ] admin: 관리자
- [ ] permission
    - [ ] act.admin
    - [ ] act.dealer
    - [ ] act.user

### 11-3. CSRF 보호
- [ ] 모든 POST 요청에 @csrf
- [ ] 🟡 **보안취약점**: 로컬 환경 예외 과다 (`app/Http/Middleware/VerifyCsrfToken.php`)
  ```php
  // 로컬 환경에서 과도한 예외 설정
  $this->except = ['api/*', 'login', 'register'];
  // 수정필요: login, register는 CSRF 보호 유지
  ```

### 11-4. 보안 취약점 (발견된 주요 이슈)

#### 🔴 심각도: 높음 (즉시 수정 필요)
- [ ] **Mass Assignment**: User/Auction/Dealer 모델 `$guarded = []`
- [ ] **하드코딩 ID**: User::find(2) 다수 위치
- [ ] **XSS 취약점**: Blade 템플릿 `{!! !!}` 및 x-html 사용
- [ ] **API 키 노출**: .env.example에 실제 키 노출
- [ ] **SQL Injection 위험**: orWhere 로그인 로직
- [ ] **웹훅 검증 부족**: `app/Http/Controllers/Api/WebhookController.php`
  ```php
  // IP 화이트리스트 없음, 서명 검증 없음, 무제한 요청 허용
  // 수정필요: IP 제한, 서명 검증, Rate Limiting 적용
  ```
- [ ] **스케줄러 인증 취약점**: `app/Console/Kernel.php:33-34`
  ```php
  $admin = User::where('id', 1)->first();
  Auth::login($admin); // 하드코딩된 관리자 로그인
  // 수정필요: config('app.scheduler_admin_id') 사용
  ```

#### 🟡 심각도: 중간
- [ ] **파일 업로드 검증 부족**
- [ ] **Rate Limiting 부족**: API 엔드포인트들 전반적으로 누락
- [ ] **세션 보안 설정 미흡**: regenerateToken() 누락
- [ ] **로깅 민감정보**: 차량번호, 개인정보 등 평문 로깅
- [ ] **테스트용 하드코딩**: PaymentController 결제 금액 100원 고정

## 12. 개발/배포 환경

### 12-1. 개발 환경
- [ ] Laravel Sail (Docker)
- [ ] 컨테이너:
  - [ ] PHP 8.2 + Laravel 10
  - [ ] MySQL 8.0  
  - [ ] Redis (큐/캐시)
  - [ ] Mailhog (메일 테스트)
- [ ] 명령어:
  ```bash
  ./vendor/bin/sail up -d
  ./vendor/bin/sail artisan migrate
  ./vendor/bin/sail artisan queue:work
  ```

### 12-2. Queue 설정
- [ ] ⚠️ 현재: QUEUE_CONNECTION=sync
- [ ] 운영: database 또는 redis 변경 필요
- [ ] Job 테이블: jobs, failed_jobs
- [ ] 재시도: 3회, 대기시간 90초
- [ ] 🟡 **보안취약점**: 동기 처리로 인한 성능 저하 및 장애 전파 위험

### 12-3. 테스트
- [ ] 테스트 커버리지 부족
- [ ] 필요한 테스트:
  - [ ] 회원가입/로그인
  - [ ] 경매 등록/입찰
  - [ ] 결제 프로세스
  - [ ] 알림 발송

## 13. 스케줄링 작업 (Cron)

### 13-1. 매분 실행
- [ ] **진단 완료 체크** (DiagService::diagnosticCheck)
  - [ ] status='diag' 경매의 진단 완료 여부 확인
  - [ ] 완료 시 status='ing'로 변경
  
- [ ] **탁송 상태 체크** (TaksongStatusJob)
  - [ ] chosen/dlvr 상태 경매의 탁송 API 호출
  - [ ] 탁송 상태 업데이트
  
- [ ] **경매 종료 처리** (AuctionService::auctionFinalAtUpdate)
  - [ ] ing 상태에서 final_at 지난 경매
  - [ ] status='wait'로 변경 (선택 마감 2일)
  - [ ] 알림: 판매자에게 선택 요청

### 13-2. 매시간 실행
- [ ] **차량대금 미입금 체크** (AuctionService::auctionTotalDepositMiss)
  - [ ] dlvr 상태 + 탁송 ask 상태
  - [ ] 탁송희망일 2시간 전 알림
  
- [ ] **경매 자동 취소** (AuctionService::auctionCancel)
  - [ ] wait 상태에서 선택 마감일 초과
  - [ ] bid_id 없으면 status='cancel'
  - [ ] 알림: 모든 입찰자에게 취소 알림
  
- [ ] **명의이전 확인** (ownership:check 커맨드)
  - [ ] dlvr/done 상태 경매
  - [ ] 명의이전 API 호출하여 완료 확인

### 13-3. 매일 오전 9시 실행
- [ ] **수수료 처리** (AuctionService::auctionAfterFeeDone)
  - [ ] done 상태 + 차량대금 결제 완료
  - [ ] 딜러에게 수수료 안내
  
- [ ] **명의이전 독촉** (OwnershipService::sendOwnershipAlertsAuto)
  - [ ] dlvr/done 상태 + 명의이전 미완료
  - [ ] 구매자/판매자에게 독촉 알림

### 13-4. 스케줄러 설정
```bash
# crontab 설정 필요
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 14. 주요 프로세스 요약

### 14-1. 회원 프로세스
1. **가입**: status='ask' → 관리자 승인 → status='ok'
2. **로그인**: 세션 생성, 상태 체크
3. **제재**: warning1(3일), warning2(30일), expulsion(영구)

### 14-2. 경매 프로세스
1. **등록**: ask (심사대기)
2. **진단**: ask → diag → ing (경매시작)
3. **입찰**: 딜러만 가능, 최고가 갱신
4. **종료**: ing → wait (선택대기)
5. **낙찰**: wait → chosen
6. **결제**: 가상계좌/카드
7. **탁송**: chosen → dlvr
8. **완료**: dlvr → done

### 14-3. 알림 타이밍
- [ ] **즉시**: 상태 변경 시
- [ ] **예약**: 마감 임박, 입금 독촉 (미구현)
- [ ] **배치**: 통계, 요약 (미구현)

---

## 🚨 즉시 수정 필요 (우선순위 순)

### Phase 1: 심각한 보안 취약점 (즉시)
1. **Mass Assignment 취약점 수정**
   ```php
   // 모든 모델에서 수정 필요
   // app/Models/User.php, Auction.php, Dealer.php
   protected $guarded = []; // 현재
   protected $fillable = ['field1', 'field2', ...]; // 수정
   ```

2. **API 키 노출 문제 해결**
   ```bash
   # .env.example 파일에서 실제 키값 제거
   KOR_HOLIDAY_API_KEY=your_api_key_here
   TAKSONG_API_KEY=your_taksong_key_here
   ```

3. **XSS 취약점 수정**
   ```blade
   <!-- [ ] Blade 템플릿 수정 -->
   {!! $data !!} → @json($data) 또는 {{ $data }}
   x-html → x-text (HTML 불필요시)
   ```

4. **하드코딩된 값 제거**
   ```php
   User::find(2) → config('app.admin_user_id')
   $amount = 100 → env('TEST_PAYMENT_AMOUNT', 100)
   ```

### Phase 2: 중요한 기능 개선 (1주일 내)
1. **Queue 설정 변경**: sync → database/redis
2. **파일 업로드 검증 강화**: MIME 타입, 크기 제한
3. **Rate Limiting 적용**: API 호출 제한
4. **CSRF 보호 재검토**: 필수 엔드포인트 보호
5. **세션 보안 강화**: regenerate(), 암호화 설정

### Phase 3: 시스템 안정화 (2주일 내)
1. **에러 처리 표준화**: 사용자 친화적 메시지
2. **로깅 정책 개선**: 민감정보 제외
3. **테스트 커버리지 확대**: 핵심 기능 테스트
4. **알림 시스템 보완**: 누락된 알림 추가
5. **모니터링 시스템 구축**: 보안 이벤트 감지

---

## 🚀 Livewire 도입 제안사항 (시간 단축)

### 🎯 Livewire 도입으로 개발 속도 향상 가능 영역

#### 1. 경매 실시간 기능 (최우선 추천)
**현재**: Alpine.js + API 폴링/웹소켓  
**Livewire 적용 시**: 자동 실시간 업데이트
```php
// LivewireComponent로 간단 구현
class AuctionLive extends Component {
    public $auction;
    
    #[On('bid-updated')]
    public function refreshBids() {
        $this->auction->refresh();
    }
    
    public function render() {
        return view('livewire.auction-live');
    }
}
```
**예상 시간 단축**: 3-4일 → 1일 (75% 단축)

#### 2. 입찰 폼 및 검증 (높은 우선순위)
**현재**: 복잡한 JavaScript 검증 + API 호출  
**Livewire 적용 시**: 서버사이드 실시간 검증
```php
class BidForm extends Component {
    public $price;
    public $auction_id;
    
    public function updatedPrice() {
        $this->validateOnly('price');
    }
    
    public function submitBid() {
        $this->validate(['price' => 'required|min:' . $this->auction->min_price]);
        // 입찰 처리
    }
}
```
**예상 시간 단축**: 2-3일 → 0.5일 (80% 단축)

#### 3. 관리자 대시보드 (중간 우선순위)
**현재**: 다수의 API 엔드포인트 + Alpine.js  
**Livewire 적용 시**: 통합된 컴포넌트
```php
class AdminDashboard extends Component {
    public $auctions;
    public $users;
    
    public function updateAuctionStatus($id, $status) {
        $auction = Auction::find($id);
        $auction->update(['status' => $status]);
        $this->auctions = Auction::latest()->get();
    }
}
```
**예상 시간 단축**: 4-5일 → 1.5일 (70% 단축)

#### 4. 파일 업로드 컴포넌트 (중간 우선순위)
**현재**: 복잡한 JavaScript + 개별 API  
**Livewire 적용 시**: 단일 컴포넌트로 통합
```php
class FileUploadComponent extends Component {
    public $files = [];
    public $collection = 'default';
    
    public function save() {
        foreach($this->files as $file) {
            $this->model->addMediaFromRequest($file)
                ->toMediaCollection($this->collection);
        }
    }
}
```
**예상 시간 단축**: 2일 → 0.5일 (75% 단축)

#### 5. 게시판 실시간 댓글 (낮은 우선순위)
**현재**: Ajax + DOM 조작  
**Livewire 적용 시**: 자동 렌더링
```php
class CommentSection extends Component {
    public $comments;
    public $newComment;
    
    public function addComment() {
        Comment::create([
            'content' => $this->newComment,
            'article_id' => $this->article->id
        ]);
        $this->newComment = '';
        $this->comments = $this->article->comments;
    }
}
```
**예상 시간 단축**: 1-2일 → 0.5일 (60% 단축)

### 🔧 도입 전략 (마감 임박 고려)

#### Phase 1: 핵심 기능만 (1주일)
1. **경매 실시간 업데이트** - [ ] 가장 복잡한 부분 단순화
2. **입찰 폼** - [ ] 사용자 경험 대폭 개선
3. **관리자 상태 변경** - [ ] 관리 효율성 증대

#### Phase 2: 보조 기능 (여유 시)
4. **파일 업로드** - [ ] 안정성 향상
5. **게시판 댓글** - [ ] 사용자 만족도 향상

### 🚨 도입 시 주의사항

#### 보안 강화 효과
- [ ] **CSRF 자동 보호**: Livewire 기본 제공
- [ ] **XSS 방지**: 자동 이스케이프 처리
- [ ] **입력 검증**: 서버사이드 실시간 검증

#### 기존 코드와의 호환성
```php
// 기존 Alpine.js와 함께 사용 가능
<div x-data="auctionTimer" wire:poll.1s="refreshAuction">
    <livewire:auction-timer :auction="$auction" />
</div>
```

### 💰 총 예상 효과
- [ ] **개발 시간**: 12-15일 → 4-5일 (약 70% 단축)
- [ ] **버그 감소**: 클라이언트-서버 동기화 이슈 대폭 감소
- [ ] **보안 향상**: 자동 CSRF/XSS 보호
- [ ] **유지보수성**: JavaScript 코드 대폭 감소

### 🎯 권장 우선순위
1. **경매 실시간 기능** (필수, 즉시 적용)
2. **입찰 폼** (필수, 즉시 적용)  
3. **관리자 대시보드** (권장, 1주일 내)
4. **파일 업로드** (선택, 여유 시)
5. **게시판 댓글** (선택, 여유 시)

---

> **⚠️ 긴급 보안 권고**  
> 🔴 표시된 보안 취약점들은 **즉시 수정**이 필요합니다.  
> 특히 Mass Assignment와 API 키 노출은 시스템 전체에 치명적인 영향을 줄 수 있습니다.  
> 수정 후 반드시 보안 테스트를 실시하여 취약점이 해결되었는지 확인하세요.  
> 
> **🚀 Livewire 도입 시**: 위 보안 취약점들 중 상당수가 자동으로 해결되며, 개발 속도도 대폭 향상됩니다.