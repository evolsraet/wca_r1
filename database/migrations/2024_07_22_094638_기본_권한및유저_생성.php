<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 시딩 코드는 PermissionRoleSeeder로 이동됨
        // 이 마이그레이션은 스키마 변경이 없으므로 빈 상태로 유지
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 시딩 데이터 롤백은 별도로 관리
    }
};
