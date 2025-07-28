# 위카옥션 v2 전체 기능 맵 (프론트→백엔드 시간흐름)

> 주의: 프론트엔드는 PHP Blade 뷰이므로 세션은 백엔드에서 관리됨. 별도의 토큰 저장 없음.
> 개발환경에서 모든 알림은 mail (mailfit) 으로 사용. 실제로는 메일+알리고(알림톡) 으로 진행.

## 1. 인증 및 회원 관리

### 1-1. 로그인
- 로그인 페이지 접속 (`GET /v2/login`)
- 이메일/비밀번호 입력
- 소셜 로그인 선택 (카카오/네이버)
- 로그인 버튼 클릭
> `POST /v2/login` 폼 제출
> AuthenticatedSessionController@login
> 이메일 또는 전화번호로 사용자 조회 (⚠️ orWhere 취약점)
> 사용자 상태 확인
  - ask: "현재 심사중입니다" 에러
  - warning1: "3일 이용금지" 에러 (최초로그인 시 penalty_until 할당)
  - warning2: "30일 이용금지" 에러 (최초로그인 시 penalty_until 할당)
  - expulsion: "영구정지" 에러
> 비밀번호 검증
> 세션 생성 및 리다이렉트

### 1-2. 회원가입
- 회원가입 페이지 접속 (`GET /v2/register`)
    - `register.blade` 는 모든 회원가입폼이 유저,딜러,관리자 / 사용자모드,관리자모드 분기에 따라 갈린다.
- 회원 타입 선택 (일반/딜러)
- 기본정보 입력 (이름, 전화번호, 이메일, 비밀번호)
- 딜러인 경우 추가정보 입력
  - 주소, 사업자번호, 대표자명 등 현재 필수요소
  - 파일: 사진, 사업자등록증, 매도용인감증명서, 위임장
- 약관 동의 체크
- 가입 버튼 클릭
> FormData로 전체 데이터 수집
> `POST /api/users` 요청
> Api\UserController@store
> 발리데이션 : 중복 체크 (전화번호, 이메일)
> 파일 업로드 처리
> User 생성 (status: 'ask')
> 역할 부여 (user/dealer)
> 알림: UserStartJob (관리자에게 가입 승인 요청)

### 1-3. 소셜 로그인 
- 소셜 로그인 버튼 클릭 (카카오/네이버)
> `GET /auth/{provider}/redirect`
> SocialLoginController@redirect  
> OAuth 인증 페이지로 리다이렉트
> 사용자 동의 후 콜백
> `GET /auth/{provider}/callback`
> SocialLoginController@callback
> 소셜 사용자 정보 획득
> UserSns 테이블에서 기존 회원 확인
> 기존 회원: 자동 로그인 처리
> 신규: 세션에 social_user 저장 후 회원가입 페이지로
  - 회원가입 시 socialLogin=true로 비밀번호 자동생성

### 1-4. 비밀번호 재설정
- 비밀번호 찾기 클릭 (`GET /v2/forgot-password`)
- 전화번호 입력
- 재설정 요청 버튼 클릭
> `POST /api/users/reset-password-link`
> Api\UserController@resetPasswordLink
> 전화번호로 사용자 확인
> 3시간 유효 암호화 링크 생성 (AES-256)
> 알림: ResetPasswordLinkJob (SMS 발송)
  - Aligo API로 링크 전송
> 사용자가 SMS 링크 클릭
> `GET /v2/reset-password/{token}`
> 토큰 복호화 및 검증
> 새 비밀번호 입력 폼 표시
> `POST /v2/reset-password`
> 비밀번호 업데이트 및 로그인

## 2. 차량 판매 (Sell)

### 2-1. 차량 조회
- 판매 메인 페이지 접속 (`GET /v2/sell`)
- 소유자명, 차량번호 입력
- 조회 버튼 클릭
> `POST /v2/sell/result` 폼 제출
> SellController@result
> 세션에 owner_name, car_no 저장
> result.blade.php로 리다이렉트
> 페이지 로드 시 차량정보 API 호출
> `POST /api/auctions/car-info`
> AuctionController@carInfo
> NiceDNRService 호출
  - 차량 기본정보 조회 (제조사, 모델, 연식 등)
  - 캐시 확인 (1일 1회 제한)
> CarmerceService 호출
  - 도매/소매 시세 조회
> 중복 경매 체크 (status != 'cancel')
> 결과 반환

