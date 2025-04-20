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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('recipient_group')->nullable(); // To store: church, kingdom workers, etc.
            $table->string('title')->nullable();
            $table->text('body')->nullable(); // For up to 1000 words
            $table->string('image_path')->nullable(); // Optional image
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
