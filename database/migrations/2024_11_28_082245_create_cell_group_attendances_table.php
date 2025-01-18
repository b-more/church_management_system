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
        Schema::create('cell_group_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cell_group_meeting_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('attendance_type')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_phone')->nullable();
            $table->text('visitor_address')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->text('follow_up_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
    
            // Prevent duplicate attendance records
            $table->unique(['cell_group_meeting_id', 'member_id']);
            
            // Custom shorter names for indexes
            $table->index(['cell_group_meeting_id', 'attendance_type'], 'cg_meeting_attendance_type_idx');
            $table->index(['member_id', 'cell_group_meeting_id'], 'member_meeting_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_group_attendance');
    }
};
