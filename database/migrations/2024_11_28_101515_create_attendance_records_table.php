<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('attendance_records', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->unsignedBigInteger('service_id')->nullable();
        $table->unsignedBigInteger('member_id')->nullable();
        $table->string('attendance_type')->nullable();
        $table->dateTime('check_in_time')->nullable();
        $table->dateTime('check_out_time')->nullable();
        $table->string('visitor_name')->nullable();
        $table->string('visitor_phone')->nullable();
        $table->text('visitor_address')->nullable();
        $table->string('age_group')->nullable();
        $table->string('gender')->nullable();
        $table->string('previous_church')->nullable();
        $table->unsignedBigInteger('checked_in_by')->nullable();
        $table->boolean('follow_up_required')->default(false);
        $table->text('follow_up_notes')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();

        // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        // $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        // $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
        // $table->foreign('checked_in_by')->references('id')->on('users')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
