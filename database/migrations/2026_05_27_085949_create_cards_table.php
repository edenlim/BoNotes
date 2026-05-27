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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_type');
            $table->json('tags');
            $table->string('user_vote')->nullable();
            $table->integer('no_of_likes')->default(0);
            $table->integer('no_of_dislikes')->default(0);
            $table->string('author');
            $table->string('upload_time');
            $table->integer('page_length');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
