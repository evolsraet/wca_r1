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
            $table->integer('unique_number')->unique();
            
            $table->boolean('auction_type')->comment('경매 타입');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('owner_name')->comment('소유자명');
            $table->string('company_name')->nullable()->comment('회사명');
            $table->string('car_no')->comment('차량번호')->index();
            $table->string('status')->default('ask')->comment('진행상태');
            $table->unsignedBigInteger('diag_id')->nullable()->comment('진단아이디');
            $table->timestamp('diag_check_at')->nullable()->comment('진단확인일');
            $table->string('region')->nullable()->comment('지역');
            $table->string('addr_post')->nullable()->comment('우편번호');
            $table->string('addr1')->nullable()->comment('주소');
            $table->string('addr2')->nullable()->comment('상세주소');
            $table->string('bank')->nullable()->comment('은행');
            $table->string('account')->nullable()->comment('은행번호');
            $table->string('account_name')->nullable()->comment('예금주');
            $table->text('memo')->nullable()->comment('고객 메모');
            $table->text('memo_digician')->nullable()->comment('평가사 의견');
            $table->boolean('is_reauction')->default(0)->comment('재경매여부');
            $table->string('is_deposit')->nullable()->comment('입금여부');
            $table->string('is_taksong')->nullable()->comment('탁송상태');
            $table->boolean('is_biz')->default(0)->comment('법인/사업자차량');
            $table->boolean('is_accident')->default(0)->comment('사고차량');
            $table->boolean('is_business_owner')->default(0)->comment('사업자 소유자 여부');
            $table->boolean('is_agree')->default(0)->comment('개인사업자등록상태 조회 고지사항 동의여부');
            $table->timestamp('diag_first_at')->nullable()->comment('진단희망 날짜및시간1');
            $table->timestamp('diag_second_at')->nullable()->comment('진단희망 날짜및시간2');

            // 자동차 정보 추가 
            $table->string('car_maker')->nullable()->comment('차량제조사');
            $table->string('car_model')->nullable()->comment('차량모델');
            $table->string('car_model_sub')->nullable()->comment('차량모델 서브');
            $table->string('car_grade')->nullable()->comment('차량등급');
            $table->string('car_grade_sub')->nullable()->comment('차량등급 서브');
            $table->string('car_year')->nullable()->comment('차량연식');
            $table->timestamp('car_first_reg_date')->nullable()->comment('최량최초등록일');
            $table->string('car_mission')->nullable()->comment('차량미션');
            $table->string('car_fuel')->nullable()->comment('차량연료');
            $table->string('car_price_now')->nullable()->comment('소매 시세');
            $table->string('car_price_now_whole')->nullable()->comment('도매 시세');
            $table->string('car_km')->nullable()->comment('차량주행거리');
            $table->string('car_thumbnail')->nullable()->comment('차량썸네일');

            // 차량상태
            $table->string('car_status')->nullable()->comment('차량상태');
            $table->string('car_condition')->nullable()->comment('차량키로수');


            $table->timestamp('final_at')->nullable()->comment('경매마감일');
            $table->timestamp('choice_at')->nullable()->comment('선택일');
            $table->timestamp('taksong_wish_at')->nullable()->comment('탁송희망일');
            $table->timestamp('done_at')->nullable()->comment('완료일');


            

            // 탁송 정보 
            $table->string('taksong_courier_fee')->nullable()->comment('탁송요금');
            $table->string('taksong_courier_name')->nullable()->comment('기사명');
            $table->string('taksong_courier_mobile')->nullable()->comment('기사전화번호');
            $table->text('taksong_departure_address')->nullable()->comment('출발지');
            $table->string('taksong_departure_mobile')->nullable()->comment('출발지 전화번호');
            $table->text('taksong_dest_address')->nullable()->comment('도착지');
            $table->string('taksong_dest_mobile')->nullable()->comment('도착지 전화번호');
            $table->dateTime('taksong_departure_at')->nullable()->comment('출발시간');
            $table->dateTime('taksong_dest_at')->nullable()->comment('도착시간');

            // $table->foreignId('bid_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            // 순서 문제로 bids_table 생성에서 추가
            $table->unsignedInteger('success_fee')->nullable()->comment('성공수수료');
            $table->unsignedInteger('diag_fee')->nullable()->comment('진단수수료');
            $table->unsignedInteger('total_fee')->nullable()->comment('총비용');
            $table->unsignedInteger('final_price')->nullable()->comment('낙찰가');
            $table->boolean('verified')->default(0)->comment('');
            $table->unsignedInteger('hit')->default(0)->comment('조회수');
            $table->unsignedInteger('hope_price')->nullable()->comment('희망가');

            $table->string('dest_addr_post')->nullable()->comment('도착지 우편번호');
            $table->string('dest_addr1')->nullable()->comment('도착지 주소');
            $table->string('dest_addr2')->nullable()->comment('도착지 상세주소');

            $table->string('customTel1')->nullable()->comment('고객 전화번호1');
            $table->string('customTel2')->nullable()->comment('고객 전화번호2');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auctions');
    }
}
