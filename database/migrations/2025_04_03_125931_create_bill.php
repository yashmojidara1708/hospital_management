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
        Schema::create('bill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admitted_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->string('room_number');
            $table->date('admission_date');
            $table->date('discharge_date');
            $table->integer('total_days');
            $table->decimal('room_charge',10,2);
            $table->decimal('doctor_fees',10,2);
            $table->decimal('discount',10,2);
            $table->decimal('discount_amount',10,2);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->unsignedBigInteger('generated_by'); // User ID who generated the bill
            $table->date('generated_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill');
    }
};
