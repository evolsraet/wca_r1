<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->unsignedInteger('car_maker_id')->nullable()->after('diag_second_at')->comment('제조사 코드');
            
            $table->unsignedInteger('car_model_id')->nullable()->after('car_maker')->comment('대표 모델 코드');
            
            $table->unsignedInteger('car_detail_id')->nullable()->after('car_model')->comment('상세 모델 코드');
            
            $table->unsignedInteger('car_bp_id')->nullable()->after('car_model_sub')->comment('제원코드');
            
            $table->unsignedInteger('car_grade_id')->nullable()->after('car_bp_id')->comment('등급코드');

            $table->foreign('car_maker_id')->references('id')->on('car_makers')->onDelete('set null');
            $table->foreign('car_model_id')->references('id')->on('car_models')->onDelete('set null');
            $table->foreign('car_detail_id')->references('id')->on('car_details')->onDelete('set null');
            $table->foreign('car_bp_id')->references('id')->on('car_bps')->onDelete('set null');
            $table->foreign('car_grade_id')->references('id')->on('car_grades')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
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