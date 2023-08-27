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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id'); // Match the data type with 'userId' in 'users' table
            $table->string('receiver_id'); // Match the data type with 'userId' in 'users' table
            $table->Text('message');
            $table->string('chat_id');
            $table->timestamps();

            // $table->foreign('sender_id')->references('userId')->on('users')->onDelete('cascade');
            // $table->foreign('receiver_id')->references('userId')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
