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
        Schema::create('admitted_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('set null')->nullable();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('set null')->nullable();
            $table->date('admit_date');
            $table->date('discharge_date')->nullable();
            $table->text('admission_reason');
            $table->boolean('status')->default(0);
            $table->integer('isdeleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admitted_patients');
    }
};
