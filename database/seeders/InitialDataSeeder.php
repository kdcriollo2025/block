<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medico;
use App\Models\Patient;
use App\Models\MedicalHistory;
use App\Models\AllergyRecord;
use App\Models\VaccinationRecord;
use App\Models\SurgeryRecord;
use App\Models\MedicalConsultationRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador con una cédula diferente
        $adminUser = User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'cedula' => '1712345678', // Cédula diferente
            'password' => Hash::make('12345678'),
            'type' => 'admin',
            'first_login' => false,
        ]);

        // Crear usuario médico con una cédula diferente
        $medicoUser = User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'medico1@gmail.com',
            'cedula' => '1798765432', // Cédula diferente
            'password' => Hash::make('12345678'),
            'type' => 'medico',
            'first_login' => false,
        ]);

        // Crear perfil del médico
        $medico = Medico::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'medico1@gmail.com',
            'cedula' => '1798765432', // Misma cédula que el usuario médico
            'specialty' => 'Cardiología',
            'phone_number' => '0996512993',
            'is_active' => true,
        ]);

        // Crear pacientes para el médico
        $patient1 = Patient::create([
            'name' => 'María García',
            'email' => 'maria@gmail.com',
            'phone_number' => '0987654321',
            'birth_date' => '1990-05-15',
            'gender' => 'Femenino',
            'blood_type' => 'O+',
            'address' => 'Av. Principal 123',
            'doctor_id' => $medico->id,
        ]);

        $patient2 = Patient::create([
            'name' => 'Carlos López',
            'email' => 'carlos@gmail.com',
            'phone_number' => '0998765432',
            'birth_date' => '1985-08-20',
            'gender' => 'Masculino',
            'blood_type' => 'A+',
            'address' => 'Calle Secundaria 456',
            'doctor_id' => $medico->id,
        ]);

        // Crear historiales médicos
        $history1 = MedicalHistory::create([
            'patient_id' => $patient1->id,
            'hash' => Str::random(64), // Generar un hash aleatorio
        ]);

        $history2 = MedicalHistory::create([
            'patient_id' => $patient2->id,
            'hash' => Str::random(64), // Generar un hash aleatorio
        ]);

        // Crear registros de alergias
        AllergyRecord::create([
            'medical_history_id' => $history1->id,
            'allergy_name' => 'Penicilina',
            'severity_level' => 'Alta',
            'allergy_symptoms' => 'Dificultad para respirar, erupciones cutáneas',
            'diagnosis_date' => '2023-01-15',
        ]);

        AllergyRecord::create([
            'medical_history_id' => $history2->id,
            'allergy_name' => 'Polen',
            'severity_level' => 'Media',
            'allergy_symptoms' => 'Estornudos, congestión nasal',
            'diagnosis_date' => '2023-03-20',
        ]);

        // Crear registros de vacunación
        VaccinationRecord::create([
            'medical_history_id' => $history1->id,
            'vaccine_name' => 'COVID-19',
            'dose' => '2da dosis',
            'application_date' => '2023-02-10',
            'next_application_date' => '2024-02-10',
        ]);

        VaccinationRecord::create([
            'medical_history_id' => $history2->id,
            'vaccine_name' => 'Influenza',
            'dose' => 'Única',
            'application_date' => '2023-04-15',
            'next_application_date' => null,
        ]);

        // Crear registros de cirugías
        SurgeryRecord::create([
            'medical_history_id' => $history1->id,
            'surgery_name' => 'Apendicectomía',
            'surgeon' => 'Dr. Roberto Sánchez',
            'surgery_date' => '2023-05-20',
            'details' => 'Cirugía exitosa sin complicaciones',
        ]);

        SurgeryRecord::create([
            'medical_history_id' => $history2->id,
            'surgery_name' => 'Artroscopia de rodilla',
            'surgeon' => 'Dra. María López',
            'surgery_date' => '2023-08-15',
            'details' => 'Procedimiento sin complicaciones, recuperación favorable',
        ]);

        // Crear registros de consultas médicas
        MedicalConsultationRecord::create([
            'medical_history_id' => $history1->id,
            'doctor_id' => $medico->id,
            'reason' => 'Control rutinario',
            'symptoms' => 'Ninguno',
            'diagnosis' => 'Paciente en buen estado',
            'treatment' => 'Mantener hábitos saludables',
            'consultation_date' => '2023-06-15',
            'next_appointment' => '2023-12-15',
        ]);

        MedicalConsultationRecord::create([
            'medical_history_id' => $history2->id,
            'doctor_id' => $medico->id,
            'reason' => 'Dolor de cabeza',
            'symptoms' => 'Migraña, sensibilidad a la luz',
            'diagnosis' => 'Migraña tensional',
            'treatment' => 'Ibuprofeno 400mg cada 8 horas',
            'consultation_date' => '2023-07-20',
            'next_appointment' => '2023-08-20',
        ]);
    }
}