### 2-2. 판매 신청
- 예상가 측정 버튼 클릭
> `POST /api/auctions/check-expected-price`
> AuctionController@CheckExpectedPrice
> 감가상각 계산 (연식별 비율)
> 옵션 반영
  - 사고이력: -300만원
  - 키 개수: 1개 -50만원
  - 휘 손상: -30만원
  - 광택 작업: -20만원
> 예상가격 반환
- 판매 신청 버튼 클릭
> apply.blade.php로 이동 (세션 데이터 전달)
- 본인인증 모달 오픈
> 본인인증 API 호출
> 세션에 authenticated_owner 저장
- 추가 정보 입력
  - 주소 (Daum 우편번호 API)
  - 진단 희망일 (datepicker)
  - 계좌정보 (은행 선택 모달)
  - 파일 업로드 (자동차등록증 필수)
- 판매 신청 제출
> FormData 생성 (모든 필드 + 파일)
> `POST /v2/sell/apply`
> SellController@apply
> 세션 본인인증 확인 필수
> 파일 업로드 (MediaLibrary)
> Auction 생성
  - status: 'ask'
  - user_id: 비로그인 시 null
> 알림 발송:
  - AuctionStartJob: 고객에게 등록 완료 알림
  - AuctionStartJobAdmin: 관리자에게 신규 경매 알림 (⚠️ User::find(2) 하드코딩)
  - AuctionDiagJobAdmin: 진단팀에게 진단 요청

## 3. 경매 시스템

### 3-1. 경매 목록 조회  
- 경매 목록 페이지 접속 (`GET /v2/auction`)
- 카테고리 탭 선택 (전체/진행중/종료)
- 검색 조건 입력
> API 호출로 경매 목록 로드
> 상태별 필터링:
  - 진행중: status = 'ing'
  - 종료: status in ('chosen', 'dlvr', 'done')
> 페이지네이션 처리
> 각 경매 카드에 표시:
  - 남은 시간 또는 종료 시간
  - 현재 입찰가
  - 입찰 수

### 3-2. 경매 상세 조회
- 경매 아이템 클릭
> `GET /v2/auction/{id}`
> AuctionController@show
> 경매 정보 with 관계 로드
> 사용자 역할별 뷰 분기:
  - guest: 제한된 정보만 표시
  - user: 판매자용 상태 표시
  - dealer: 입찰 가능 UI
> 상태별 컴포넌트:
  - ask: "심사중" 표시
  - diag: "진단중" + 진단예정일
  - ing: 입찰 폼 + 현재 최고가 + 남은시간
  - wait: "낙찰자 선택 대기중"
  - chosen: 결제 대기 또는 결제 완료
  - dlvr: 탁송 정보
  - done: 거래 완료

### 3-3. 입찰 (딜러만)
- 딜러 로그인 상태에서 경매 상세 페이지
- 입찰가 입력 (현재가보다 높아야 함)
- 입찰 버튼 클릭
> `POST /api/bids`
> BidController@store
> BidService 처리:
  - 경매 상태 확인 (ing만 가능)
  - 최소 입찰가 검증
  - 기존 입찰 여부 확인
> Bid 생성 또는 업데이트
> Auction의 current_bid_price 갱신
> 알림: AuctionBidStatusJobAsk
  - 판매자에게 "새로운 입찰가 {price}원" 알림

### 3-4. 경매 종료 및 낙찰자 선택
- 경매 종료 시간 도래 (스케줄러 또는 관리자)
> 상태 변경: ing → wait
> 판매자에게 선택 요청 알림
- 판매자가 낙찰자 선택
> 상태 변경: wait → chosen  
> 알림 발송:
  - AuctionCohosenJobUser: 판매자에게 낙찰 알림
  - AuctionCohosenJobDealer: 딜러에게 낙찰 알림
  - 미선택 딜러들에게 탈락 알림

### 3-5. 결제 처리
- 낙찰 딜러가 결제 페이지 접속
> `GET /payment/form`
> PaymentController@showPaymentForm
> Bid 정보 확인
> 가상계좌 만료일 설정 (2주)
> ⚠️ 테스트 가격 100원 하드코딩
- 결제 진행
> 나이스페이 폼 제출
> `POST /payment/request`
> PaymentController@requestPayment  
> SHA-256 서명 검증
> 결제 승인 API 호출
- PG사 웹훅 수신
> `POST /payment/notify`
> WebhookController@paymentNotify
> 결제 정보 DB 저장 (auctions_payments)
> 알림: AuctionTotalDepositJob
  - 판매자: "차량대금 입금 확인"
  - 딜러: "차량대금 입금 확인, 탁송지 입력 필요"
  - 탁송팀: 탁송 준비 알림

