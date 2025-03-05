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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('role');
            $table->string('specialization');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('experience')->unsigned()->comment('Experience in years');
            $table->string('qualification');
            $table->text('address');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('zip');
            $table->string('image')->nullable()->comment('Profile image path');
            $table->integer('isdeleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
