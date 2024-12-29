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
        Schema::create('rental', function (Blueprint $table) {
            $table->id('rental_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');            $table->timestamp('borrowed_at');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->enum('rental_status', [0, 1])->default(0); // 0 = borrowed, 1 = returned
            $table->enum('status_delete', [0, 1])->default(0); // 0 = active, 1 = deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental');
    }
};