### 3-6. 탁송 및 명의이전
- 딜러가 탁송지 정보 입력
> 상태 변경: chosen → dlvr
> 탁송 진행
- 탁송 완료 후 명의이전
> 알림: TaksongNameChangeJob  
  - 판매자/딜러에게 "탁송 완료, 명의이전 진행" 알림
- 명의이전 서류 업로드
> `POST /api/auctions/name-change-file-upload`
> AuctionController@nameChangeFileUpload
> 파일 업로드 (MediaLibrary)
> 상태 변경: dlvr → done
> 알림 발송:
  - AuctionDoneJobUser: 판매자에게 "거래 완료" 알림
  - AuctionDoneJobDealer: 딜러에게 "수수료 안내" 알림
  - TaksongNameChangeFileUploadJob: 명의이전 서류 업로드 확인

### 3-7. 경매 취소
- 관리자 또는 판매자가 경매 취소
> 상태 변경: * → cancel
> 알림: AuctionCancelJob
  - 모든 입찰자에게 취소 알림
  - 판매자에게 취소 확인

## 4. 게시판 시스템

### 4-1. 게시글 목록
- 게시판 접속 (`GET /v2/board/{boardId}`)
- BoardController@index
- 스킨별 list.blade.php 렌더링
- Alpine.js articleList 컴포넌트
- 카테고리 필터
- 검색 (제목, 내용)
- 정렬 (작성일, 조회수)
> API로 게시글 목록 로드
> 페이지네이션 처리

### 4-2. 게시글 작성
- 글쓰기 버튼 클릭
> `GET /v2/board/{boardId}/form`
> BoardController@form
> 작성 권한 확인:
  - 리뷰(3): 판매자만, 경매 완료 14일 이내
  - 클레임(4): 낙찰 딜러만, 경매 완료 14일 이내
> 리뷰/클레임의 경우 경매 선택 필수
- 내용 작성 (제목, 내용, 카테고리)
- 파일 첨부
- 등록 버튼 클릭
> `POST /api/articles`
> ArticleController@store
> 유효성 검사
> Article 생성
> 파일 업로드 (MediaLibrary)
> 목록으로 리다이렉트

### 4-3. 게시글 조회
- 게시글 클릭
> `GET /v2/board/{boardId}/view/{articleId}`
> BoardController@view
> 조회수 증가
> view.blade.php 렌더링
> 댓글 목록 로드

### 4-4. 댓글 작성
- 댓글 입력
- 등록 버튼 클릭
> `POST /api/comments`
> CommentController@store
> 댓글 생성
> 실시간 댓글 추가

## 5. 관리자 시스템

### 5-1. 관리자 로그인
- 관리자 계정으로 로그인
> 권한 확인 (hasRole('admin'))
> 관리자 대시보드 접근

### 5-2. 경매 관리
- 관리자 경매 목록 (`GET /v2/admin/auction`)
> 전체 경매 표시 (모든 상태)
> 검색 및 필터링
- 경매 상태 변경
> 진단 완료: ask → diag
  - 알림: AuctionDiagJob (고객에게 진단 대기 알림)
> 진단 결과 등록: diag → ing
  - 알림: AuctionIngJob (고객에게 경매 시작 알림)
> 경매 종료: ing → wait
  - 알림: AuctionBidStatusJobWait (선택 대기)

### 5-3. 회원 관리  
- 회원 목록 조회
- 회원 상태 변경
> ask → ok: 가입 승인
> warning1/2: 경고 처리 (3일/30일 제재)
> expulsion: 영구 정지
> 상태변경 알림
- 딜러 정보 관리
> 사업자등록증, 서류 확인

### 5-4. 게시판 관리
- 공지사항 작성/수정
- 게시글 삭제
- 댓글 관리

## 6. 알림 시스템 (상세)

### 6-1. 경매 등록 알림
- **AuctionStartJob**: 고객에게 "경매 등록 완료" (SMS/이메일)
- **AuctionStartJobAdmin**: 관리자에게 "새 경매 등록" (이메일)
- **AuctionDiagJobAdmin**: 진단팀에게 "진단 요청" (이메일)

