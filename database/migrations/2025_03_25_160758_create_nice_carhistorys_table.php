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

        // $table->string('car_no'); 에 대한 인덱스 추가
        Schema::create('nice_carhistorys', function (Blueprint $table) {
            $table->id();
            $table->string('car_no');
            $table->date('first_regdate');
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('nice_carhistorys', function (Blueprint $table) {
            $table->index('car_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nice_carhistorys');
    }
};
