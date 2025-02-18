<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->constrained('users');
            $table->text('diagnosis');
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Para mantener el registro incluso si se "elimina"
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
}; 