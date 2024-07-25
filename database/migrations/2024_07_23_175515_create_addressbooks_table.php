<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addressbooks', function (Blueprint $table) {
            $table->id()->comment('아이디');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable()->comment('이름');
            $table->string('addr_post')->nullable()->comment('우편번호');
            $table->string('addr1')->nullable()->comment('주소');
            $table->string('addr2')->nullable()->comment('상세주소');

            $table->timestamps();
        });

        // auctions 테이블 수정
    }

    public function down()
    {
        Schema::dropIfExists('addressbooks');
    }
};
