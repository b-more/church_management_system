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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('event_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('venue')->nullable();
            $table->text('venue_address')->nullable();
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->unsignedBigInteger('coordinator_id')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->integer('expected_attendance')->nullable();
            $table->integer('actual_attendance')->nullable();
            $table->boolean('registration_required')->default(false);
            $table->dateTime('registration_deadline')->nullable();
            $table->string('status')->default('Planned');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            // $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            // $table->foreign('organizer_id')->references('id')->on('members')->onDelete('set null');
            // $table->foreign('coordinator_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
