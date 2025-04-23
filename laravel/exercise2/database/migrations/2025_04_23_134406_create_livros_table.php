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
        Schema::create('livros', function (Blueprint $table) {
            $table->id();

            // Campos obrigatórios
            $table->string('title');
            $table->text('authors');
            $table->string('isbn')->unique();

            // Campos opcionais
            $table->integer('book_id')->nullable();
            $table->text('original_title')->nullable();
            $table->float('original_publication_year')->nullable();
            $table->string('language_code', 10)->nullable();
            $table->string('editora')->nullable();
            $table->float('average_rating')->nullable();
            $table->integer('ratings_count')->default(0)->nullable();
            $table->integer('work_ratings_count')->default(0)->nullable();
            $table->integer('work_text_reviews_count')->default(0)->nullable();
            $table->integer('ratings_1')->default(0)->nullable();
            $table->integer('ratings_2')->default(0)->nullable();
            $table->integer('ratings_3')->default(0)->nullable();
            $table->integer('ratings_4')->default(0)->nullable();
            $table->integer('ratings_5')->default(0)->nullable();
            $table->text('image_url')->nullable();
            $table->text('small_image_url')->nullable();
            $table->unsignedBigInteger('goodreads_book_id')->nullable();
            $table->unsignedBigInteger('best_book_id')->nullable();
            $table->unsignedBigInteger('work_id')->nullable();
            $table->integer('books_count')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
