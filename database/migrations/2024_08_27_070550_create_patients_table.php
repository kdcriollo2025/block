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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('medicos')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cedula')->unique();
            $table->string('phone')->nullable();
            $table->string('address');
            $table->date('birth_date');
            $table->string('gender');
            $table->string('blood_type');
            $table->text('allergies')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};