### 6-2. 상태 변경 알림
- **AuctionDiagJob**: ask→diag 시 "진단 대기중"
- **AuctionIngJob**: diag→ing 시 "경매 시작"
- **AuctionBidStatusJobAsk**: 새 입찰 시 판매자에게
- **AuctionBidStatusJobWait**: ing→wait 시 "선택 대기"
- **AuctionCohosenJobUser/Dealer**: 낙찰 알림
- **AuctionCancelJob**: 취소 시 모든 입찰자

### 6-3. 결제/탁송 알림
- **AuctionTotalDepositJob**: 차량대금 입금 확인
- **TaksongNameChangeJob**: 탁송 완료 알림
- **AuctionDoneJobUser/Dealer**: 거래 완료 알림
- **TaksongNameChangeFileUploadJob**: 명의이전 서류 업로드

### 6-4. 시스템 알림
- **UserStartJob**: 신규 회원가입 (관리자)
- **ResetPasswordLinkJob**: 비밀번호 재설정 링크

### 6-5. 알림 채널
- **SMS/알림톡**: Aligo API
- **이메일**: Laravel Mail (Queue)
- **템플릿**: NotificationTemplate::getTemplate()

### 6-6. 누락된 알림 기능
- ❌ 진단 완료 알림
- ❌ 입금 독촉 알림
- ❌ 경매 마감 임박 알림
- ❌ 선택 시간 초과 알림
- ❌ 알림 수신 설정 기능

## 7. 외부 API 연동

### 7-1. 나이스 DNR (차량정보)
- API 호출: NiceDNRService::getCarInfo()
- 입력: 소유자명, 차량번호
- 반환 정보:
  - 제조사, 모델명, 연식
  - 차량형식, 연료, 배기량
  - 최초등록일, 검사유효일
- 캐시: 24시간

### 7-2. 카머스 (시세정보)
- API 호출: CarmerceService::getPrice()
- 입력: 모델코드, 연식, 세부모델
- 반환 정보:
  - min_price (최저가)
  - max_price (최고가)
  - market_price (시장가)
- 캐시: 24시간

### 7-3. 나이스페이 (결제)
- 결제 요청: SHA-256 서명
- 가상계좌:
  - 유효기간: 14일
  - 입금 확인: 웹훅
- 신용카드: 즉시 결제
- 취소/환불 API 지원
**차량대금**은 가상계좌 + 에스크로 > 고객에게
**성공수수료**는 가상계좌 > 회사계좌

### 7-4. 본인인증
- 휴대폰 본인인증 API
- 세션 저장: authenticated_owner
- 유효기간: 세션 유지 시간
- 차량 판매 신청 시 필수

### 7-5. Aligo (SMS/알림톡)
- SMS 발송: AligoService::send()
- 알림톡: 템플릿 기반
- 대량 발송 지원
- 발송 결과 확인 API

## 8. 파일 관리

### 8-1. 파일 업로드
- Spatie MediaLibrary 사용
- 컨버전 설정:
  - thumb: 150x150
  - preview: 500x500
- 파일 타입:
  - 회원: 사진, 사업자등록증, 서류
  - 경매: 자동차등록증, 사진
  - 게시판: 첨부파일

### 8-2. 파일 다운로드  
- 보안 다운로드 (`GET /files/download/{uuid}`)
> Media 모델에서 UUID로 파일 확인
> 권한 체크 (소유자 또는 관리자)
> 스트리밍 다운로드
> 다운로드 로그 기록

### 8-3. 엑셀 다운로드
- 관리자 전용 (`GET /excelDown/{resource}`)
- 지원 리소스:
  - users: 회원 목록
  - auctions: 경매 목록
  - bids: 입찰 내역
  - payments: 결제 내역

## 9. 보안 및 권한

### 9-1. 인증 (Authentication)
- PHP 세션 기반 (Blade 뷰)
- API용 Sanctum 토큰 (v1 호환)
- 로그인 후 세션에 사용자 정보 저장

### 9-2. 인가 (Authorization)
- Spatie Permission
- role:
  - 뷰에서 @can, @hasrole 사용
  - 종류
    - user: 일반 회원 (판매자)
    - dealer: 딜러 (구매자)
    - admin: 관리자
- permission
    - act.admin
    - act.dealer
    - act.user

