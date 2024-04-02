<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCachesTable extends Migration
{
    public function up()
    {
        Schema::create('caches', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable()->comment('타입');
            $table->string('rel_id')->nullable()->comment('대상아이디');
            $table->longText('content')->nullable()->comment('내용');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('caches');
    }
}
