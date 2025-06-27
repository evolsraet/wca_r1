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
            $table->string('taksong_status')->nullable()->comment('탁송상태')->after('done_at');
            $table->unsignedBigInteger('vehicle_payment_id')->nullable()->comment('차량대금 입금 정보 ID')->after('taksong_status');
            $table->unsignedBigInteger('fee_payment_id')->nullable()->comment('수수료 입금 정보 ID')->after('vehicle_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn(['taksong_status', 'vehicle_payment_id', 'fee_payment_id']);
        });
    }
};
