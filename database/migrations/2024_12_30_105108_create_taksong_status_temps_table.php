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
        Schema::create('taksong_status_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->comment('경매아이디');
            $table->string('chk_id')->nullable()->comment('탁송아이디');
            $table->string('chk_status')->nullable()->comment('탁송상태');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taksong_status_temps');
    }
};
