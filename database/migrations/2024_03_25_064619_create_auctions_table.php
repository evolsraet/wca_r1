<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('owner_name')->comment('소유자명');
            $table->string('car_no')->comment('차량번호');
            $table->string('status')->default('ask')->comment('진행상태');
            $table->unsignedBigInteger('diag_id')->nullable()->comment('진단아이디');
            $table->timestamp('diag_check_at')->nullable()->comment('진단확인일');
            $table->softDeletes();
            $table->string('region')->nullable()->comment('지역');
            $table->string('addr_post')->nullable()->comment('우편번호');
            $table->string('addr1')->nullable()->comment('주소');
            $table->string('addr2')->nullable()->comment('상세주소');
            $table->string('bank')->nullable()->comment('은행');;
            $table->string('account')->nullable()->comment('은행번호');
            $table->text('memo')->nullable()->comment('고객 메모');
            $table->text('memo_digician')->nullable()->comment('평가사 의견');
            $table->boolean('is_reauction')->default(0)->comment('재경매여부');
            $table->boolean('is_biz')->default(0)->comment('법인/사업자차량');
            $table->timestamp('final_at')->nullable()->comment('경매마감일');
            $table->timestamp('choice_at')->nullable()->comment('선택일');
            $table->timestamp('done_at')->nullable()->comment('완료일');
            // $table->foreignId('bid_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            // 순서 문제로 bids_table 생성에서 추가
            $table->unsignedInteger('success_fee')->nullable()->comment('성공수수료');
            $table->unsignedInteger('diag_fee')->nullable()->comment('진단수수료');
            $table->unsignedInteger('total_fee')->nullable()->comment('총비용');
            $table->unsignedInteger('final_price')->nullable()->comment('낙찰가');
            $table->boolean('verified')->default(0)->comment('');
            $table->unsignedInteger('hit')->default(0)->comment('조회수');
            $table->unsignedInteger('hope_price')->nullable()->comment('희망가');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auctions');
    }
}
