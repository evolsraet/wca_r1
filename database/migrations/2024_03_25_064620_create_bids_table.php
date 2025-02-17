<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->uuid('auction_id');
            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade')->comment('딜러 아이디');
            $table->string('status')->nullable()->comment('상태');
            $table->unsignedInteger('price')->comment('입찰가격');
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
        });

        Schema::table('auctions', function (Blueprint $table) {
            // `after` 메소드를 사용하여 `done_at` 컬럼 이후에 위치하도록 설정
            $table->foreignId('bid_id')->nullable()->constrained('bids')->onDelete('cascade')->onUpdate('cascade')->after('done_at')->comment('낙찰아이디');
        });
    }

    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropForeign(['bid_id']);
            $table->dropColumn('bid_id');
        });

        Schema::dropIfExists('bids');
    }
}
