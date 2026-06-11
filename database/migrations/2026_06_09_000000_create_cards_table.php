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
            $table->string('fileType');

            $table->json('tags');

            $table->integer('noOfLikes')->default(0);
            $table->integer('noOfDislikes')->default(0);

            // The updated foreign key pointing to the users table
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

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
