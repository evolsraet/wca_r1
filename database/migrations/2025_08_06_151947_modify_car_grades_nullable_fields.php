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
        Schema::table('car_grades', function (Blueprint $table) {
            $table->integer('bp_id')->comment('제원코드')->unsigned()->nullable()->change();
            $table->integer('car_type_id')->comment('차종코드')->unsigned()->nullable()->change();
            $table->integer('shape_category_id')->comment('외형 구분 코드')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_grades', function (Blueprint $table) {
            $table->integer('bp_id')->comment('제원코드')->unsigned()->nullable(false)->change();
            $table->integer('car_type_id')->comment('차종코드')->unsigned()->nullable(false)->change();
            $table->integer('shape_category_id')->comment('외형 구분 코드')->unsigned()->nullable(false)->change();
        });
    }
};
