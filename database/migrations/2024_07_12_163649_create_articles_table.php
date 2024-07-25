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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('board_id')->comment('보드 아이디');
            $table->string('category')->nullable()->comment('카테고리');
            $table->string('title')->comment('제목');
            $table->text('content')->comment('내용');
            $table->unsignedBigInteger('user_id')->comment('작성자');

            $table->string('extra1')->nullable()->comment('여분1');
            $table->string('extra2')->nullable()->comment('여분2');
            $table->timestamp('extra1_date_at')->nullable()->comment('여분스탬프1');
            $table->timestamp('extra2_date_at')->nullable()->comment('여분스탬프2');

            $table->timestamps();
            $table->softDeletes()->comment('삭제일');

            // 비밀글 여부
            $table->boolean('is_secret')->default(false)->comment('비밀글');

            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
