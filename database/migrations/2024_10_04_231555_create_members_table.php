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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('registration_number')->unique();
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('phone')->unique();
            $table->string('alternative_phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('address');
            $table->string('marital_status');
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();

            // Church-specific information
            $table->date('membership_date');
            $table->string('membership_status')->default('Active');
            $table->string('previous_church')->nullable();
            $table->string('previous_church_pastor')->nullable();
            $table->date('salvation_date')->nullable();
            $table->date('baptism_date')->nullable();
            $table->string('baptism_type')->nullable();

            // Growth Cycle Tracking
            $table->string('membership_class_status')->default('Not Started');
            $table->string('foundation_class_status')->default('Not Started');
            $table->string('leadership_class_status')->default('Not Started');
            $table->unsignedBigInteger('cell_group_id')->nullable();

            // Additional Information
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('special_needs')->nullable();
            $table->text('skills_talents')->nullable();
            $table->text('interests')->nullable();

            // Administrative
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pastor')->default(false);
            $table->boolean('is_intercessor')->default(false);
            $table->boolean('is_usher')->default(false);
            $table->boolean('is_worship_leader')->default(false);
            $table->boolean('is_sunday_school_teacher')->default(false);
            $table->boolean('is_offering_exhortation_leader')->default(false);
            $table->boolean('is_eligible_for_pulpit_ministry')->default(false);
            $table->string('deactivation_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['first_name', 'last_name']);
            $table->index('phone');
            $table->index('membership_status');
              $table->index('is_pastor');
            $table->index('is_intercessor');
            $table->index('is_usher');
            $table->index('is_worship_leader');
            $table->index('is_sunday_school_teacher');
            $table->index('is_offering_exhortation_leader');
            $table->index('is_eligible_for_pulpit_ministry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
