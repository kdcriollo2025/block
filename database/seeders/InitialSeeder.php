<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medico;
use App\Models\Patient;
use App\Models\MedicalHistory;
use App\Models\AllergyRecord;
use App\Models\SurgeryRecord;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class InitialSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_EC');

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@empresa.com',
            'password' => Hash::make('password123'),
            'cedula' => '1716234578',
            'type' => 'admin',
            'first_login' => false,
        ]);

        // Crear médico
        $medicoUser = User::create([
            'name' => 'Dr. Juan Carlos Morales',
            'email' => 'jcmorales@empresa.com',
            'password' => Hash::make('password123'),
            'cedula' => '1715678234',
            'type' => 'medico',
            'first_login' => false,
        ]);

        $medico = Medico::create([
            'user_id' => $medicoUser->id,
            'specialty' => 'Medicina Interna',
            'phone_number' => '0991234567',
            'cedula' => $medicoUser->cedula,
        ]);

        // Lista de alergias comunes
        $alergias = [
            'Penicilina', 'Aspirina', 'Ibuprofeno', 'Polen', 
            'Ácaros del polvo', 'Mariscos', 'Nueces', 'Látex',
            'Huevo', 'Leche', 'Gluten', 'Picadura de abeja'
        ];

        // Lista de cirugías comunes
        $cirugias = [
            'Apendicectomía', 'Amigdalectomía', 'Colecistectomía',
            'Hernioplastia', 'Artroscopia de rodilla', 'Rinoplastia',
            'Extracción de cordales', 'Cesárea', 'Laparoscopia',
            'Cirugía de cataratas'
        ];

        // Crear 100 pacientes con sus historiales
        for ($i = 0; $i < 100; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $faker->firstName($gender);
            $lastName = $faker->lastName . ' ' . $faker->lastName;

            // Generar una cédula ecuatoriana válida
            $provincia = $faker->numberBetween(1, 24);
            $tercerDigito = $faker->numberBetween(0, 5);
            $cedula = sprintf("%02d%d%07d", $provincia, $tercerDigito, $faker->numberBetween(0, 9999999));
            $digitoVerificador = 0;
            for ($j = 0; $j < 9; $j++) {
                $multiplicador = ($j % 2 == 0) ? 2 : 1;
                $resultado = intval($cedula[$j]) * $multiplicador;
                if ($resultado >= 10) $resultado -= 9;
                $digitoVerificador += $resultado;
            }
            $digitoVerificador = 10 - ($digitoVerificador % 10);
            if ($digitoVerificador == 10) $digitoVerificador = 0;
            $cedula .= $digitoVerificador;

            $patient = Patient::create([
                'name' => $firstName . ' ' . $lastName,
                'cedula' => $cedula,
                'birth_date' => $faker->dateTimeBetween('-70 years', '-18 years'),
                'gender' => $gender == 'male' ? 'M' : 'F',
                'blood_type' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'address' => $faker->address,
                'phone_number' => '09' . $faker->numberBetween(80000000, 99999999),
                'email' => strtolower($firstName) . '.' . strtolower(explode(' ', $lastName)[0]) . '@gmail.com',
                'doctor_id' => $medico->id
            ]);

            $history = MedicalHistory::create([
                'patient_id' => $patient->id,
                'family_history' => $faker->optional(0.7)->randomElement([
                    'Diabetes tipo 2 en padre y abuelos',
                    'Hipertensión arterial en madre',
                    'Cáncer de mama en familia materna',
                    'Cardiopatías en familia paterna',
                    'Sin antecedentes familiares relevantes'
                ]),
                'personal_history' => $faker->optional(0.6)->randomElement([
                    'Hipertensión arterial controlada',
                    'Diabetes tipo 2 en tratamiento',
                    'Asma bronquial',
                    'Migraña crónica',
                    'Sin antecedentes personales relevantes'
                ])
            ]);

            // Agregar alergias aleatoriamente (30% de probabilidad)
            if ($faker->boolean(30)) {
                $numAlergias = $faker->numberBetween(1, 3);
                $alergiasSeleccionadas = $faker->randomElements($alergias, $numAlergias);
                foreach ($alergiasSeleccionadas as $alergia) {
                    AllergyRecord::create([
                        'medical_history_id' => $history->id,
                        'allergy_type' => $alergia,
                        'severity' => $faker->randomElement(['Leve', 'Moderada', 'Grave']),
                        'diagnosis_date' => $faker->dateTimeBetween('-10 years', 'now'),
                        'treatment' => $faker->randomElement([
                            'Antihistamínicos orales',
                            'Evitar exposición',
                            'Epinefrina de emergencia',
                            'Corticosteroides tópicos'
                        ])
                    ]);
                }
            }

            // Agregar cirugías aleatoriamente (20% de probabilidad)
            if ($faker->boolean(20)) {
                $numCirugias = $faker->numberBetween(1, 2);
                $cirugiasSeleccionadas = $faker->randomElements($cirugias, $numCirugias);
                foreach ($cirugiasSeleccionadas as $cirugia) {
                    SurgeryRecord::create([
                        'medical_history_id' => $history->id,
                        'surgery_type' => $cirugia,
                        'surgery_date' => $faker->dateTimeBetween('-5 years', 'now'),
                        'surgeon' => 'Dr. ' . $faker->name,
                        'hospital' => $faker->randomElement([
                            'Hospital Metropolitano',
                            'Clínica Internacional',
                            'Hospital Vozandes',
                            'Hospital de los Valles',
                            'Clínica Pasteur'
                        ]),
                        'description' => $faker->sentence(),
                        'complications' => $faker->boolean(20) ? $faker->sentence() : null
                    ]);
                }
            }
        }
    }
} 