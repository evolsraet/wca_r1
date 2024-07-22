<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 수정 부분
            $table->softDeletes()->after('updated_at')->comment('삭제');
            $table->string('status')->default('ask')->comment('상태');
            $table->string('phone')->nullable()->comment('연락처');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes(); // SoftDeletes 롤백
        });
    }
};
