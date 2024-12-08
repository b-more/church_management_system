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
        Schema::create('attendance_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('total_services')->default(0)->nullable();
            $table->integer('attended_services')->default(0)->nullable();
            $table->decimal('attendance_percentage', 5, 2)->default(0.00)->nullable();
            $table->date('last_attendance_date')->nullable();
            $table->integer('consecutive_absences')->default(0)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add unique constraint to prevent duplicate entries
            $table->unique(['member_id', 'branch_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_statistics');
    }
};
