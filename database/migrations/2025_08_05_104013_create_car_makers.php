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
        Schema::create('car_makers', function (Blueprint $table) {
            $table->integer('id')->comment('제조사 코드')->unsigned()->primary();
            $table->string('name')->comment('국문 제조사명');
            $table->string('name_en')->comment('영문 제조사명')->nullable();
            $table->string('country')->comment('제조국가')->nullable();
            $table->char('import_yn', 1)->comment('국산/수입구분 (국산:N, 수입:Y)')->nullable();
            $table->string('logo_url')->comment('제조사 로고 이미지')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_makers');
    }
};
