<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('leader_id')->nullable();
            $table->string('meeting_day')->nullable();
            $table->time('meeting_time')->nullable();
            $table->string('meeting_location')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_groups');
    }
};