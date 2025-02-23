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
        Schema::create('surgery_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_history_id')->constrained()->onDelete('cascade');
            $table->string('surgery_type');
            $table->dateTime('surgery_date');
            $table->string('surgeon');
            $table->string('hospital');
            $table->text('description')->nullable();
            $table->text('complications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surgery_records');
    }
};
