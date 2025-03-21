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
        Schema::create('user_sns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('provider')->comment('소셜 제공자 (google, kakao, naver)');
            $table->string('provider_id')->comment('소셜 제공자의 고유 ID');
            $table->string('provider_email')->comment('소셜 제공자의 이메일');
            $table->json('provider_data')->nullable()->comment('소셜 제공자의 추가 데이터');
            $table->timestamps();
            
            $table->unique(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sns');
    }
};
