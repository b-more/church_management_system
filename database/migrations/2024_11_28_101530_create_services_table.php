<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('service_type')->nullable();
            $table->string('service_name')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            
            // Service Leaders
            $table->unsignedBigInteger('host_id')->nullable();
            $table->unsignedBigInteger('intercession_leader_id')->nullable();
            $table->unsignedBigInteger('offering_exhortation_leader_id')->nullable();
            $table->unsignedBigInteger('sunday_school_teacher_id')->nullable();
            
            // Preacher Information
            $table->string('preacher_type'); // local or visiting
            $table->unsignedBigInteger('preacher_id')->nullable();
            $table->string('visiting_preacher_name')->nullable();
            $table->string('visiting_preacher_church')->nullable();
            $table->string('visiting_preacher_city')->nullable();
            $table->string('visiting_preacher_country')->nullable();
            $table->string('visiting_preacher_phone')->nullable();
            
            $table->unsignedBigInteger('worship_leader_id')->nullable();
            $table->unsignedBigInteger('announcer_id')->nullable();
            
            // Message Details
            $table->string('message_title')->nullable();
            $table->text('bible_reading')->nullable();
            
            // Media and Streaming
            $table->string('service_banner')->nullable();
            $table->string('audio_recording')->nullable();
            $table->string('facebook_stream_link')->nullable();
            $table->string('youtube_stream_link')->nullable();
            
            // Attendance
            $table->integer('total_attendance')->default(0);
            $table->integer('total_first_timers')->default(0);
            $table->integer('total_members')->default(0);
            $table->integer('total_visitors')->default(0);
            $table->integer('total_children')->default(0);
            
            // Financial
            $table->decimal('offering_amount', 10, 2)->default(0);
            $table->decimal('tithe_amount', 10, 2)->default(0);
            
            $table->string('status')->default('Scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};