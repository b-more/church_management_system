<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pledges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('name')->nullable(); // For non-members or override
            $table->string('phone_number')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable(); // Total pledged amount
            $table->enum('frequency', ['one-time', 'weekly', 'bi-weekly', 'monthly', 'quarterly', 'yearly'])->nullable();
            $table->decimal('frequency_amount', 10, 2)->nullable(); // Amount per frequency
            $table->decimal('received_amount', 15, 2)->default(0); // Amount actually received
            $table->date('pledge_date')->nullable();
            $table->date('target_completion_date')->nullable();
            $table->enum('status', ['active', 'completed', 'defaulted', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            // $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pledges');
    }
};
