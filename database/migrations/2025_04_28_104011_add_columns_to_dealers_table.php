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
        Schema::table('dealers', function (Blueprint $table) {
            $table->string('car_management_business_registration_number')->nullable()->comment('자동차관리업 등록번호');
            $table->string('business_registration_number')->nullable()->comment('사업자등록번호');
            $table->string('corporation_registration_number')->nullable()->comment('법인등록번호');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn([
                'car_management_business_registration_number',
                'business_registration_number',
                'corporation_registration_number',
            ]);
        });
    }
};
