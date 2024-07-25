<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->string('id')->primary()->comment('아이디'); // 보드 이름 (영문)
            $table->json('categories')->nullable()->comment('카테고리'); // 카테고리 필드 (JSON 형태)
            $table->text('index_permission')->nullable()->comment('목록보기 권한'); // 목록보기 권한 필드
            $table->text('show_permission')->nullable()->comment('조회 권한'); // 조회 권한 필드
            $table->text('write_permission')->nullable()->comment('생성 권한'); // 생성 권한 필드
            $table->text('reply_permission')->nullable()->comment('답변 권한'); // 답변 권한 필드
            $table->text('comment_permission')->nullable()->comment('코멘트 권한'); // 코멘트 권한 필드
            $table->text('attach_permission')->nullable()->comment('파일첨부 권한'); // 파일첨부 권한 필드
            $table->unsignedInteger('paginate')->nullable()->comment('페이지 당 수'); // 페이지당수 필드 (텍스트)
            $table->string('skin')->nullable()->comment('스킨'); // 스킨 필드 (텍스트)
            $table->json('admins')->nullable()->comment('관리자'); // 관리자 필드 (여러 users.id)
            $table->timestamps(); // 생성 및 수정 시간
            $table->softDeletes()->comment('소프트 삭제'); // 소프트 삭제
            $table->boolean('use_secret')->default(false)->comment('비밀글 사용여부'); // 비밀글 여부 필드
        });

        // 필수 공지사항
        DB::table('boards')->insert([
            'id' => 'notice',
            'categories' => json_encode(['공지사항', '업데이트 정보', '이벤트 소식'], JSON_UNESCAPED_UNICODE),
            'index_permission' => null,
            'show_permission' => null,
            'write_permission' => 'act.admin',
            'reply_permission' => 'act.admin',
            'attach_permission' => 'act.admin',
            'comment_permission' => 'act.login',
            'paginate' => 10,
            'skin' => 'default',
            'admins' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('boards')->insert([
            'id' => 'free',
            'categories' => null,
            'index_permission' => null,
            'show_permission' => null,
            'write_permission' => 'act.login',
            'reply_permission' => 'act.login',
            'attach_permission' => 'act.login',
            'comment_permission' => 'act.login',
            'paginate' => 10,
            'skin' => 'default',
            'admins' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('boards')->insert([
            'id' => 'claim',
            'categories' => json_encode(['접수', '처리중', '처리완료'], JSON_UNESCAPED_UNICODE),
            'index_permission' => null,
            'show_permission' => 'act.dealer',
            'write_permission' => 'act.dealer',
            'reply_permission' => 'act.admin',
            'attach_permission' => 'act.dealer',
            'comment_permission' => 'act.dealer',
            'paginate' => 10,
            'skin' => 'default',
            'admins' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
