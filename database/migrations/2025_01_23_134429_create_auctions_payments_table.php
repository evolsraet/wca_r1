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
        Schema::create('auctions_payments', function (Blueprint $table) {
            $table->bigIncrements('id'); // 기본 키
            $table->string('auction_id')->nullable()->comment('경매 ID'); // AuctionID
            $table->string('pay_method', 10)->nullable()->comment('지불수단'); // PayMethod
            $table->string('mid', 20)->nullable()->comment('상점ID'); // MID
            $table->string('mall_user_id', 20)->nullable()->comment('회원사 ID'); // MallUserID
            $table->decimal('amount', 10, 2)->nullable()->comment('금액'); // Amt
            $table->string('name', 30)->nullable()->comment('구매자명'); // name
            $table->string('goods_name', 40)->nullable()->comment('상품명'); // GoodsName
            $table->string('tid', 30)->nullable()->comment('거래번호'); // TID
            $table->string('moid', 64)->nullable()->comment('주문번호'); // MOID
            $table->dateTime('auth_date')->nullable()->comment('입금일시'); // AuthDate (YYMMDDHHMISS)
            $table->string('result_code', 10)->nullable()->comment('결과코드'); // ResultCode
            $table->string('result_msg', 100)->nullable()->comment('결과메시지'); // ResultMsg
            $table->string('vbank_num', 30)->nullable()->comment('가상계좌번호'); // VbankNum
            $table->string('fn_cd', 10)->nullable()->comment('가상계좌 은행코드'); // FnCd
            $table->string('vbank_name', 30)->nullable()->comment('가상계좌 은행명'); // VbankName
            $table->string('vbank_input_name', 30)->nullable()->comment('입금자명'); // VbankInputName
            $table->dateTime('cancel_date')->nullable()->comment('취소일시'); // CancelDate
            $table->string('rcpt_tid', 30)->nullable()->comment('현금영수증 거래번호'); // RcptTID
            $table->string('rcpt_type', 10)->nullable()->comment('현금영수증 구분'); // RcptType
            $table->string('rcpt_auth_code', 30)->nullable()->comment('현금영수증 승인번호'); // RcptAuthCode
            $table->string('status', 10)->nullable()->comment('상태'); // Status

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions_payments');
    }
};
