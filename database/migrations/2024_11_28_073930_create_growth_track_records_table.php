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
        Schema::create('growth_track_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('track_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->string('status')->default('Not Started');
            $table->integer('score')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->string('certificate_number')->nullable();
            $table->timestamps();
            
            // Prevent duplicate tracks
            $table->unique(['member_id', 'track_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_track_records');
    }
};
