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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rated_user_id');
            $table->unsignedBigInteger('rated_by_user_id');
            $table->unsignedInteger('rating_value');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('rated_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rated_by_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
