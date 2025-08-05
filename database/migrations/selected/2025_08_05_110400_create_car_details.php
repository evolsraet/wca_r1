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
        Schema::create('car_details', function (Blueprint $table) {
            $table->integer('id')->comment('상세 모델 코드')->unsigned()->primary();
            $table->string('name')->comment('상세 모델명');
            $table->integer('model_id')->comment('대표 모델 코드')->unsigned();
            $table->string('short_name')->comment('모델 약칭')->nullable();
            $table->string('generation_name')->comment('세대명')->nullable();
            $table->string('start_date')->comment('출시 년월 (Y.m형식)')->nullable();
            $table->string('end_date')->comment('단종 년월 (Y.m형식)')->nullable();
            $table->string('image_url')->comment('모델 이미지')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_details');
    }
};
