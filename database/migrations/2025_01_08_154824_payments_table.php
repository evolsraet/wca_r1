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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('bid')->comment('bid값');
            $table->string('tid')->comment('tid값');
            $table->string('orderId')->comment('주문번호');
            $table->string(column: 'status')->comment('결제상태');
            $table->string(column: 'paidAt')->comment('결제일시');
            $table->string(column: 'payMethod')->comment('결제방법');
            $table->string(column: 'amount')->comment('결제금액');
            $table->string(column: 'goodsName')->comment('상품이름');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
