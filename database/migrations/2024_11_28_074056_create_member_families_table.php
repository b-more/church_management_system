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
        Schema::create('member_families', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('relationship_type')->nullable();
            $table->string('relative_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_member')->default(false);
            $table->unsignedBigInteger('related_member_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'relationship_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_families');
    }
};
