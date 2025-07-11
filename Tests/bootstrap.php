<?php

/*
|--------------------------------------------------------------------------
| Test Bootstrap File
|--------------------------------------------------------------------------
|
| 이 파일은 테스트 스위트가 시작될 때 한 번만 실행됩니다.
| 데이터베이스 마이그레이션과 시딩을 여기서 처리합니다.
|
*/

// 이제 테스트 실행 흐름:
// - 테스트 스위트 시작 → bootstrap.php 실행 (마이그레이션 + 시드 1회)
// - 각 테스트 실행 시 트랜잭션 시작
// - 테스트 완료 후 트랜잭션 롤백 (DatabaseTransactions trait)
// - 다음 테스트도 깨끗한 상태에서 시작 (시드 데이터는 유지)

/*
⏺ 네, 정확합니다! 현재 설정이 완성되었습니다:

  1. 테스트 스위트 시작 시 (1회만):
    - phpunit.xml이 tests/bootstrap.php를 실행
    - migrate:fresh로 깨끗한 DB 생성
    - DatabaseSeeder로 기본 데이터 설정 (roles, permissions)
  2. 각 테스트 실행 시:
    - DatabaseTransactions trait이 트랜잭션 시작
    - 테스트 코드 실행 (데이터 생성/수정/삭제)
    - 테스트 종료 시 자동 롤백
  3. 장점:
    - 시드는 1회만 실행되어 성능 향상
    - 각 테스트는 독립적으로 실행 (격리성 보장)
    - 기본 데이터(roles, permissions)는 유지되면서 테스트 데이터만 롤백
*/

require __DIR__.'/../vendor/autoload.php';

// 애플리케이션 부트스트랩
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 테스트 환경인지 확인
if ($app->environment() === 'testing') {
    echo "Setting up test database...\n";

    // 마이그레이션 실행
    Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
        '--env' => 'testing',
    ]);

    // 시더 실행
    Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'Database\Seeders\DatabaseSeeder',
        '--env' => 'testing',
    ]);

    echo "Test database setup complete!\n";
}
