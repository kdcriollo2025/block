<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            // Verificar si la clave foránea ya existe antes de agregarla
            $foreignKeys = DB::select("
                SELECT conname FROM pg_constraint 
                WHERE conrelid = 'medical_histories'::regclass
            ");

            $existingConstraints = array_map(fn($fk) => $fk->conname, $foreignKeys);

            if (!in_array('medical_histories_patient_id_foreign', $existingConstraints)) {
                $table->foreign('patient_id')->references('id')->on('patients');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            // Eliminar la clave foránea solo si existe
            $foreignKeys = DB::select("
                SELECT conname FROM pg_constraint 
                WHERE conrelid = 'medical_histories'::regclass
            ");

            $existingConstraints = array_map(fn($fk) => $fk->conname, $foreignKeys);

            if (in_array('medical_histories_patient_id_foreign', $existingConstraints)) {
                $table->dropForeign(['patient_id']);
            }
        });
    }
};
