<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cedula')->unique();
            $table->date('birth_date');
            $table->string('gender');
            $table->string('address');
            $table->string('email');
            $table->string('phone');
            $table->string('blood_type');
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}; 