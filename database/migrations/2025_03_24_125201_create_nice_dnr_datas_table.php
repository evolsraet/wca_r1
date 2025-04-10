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
        Schema::create('nice_dnr_datas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ownerNm');
            $table->string('vhrNo');
            $table->string('isCached');
            $table->json('data');
        });

        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->index('vhrNo');
            $table->index('ownerNm');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nice_dnr_datas');
    }
};
