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
        Schema::create('api_error_logs', function (Blueprint $table) {
            $table->id();

            $table->string('job_name')->nullable()->comment('실행된 잡 클래스 이름');
            $table->string('method')->default('POST')->comment('요청 방식: POST, GET 등');
            $table->text('url')->nullable()->comment('요청한 API 주소');
            $table->json('payload')->nullable()->comment('요청 데이터');
            $table->text('response_body')->nullable()->comment('API 응답 또는 에러 응답 내용');
            $table->text('error_message')->nullable()->comment('예외 메시지');
            $table->longText('trace')->nullable()->comment('스택 트레이스');

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_error_logs');
    }
};
