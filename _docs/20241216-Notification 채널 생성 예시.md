
# Laravel Custom Notification Channel: AligoChannel

라라벨에서 `Aligo`라는 이름의 커스텀 노티피케이션 채널을 추가하는 방법에 대한 예제입니다.

---

## 1. AligoChannel 클래스 생성

`app/Notifications/Channels` 디렉토리를 생성하고, 그 안에 `AligoChannel` 클래스를 만듭니다.

```php
namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class AligoChannel
{
    public function send($notifiable, Notification $notification)
    {
        // 노티피케이션 데이터 가져오기
        $data = $notification->toAligo($notifiable);

        // 데이터 전송 로직 구현
        $this->sendAligoNotification($data);
    }

    protected function sendAligoNotification($data)
    {
        // 실제 전송 처리 로직 작성
        // 예: HTTP 요청, API 연동 등
    }
}
```

---

## 2. 노티피케이션 클래스에서 AligoChannel 사용

`php artisan make:notification AligoNotification` 명령어로 노티피케이션 클래스를 생성합니다.

### 예제: `AligoNotification` 클래스

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AligoNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['aligo']; // Aligo 채널 추가
    }

    public function toAligo($notifiable)
    {
        return [
            'message' => 'This is a notification sent via Aligo.',
            'phone' => $notifiable->phone, // 예: 수신자 전화번호
        ];
    }
}
```

---

## 3. AligoChannel 서비스 컨테이너에 등록

`AppServiceProvider` 또는 별도의 서비스 프로바이더에서 채널을 등록합니다.

### 예제: `AppServiceProvider`

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notifications\Channels\AligoChannel;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(AligoChannel::class)
            ->needs('$dependencies')
            ->give(function () {
                // 필요한 종속성 주입 (예: API 키, HTTP 클라이언트 등)
            });
    }
}
```

---

## 4. 노티피케이션 트리거

`notify` 메서드를 사용하여 사용자 또는 모델에 알림을 전송합니다.

### 예제: 사용자 모델

```php
use App\Notifications\AligoNotification;

$user = User::find(1); // 예: User 모델
$user->notify(new AligoNotification());
```

---

## 5. 테스트 및 검증

- `AligoChannel` 클래스에서 구현한 로직을 테스트합니다.
- API 호출 또는 기타 작업이 올바르게 수행되는지 확인합니다.

---

### 참고

1. `toAligo` 메서드에서 반환하는 데이터는 `AligoChannel` 클래스에서 처리될 데이터입니다.
2. 외부 서비스 연동 시 Guzzle 또는 HTTP 클라이언트를 활용하여 API를 호출하세요.
3. 의존성 주입을 통해 설정 값(API 키, URL 등)을 유연하게 관리할 수 있습니다.
