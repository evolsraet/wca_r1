<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->unsignedInteger('car_maker_id')->after('diag_second_at')->comment('제조사 코드');
            $table->unsignedInteger('car_model_id')->after('car_maker_id')->comment('대표 모델 코드');
            $table->unsignedInteger('car_detail_id')->after('car_model_id')->comment('상세 모델 코드');
            $table->unsignedInteger('car_bp_id')->after('car_detail_id')->comment('제원코드');
            $table->unsignedInteger('car_grade_id')->after('car_bp_id')->comment('등급코드');

            $table->foreign('car_maker_id')->references('id')->on('car_makers')->cascadeOnDelete();
            $table->foreign('car_model_id')->references('id')->on('car_models')->cascadeOnDelete();
            $table->foreign('car_detail_id')->references('id')->on('car_details')->cascadeOnDelete();
            $table->foreign('car_bp_id')->references('id')->on('car_bps')->cascadeOnDelete();
            $table->foreign('car_grade_id')->references('id')->on('car_grades')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropForeign(['car_maker_id']);
            $table->dropForeign(['car_model_id']);
            $table->dropForeign(['car_detail_id']);
            $table->dropForeign(['car_bp_id']);
            $table->dropForeign(['car_grade_id']);
            
            $table->dropColumn([
                'car_maker_id',
                'car_model_id', 
                'car_detail_id',
                'car_bp_id',
                'car_grade_id'
            ]);
        });
    }
};