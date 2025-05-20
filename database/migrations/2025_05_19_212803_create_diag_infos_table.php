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
        Schema::create('diag_infos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index(); // 빠른 조회용 인덱스
            $table->longText('diag_data'); // JSON 데이터 저장
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diag_infos');
    }
};
