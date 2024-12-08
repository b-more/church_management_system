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
        Schema::create('duty_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->date('service_date');
            $table->string('service_type');
            $table->time('service_time');
            
            // Service Leaders
            $table->foreignId('service_host_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('intercession_leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('worship_leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('announcer_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('exhortation_leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('sunday_school_teacher_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('special_song_singer_id')->nullable()->constrained('members')->nullOnDelete();
            
            // Preacher Information
            $table->enum('preacher_type', ['local', 'visiting'])->default('local');
            $table->foreignId('preacher_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('visiting_preacher_name')->nullable();
            $table->string('visiting_preacher_church')->nullable();
            
            $table->text('notes')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate rosters for same date and branch
            $table->unique(['branch_id', 'service_date', 'service_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_rosters');
    }
};
