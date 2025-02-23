<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cedula', 20)->unique();
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F']);
            $table->string('blood_type', 5)->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('email')->unique()->nullable();
            $table->foreignId('doctor_id')->nullable()->constrained('medicos')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};