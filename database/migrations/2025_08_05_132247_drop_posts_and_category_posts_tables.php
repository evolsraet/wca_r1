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
        // Drop category_posts table first due to foreign key constraints
        Schema::dropIfExists('category_posts');
        
        // Drop posts table
        Schema::dropIfExists('posts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate posts table
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('user_id');
            $table->longText('content');
            $table->timestamps();
        });

        // Recreate category_posts table
        Schema::create('category_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->primary(['category_id', 'post_id']);
        });
    }
};
