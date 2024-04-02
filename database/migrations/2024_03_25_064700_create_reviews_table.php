<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('dealer_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->double('star', 2, 1)->nullable();
            $table->text('content')->nullable();
            // Unique index for composite key
            $table->unique(['auction_id', 'user_id', 'dealer_id'], 'composite_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
