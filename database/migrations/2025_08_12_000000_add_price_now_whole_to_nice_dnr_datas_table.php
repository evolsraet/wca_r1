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
        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->unsignedInteger('carmerce_price')->default(0)->after('data')->comment('카머스 도매 시세 (원)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->dropColumn('carmerce_price');
        });
    }
};