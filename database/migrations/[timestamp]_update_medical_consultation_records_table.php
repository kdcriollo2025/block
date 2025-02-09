<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medical_consultation_records', function (Blueprint $table) {
            // Verifica si las columnas existen antes de crearlas
            if (!Schema::hasColumn('medical_consultation_records', 'reason')) {
                $table->string('reason')->after('doctor_id');
            }
            if (!Schema::hasColumn('medical_consultation_records', 'symptoms')) {
                $table->text('symptoms')->after('reason');
            }
            if (!Schema::hasColumn('medical_consultation_records', 'diagnosis')) {
                $table->text('diagnosis')->after('symptoms');
            }
            if (!Schema::hasColumn('medical_consultation_records', 'treatment')) {
                $table->text('treatment')->after('diagnosis');
            }
            if (!Schema::hasColumn('medical_consultation_records', 'consultation_date')) {
                $table->date('consultation_date')->after('treatment');
            }
            if (!Schema::hasColumn('medical_consultation_records', 'next_appointment')) {
                $table->date('next_appointment')->nullable()->after('consultation_date');
            }
        });
    }

    public function down()
    {
        Schema::table('medical_consultation_records', function (Blueprint $table) {
            // Solo elimina las columnas si existen
            $columns = [
                'reason',
                'symptoms',
                'diagnosis',
                'treatment',
                'consultation_date',
                'next_appointment'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('medical_consultation_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 