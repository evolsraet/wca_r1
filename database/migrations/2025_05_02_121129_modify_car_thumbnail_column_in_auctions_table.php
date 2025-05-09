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
        Schema::table('auctions', function (Blueprint $table) {
            //

            Schema::table('auctions', function (Blueprint $table) {
                // LONGTEXT로 변경 (주의: requires doctrine/dbal 패키지)
                $table->longText('car_thumbnail')->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            //

            Schema::table('auctions', function (Blueprint $table) {
                // 롤백 시 VARCHAR(255)로 되돌리기
                $table->string('car_thumbnail', 255)->nullable()->change();
            });
        });
    }
};
