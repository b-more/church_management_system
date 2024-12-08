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
        Schema::create('cell_group_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cell_group_id')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('meeting_type')->default('Regular');
            $table->string('venue')->nullable();
            $table->text('venue_address')->nullable();
            $table->unsignedBigInteger('host_id')->nullable()->constrained('members');
            $table->unsignedBigInteger('leader_id')->nullable();
            $table->string('topic')->nullable();
            $table->string('bible_reading')->nullable();
            $table->integer('total_attendance')->default(0);
            $table->integer('total_members_present')->default(0);
            $table->integer('total_visitors')->default(0);
            $table->decimal('offering_amount', 10, 2)->default(0);
            $table->string('status')->default('Scheduled');
            $table->string('cancellation_reason')->nullable();
            $table->date('next_meeting_date')->nullable();
            $table->json('testimonies')->nullable();
            $table->json('prayer_points')->nullable();
            $table->json('announcements')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
    
            // Indexes for faster queries
            $table->index(['cell_group_id', 'date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_group_meetings');
    }
};
