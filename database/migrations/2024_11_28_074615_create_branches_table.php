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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('branch_code')->unique()->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('senior_pastor_id')->nullable();
            $table->unsignedBigInteger('district_pastor_id')->nullable();
            $table->date('founding_date')->nullable();
            $table->string('status')->default('Active');
            $table->json('service_times')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('branch_type')->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->string('gps_coordinates')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
    
            $table->index(['name', 'branch_code']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
