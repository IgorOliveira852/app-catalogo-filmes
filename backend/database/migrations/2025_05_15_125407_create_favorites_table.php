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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('external_movie_id');
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->text('overview')->nullable();
            $table->date('release_date')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->foreignId('genre_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
