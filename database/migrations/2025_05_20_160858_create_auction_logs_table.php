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
        Schema::create('auction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('auctions')->comment('경매 ID');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('사용자 ID');
            $table->string('ip')->comment('IP 주소');
            $table->string('status')->comment('상태');
            $table->longText('changes')->nullable()->comment('변경(Json)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_logs');
    }
};
