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
        Schema::create('ussd_givings', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('giving_type')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ussd_givings');
    }
};
