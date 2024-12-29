<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rental_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamps();

            $table->foreign('rental_id')->references('rental_id')->on('rental')->onDelete('cascade'); // 'rental_id' di tabel 'rental'
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade'); // 'book_id' di tabel 'books'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_detail');
    }
};
