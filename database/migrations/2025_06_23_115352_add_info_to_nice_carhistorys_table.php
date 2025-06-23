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
        Schema::table('nice_carhistorys', function (Blueprint $table) {
            // 조회시 유저 정보 수집 
            $table->string('ip')->nullable();
            $table->string('device')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_agent')->nullable();
            $table->integer('response_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nice_carhistorys', function (Blueprint $table) {
            $table->dropColumn([
                'ip',
                'device', 
                'user_id',
                'user_agent',
                'response_status'
            ]);
        });
    }
};
