<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->string('business_identity_number')->nullable()->comment('사업자 관련 통합 번호 (사업자/법인/자동차관리)');
        });
    }

    public function down()
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn('business_identity_number');
        });
    }
};
