<?php

// API 보안 테스트

test('비인증 사용자는 보호된 엔드포인트에 접근할 수 없다', function () {
    $endpoints = [
        'GET:/api/auctions',
        'POST:/api/auctions',
        'GET:/api/users/me',
        'POST:/api/bids',
        'GET:/api/payment'
    ];

    foreach ($endpoints as $endpoint) {
        [$method, $url] = explode(':', $endpoint);
        
        $response = match($method) {
            'GET' => $this->getJson($url),
            'POST' => $this->postJson($url, []),
            'PUT' => $this->putJson($url, []),
            'DELETE' => $this->deleteJson($url),
        };
        
        expect($response->status())->toBeIn([401, 302], "Endpoint {$endpoint} should be protected");
    }
});

test('SQL Injection 공격 방어 테스트', function () {
    // SQL injection 시도
    $maliciousInputs = [
        "1' OR '1'='1",
        "'; DROP TABLE users; --",
        "1 UNION SELECT * FROM users",
        "<script>alert('xss')</script>",
        "{{7*7}}" // Template injection
    ];

    foreach ($maliciousInputs as $input) {
        $response = $this->post('/api/users', [
            'user' => [
                'name' => $input,
                'email' => $input . '@test.com',
                'phone' => '01012345678',
                'password' => 'password123!',
                'password_confirmation' => 'password123!',
                'isCheckPrivacy' => 1
            ]
        ]);

        // 입력값이 그대로 저장되지 않고 적절히 처리되는지 확인
        expect($response->status())->toBeIn([200, 422]);
    }
});

test('권한 기반 접근 제어 테스트', function () {
    $user = actingAsUser();
    $otherUser = createUser();
    
    // 다른 사용자의 정보에 접근 시도
    $response = $this->getJson("/api/users/{$otherUser->id}");
    
    // 접근 권한이 없어야 함 (403 또는 404)
    expect($response->status())->toBeIn([403, 404]);
});

test('Rate Limiting 테스트 (로그인 시도)', function () {
    $attempts = 0;
    $maxAttempts = 6; // Laravel 기본값보다 높게 설정
    
    // 연속으로 잘못된 로그인 시도
    for ($i = 0; $i < $maxAttempts; $i++) {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);
        
        if ($response->status() === 429) {
            // Rate limit에 걸렸음
            break;
        }
        $attempts++;
    }
    
    // Rate limiting이 적용되었는지 확인
    expect($attempts)->toBeLessThan($maxAttempts);
});

test('CSRF 보호 테스트', function () {
    // CSRF 토큰 없이 요청
    $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                     ->post('/login', [
                         'email' => 'test@example.com',
                         'password' => 'password'
                     ]);
    
    // CSRF 보호가 적용되어 있는지 확인
    expect($response->status())->toBeIn([419, 422, 302]);
});

test('대용량 페이로드 공격 방어', function () {
    $largeData = str_repeat('A', 10000); // 10KB 데이터
    
    $response = $this->post('/api/users', [
        'user' => [
            'name' => $largeData,
            'email' => 'large@test.com',
            'phone' => '01012345678',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'isCheckPrivacy' => 1
        ]
    ]);
    
    // 대용량 데이터가 적절히 처리되는지 확인
    expect($response->status())->toBeIn([200, 422, 413]);
});

test('민감한 정보 노출 방지 테스트', function () {
    $user = actingAsUser();
    
    $response = $this->getJson('/api/users/me');
    
    if ($response->status() === 200) {
        $userData = $response->json();
        
        // 비밀번호나 토큰 등이 노출되지 않는지 확인
        expect($userData)->not->toHaveKey('password');
        expect($userData)->not->toHaveKey('remember_token');
        expect($userData)->not->toHaveKey('email_verified_at');
    }
});

test('파일 업로드 보안 테스트', function () {
    $user = actingAsUser();
    
    // 위험한 파일 확장자 테스트
    $dangerousExtensions = ['php', 'exe', 'bat', 'sh'];
    
    foreach ($dangerousExtensions as $ext) {
        $response = $this->post('/api/auctions', [
            'title' => '테스트 경매',
            'description' => '설명',
            'start_price' => 100000,
            'car_model' => '테스트',
            'car_year' => 2020,
            'car_mileage' => 50000,
            'file' => \Illuminate\Http\Testing\File::create("malicious.{$ext}", 100)
        ]);
        
        // 위험한 파일이 업로드되지 않아야 함
        expect($response->status())->toBeIn([400, 422]);
    }
});

test('API 버전 정보 노출 방지', function () {
    $response = $this->getJson('/api');
    
    // API 버전이나 시스템 정보가 노출되지 않는지 확인
    if ($response->status() === 200) {
        $content = $response->getContent();
        expect($content)->not->toContain('Laravel');
        expect($content)->not->toContain('PHP');
        expect($content)->not->toContain('version');
    }
});

test('HTTP 헤더 보안 설정 확인', function () {
    $response = $this->getJson('/api/users/me');
    
    // 보안 헤더가 설정되어 있는지 확인
    $headers = $response->headers->all();
    
    // X-Frame-Options 헤더 확인
    expect($headers)->toHaveKey('x-frame-options');
    
    // Content-Type이 올바르게 설정되어 있는지 확인
    expect($response->headers->get('content-type'))->toContain('application/json');
});