### 9-3. CSRF 보호
- 모든 POST 요청에 @csrf
- ⚠️ 로컬 환경 예외 과다:
  - /api/*
  - /login, /register (제거 필요)

### 9-4. 보안 취약점
- ⚠️ Mass Assignment (User $guarded = [])
- ⚠️ 하드코딩 ID (User::find(2))
- ⚠️ orWhere 로그인 취약점
- ⚠️ API 키 노출 (.env.example)

## 10. 개발/배포 환경

### 10-1. 개발 환경
- Laravel Sail (Docker)
- 컨테이너:
  - PHP 8.2 + Laravel 10
  - MySQL 8.0
  - Redis (큐/캐시)
  - Mailhog (메일 테스트)
- 명령어:
  ```bash
  ./vendor/bin/sail up -d
  ./vendor/bin/sail artisan migrate
  ./vendor/bin/sail artisan queue:work
  ```

### 10-2. Queue 설정
- ⚠️ 현재: QUEUE_CONNECTION=sync
- 운영: database 또는 redis 변경 필요
- Job 테이블: jobs, failed_jobs
- 재시도: 3회, 대기시간 90초

### 10-3. 테스트
- 테스트 커버리지 부족
- 필요한 테스트:
  - 회원가입/로그인
  - 경매 등록/입찰
  - 결제 프로세스
  - 알림 발송

## 11. 스케줄링 작업 (Cron)

### 11-1. 매분 실행
- **진단 완료 체크** (DiagService::diagnosticCheck)
  - status='diag' 경매의 진단 완료 여부 확인
  - 완료 시 status='ing'로 변경
  
- **탁송 상태 체크** (TaksongStatusJob)
  - chosen/dlvr 상태 경매의 탁송 API 호출
  - 탁송 상태 업데이트
  
- **경매 종료 처리** (AuctionService::auctionFinalAtUpdate)
  - ing 상태에서 final_at 지난 경매
  - status='wait'로 변경 (선택 마감 2일)
  - 알림: 판매자에게 선택 요청

### 11-2. 매시간 실행
- **차량대금 미입금 체크** (AuctionService::auctionTotalDepositMiss)
  - dlvr 상태 + 탁송 ask 상태
  - 탁송희망일 2시간 전 알림
  
- **경매 자동 취소** (AuctionService::auctionCancel)
  - wait 상태에서 선택 마감일 초과
  - bid_id 없으면 status='cancel'
  - 알림: 모든 입찰자에게 취소 알림
  
- **명의이전 확인** (ownership:check 커맨드)
  - dlvr/done 상태 경매
  - 명의이전 API 호출하여 완료 확인

### 11-3. 매일 오전 9시 실행
- **수수료 처리** (AuctionService::auctionAfterFeeDone)
  - done 상태 + 차량대금 결제 완료
  - 딜러에게 수수료 안내
  
- **명의이전 독촉** (OwnershipService::sendOwnershipAlertsAuto)
  - dlvr/done 상태 + 명의이전 미완료
  - 구매자/판매자에게 독촉 알림

### 11-4. 스케줄러 설정
```bash
# crontab 설정 필요
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 12. 주요 프로세스 요약

### 11-1. 회원 프로세스
1. **가입**: status='ask' → 관리자 승인 → status='ok'
2. **로그인**: 세션 생성, 상태 체크
3. **제재**: warning1(3일), warning2(30일), expulsion(영구)

### 11-2. 경매 프로세스
1. **등록**: ask (심사대기)
2. **진단**: ask → diag → ing (경매시작)
3. **입찰**: 딜러만 가능, 최고가 갱신
4. **종료**: ing → wait (선택대기)
5. **낙찰**: wait → chosen
6. **결제**: 가상계좌/카드
7. **탁송**: chosen → dlvr
8. **완료**: dlvr → done

### 11-3. 알림 타이밍
- **즉시**: 상태 변경 시
- **예약**: 마감 임박, 입금 독촉 (미구현)
- **배치**: 통계, 요약 (미구현)

---

## 🚨 긴급 수정 필요

### 보안 취약점 (즉시)
1. **User 모델**: $guarded = [] → $fillable 사용
2. **관리자 ID**: User::find(2) → 환경변수
3. **API 키**: .env.example에서 실제 키 제거
4. **로그인**: orWhere 취약점 수정
5. **CSRF**: 과도한 예외 제거

### 기능 개선 (중요)
1. **Queue**: sync → database/redis
2. **알림 누락**: 진단완료, 입금독촉 등
3. **에러 처리**: 표준화 필요
4. **로깅**: 체계적 기록 필요
5. **테스트**: 커버리지 부족
