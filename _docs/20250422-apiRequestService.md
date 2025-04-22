# ApiRequestService 사용 가이드

`ApiRequestService`는 Laravel 프로젝트 내에서 API 호출을 일관되게 처리하고,
요청 실패 시 자동으로 DB에 로깅되도록 설계된 공통 API 호출 서비스입니다.

---

## ✨ 주요 기능

- POST / GET 요청 처리
- 요청 실패 시 `api_error_logs` 테이블에 자동 기록
- 로그 컨텍스트 지정 가능
- JSON / Form 데이터 전송 모두 지원
- 커스텀 헤더, 인증 토큰, 파일 업로드, 재시도 등 유연하게 확장 가능

---

## ✅ 기본 사용법

### POST 요청
```php
use App\Services\ApiRequestService;

$api = app(ApiRequestService::class);

$response = $api->sendPost(
    'https://example.com/api/send',
    [ 'key1' => 'value1', 'key2' => 'value2' ],
    'ExampleService'
);
```

### GET 요청
```php
$response = $api->sendGet(
    'https://example.com/api/info',
    [ 'id' => 123 ],
    'ExampleService'
);
```

---

## ⚡️ 확장 옵션 예시

### JSON으로 전송
```php
$response = Http::asJson()->post($url, $data);
```

### 헤더 추가
```php
$response = Http::withHeaders([
    'X-Custom-Header' => 'value'
])->post($url, $data);
```

### 인증 토큰 추가
```php
$response = Http::withToken('abc123')->post($url, $data);
```

### 파일 업로드 (multipart/form-data)
```php
$response = Http::attach('file', file_get_contents($path), 'filename.jpg')
    ->post($url, [ 'key1' => 'value1' ]);
```

### 요청 재시도
```php
$response = Http::retry(3, 200)->post($url, $data);
```

---

## 🔍 실패 시 자동 DB 저장

요청 실패 또는 예외 발생 시 아래 테이블에 자동 저장됩니다:
- `api_error_logs`

```php
Schema::create('api_error_logs', function (Blueprint $table) {
    $table->string('job_name');
    $table->string('method');
    $table->text('url');
    $table->json('payload')->nullable();
    $table->text('response_body')->nullable();
    $table->text('error_message')->nullable();
    $table->longText('trace')->nullable();
    $table->timestamps();
});
```

---

## 📄 로깅 위치

- 정상 요청: `storage/logs/laravel.log`
- 실패 요청: `laravel.log` + `api_error_logs` 테이블

---

## ✅ 컨트롤러 / Job 등에서 사용 시
```php
$taksongService = app(App\Services\TaksongService::class);

$taksongService->addTaksong([...]);
```

TaksongService 내부에서 ApiRequestService를 주입받아 사용하는 구조입니다.

---

## 🚀 향후 확장 아이디어

- 슬랙 / 이메일 / 메신저 연동 (실패 시 알림)
- 관리자용 에러 로그 대시보드
- 로그 뷰어 (Laravel Telescope / custom UI)

---

> **작성자**: 자비스 (with syungwan)
> ✨ 자유롭게 수정하고 너만의 스타일로 발전시켜!

