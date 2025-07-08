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
            $table->UnsignedBigInteger('branch_id')->nullable();
            $table->date('service_date')->nullable();
            $table->string('service_type')->nullable();
            $table->time('service_time')->nullable();

            // Service Leaders
            $table->UnsignedBigInteger('service_host_id')->nullable()->constrained('members')->nullOnDelete();
            $table->UnsignedBigInteger('intercession_leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->UnsignedBigInteger('worship_leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->UnsignedBigInteger('announcer_id')->nullable()->constrained('members')->nullOnDelete();
            $table->UnsignedBigInteger('exhortation_leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->UnsignedBigInteger('sunday_school_teacher_id')->nullable()->constrained('members')->nullOnDelete();
            $table->UnsignedBigInteger('special_song_singer_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('special_song_group')->nullable();
            $table->time('end_time')->nullable();


            // Preacher Information
            $table->enum('preacher_type', ['local', 'visiting'])->default('local');
            $table->UnsignedBigInteger('preacher_id')->nullable()->constrained('members')->nullOnDelete();
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
