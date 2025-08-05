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
        Schema::create('car_bps', function (Blueprint $table) {
            $table->integer('id')->comment('제원코드')->unsigned()->primary();
            $table->string('name')->comment('제원명');
            $table->integer('detail_id')->comment('상세 모델 코드')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_bps');
    }
};
