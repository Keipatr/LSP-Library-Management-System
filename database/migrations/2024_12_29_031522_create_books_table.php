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
        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id');
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->year('publication_year');
            $table->string('isbn')->unique();
            $table->enum('book_status', [0, 1])->default(0); // 0 = unavailable, 1 = available
            $table->enum('status_delete', [0, 1])->default(0); // 0: active, 1: deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
