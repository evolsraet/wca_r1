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
        Schema::create('dealers', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->comment('아이디');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name')->comment('이름');
            $table->string('phone')->comment('연락처');
            $table->date('birthday')->comment('생년월일');
            $table->string('company')->comment('소속상사');
            $table->string('company_duty')->comment('직책');
            $table->string('company_post')->comment('우편번호');
            $table->string('company_addr1')->comment('주소');
            $table->string('company_addr2')->comment('상세주소');
            $table->string('receive_post')->comment('인수지 우편번호');
            $table->string('receive_addr1')->comment('인수지 주소');
            $table->string('receive_addr2')->comment('인수지 상세주소');
            $table->text('introduce')->comment('딜러소개');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
