<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('offering_type_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('pledge_id')->nullable();
            $table->unsignedBigInteger('partnership_id')->nullable();

            // Contributor details (for non-members or override)
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();

            // Financial details
            $table->decimal('amount', 15, 2)->nullable(); // Amount received

            // Date tracking
            $table->date('date')->nullable();
            $table->integer('week_number')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();

            // Additional info
            $table->text('narration')->nullable();
            $table->string('payment_method')->nullable(); // Cash, Bank Transfer, Mobile Money, etc.
            $table->string('reference_number')->nullable(); // Transaction reference

            // Audit trail
            $table->unsignedBigInteger('recorded_by')->nullable(); // User who recorded this
            $table->timestamp('recorded_at')->nullable();

            $table->timestamps();

            // Foreign key constraints
            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            // $table->foreign('offering_type_id')->references('id')->on('offering_types')->onDelete('cascade');
            // $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            // $table->foreign('pledge_id')->references('id')->on('pledges')->onDelete('set null');
            // $table->foreign('partnership_id')->references('id')->on('partnerships')->onDelete('set null');
            // $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for better performance
            $table->index(['branch_id', 'date']);
            $table->index(['offering_type_id', 'date']);
            $table->index(['month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
