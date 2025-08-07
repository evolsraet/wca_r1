<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 카멜케이스에서 스네이크 케이스로 변경
    public function up(): void
    {
        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->dropIndex(['vhrNo']);
            $table->dropIndex(['ownerNm']);
        });

        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->renameColumn('ownerNm', 'owner_name');
            $table->renameColumn('vhrNo', 'car_no');
            $table->renameColumn('isCached', 'is_cached');
        });

        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->index('car_no');
            $table->index('owner_name');
        });
    }

    // 스네이크 케이스에서 카멜케이스로 변경
    public function down(): void
    {
        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->dropIndex(['car_no']);
            $table->dropIndex(['owner_name']);
        });

        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->renameColumn('owner_name', 'ownerNm');
            $table->renameColumn('car_no', 'vhrNo');
            $table->renameColumn('is_cached', 'isCached');
        });

        Schema::table('nice_dnr_datas', function (Blueprint $table) {
            $table->index('vhrNo');
            $table->index('ownerNm');
        });
    }
};
