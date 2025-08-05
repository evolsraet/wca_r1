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
        Schema::create('car_grades', function (Blueprint $table) {
            $table->integer('id')->comment('등급코드')->unsigned()->primary();
            $table->string('name')->comment('등급명');
            $table->integer('bp_id')->comment('제원코드')->unsigned();
            $table->string('type_name')->comment('등급구분명')->nullable();
            $table->integer('car_type_id')->comment('차종코드')->unsigned();
            $table->string('car_type_name')->comment('차종명')->nullable();
            $table->integer('shape_category_id')->comment('외형 구분 코드')->unsigned();
            $table->string('shape_category_name')->comment('외형 구분')->nullable();
            $table->integer('purpose_id')->comment('용도코드')->unsigned()->nullable();
            $table->string('purpose_name')->comment('용도명')->nullable();
            $table->string('displacement')->comment('배기량')->nullable();
            $table->string('gearbox')->comment('변속기')->nullable();
            $table->string('gearbox_auto')->comment('자동 변속기 단수')->nullable();
            $table->string('gearbox_manual')->comment('수동 변속기 단수')->nullable();
            $table->string('fuel')->comment('연료')->nullable();
            $table->string('seating_capacity')->comment('승차정원')->nullable();
            $table->integer('grade_newcar_price')->comment('등급신차가격')->unsigned()->nullable();
            $table->string('start_date')->comment('세부 출시 년월 (Y.m형식)')->nullable();
            $table->string('end_date')->comment('세부 단종 년월 (Y.m형식)')->nullable();
            $table->timestamp('created_at')->comment('데이터 등록 일자');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_grades');
    }
};
