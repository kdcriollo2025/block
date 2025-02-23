<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medico;
use App\Models\Patient;
use App\Models\MedicalHistory;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class InitialSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_EC');

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@empresa.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('password123'),
                'cedula' => '1716234578',
                'type' => 'admin',
                'first_login' => false,
            ]
        );

        // Crear médico
        $medicoUser = User::firstOrCreate(
            ['email' => 'jcmorales@empresa.com'],
            [
                'name' => 'Dr. Juan Carlos Morales',
                'password' => Hash::make('password123'),
                'cedula' => '1715678234',
                'type' => 'medico',
                'first_login' => false,
            ]
        );

        $medico = Medico::firstOrCreate(
            ['user_id' => $medicoUser->id],
            [
                'specialty' => 'Medicina Interna',
                'phone' => '0991234567',  // Cambiado de phone_number a 
                'cedula' => $medicoUser->cedula,
            ]
        );

        // Crear pacientes con datos ecuatorianos
        for ($i = 0; $i < 100; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $faker->firstName($gender);
            $lastName = $faker->lastName . ' ' . $faker->lastName;

            // Generar una cédula ecuatoriana válida
            $provincia = str_pad($faker->numberBetween(1, 24), 2, '0', STR_PAD_LEFT);
            $tercerDigito = $faker->numberBetween(0, 5);
            $numeroSecuencial = str_pad($faker->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT);
            $cedula = $provincia . $tercerDigito . $numeroSecuencial;
            $cedula = str_pad($cedula, 9, '0');

            // Calcular dígito verificador
            $suma = 0;
            for ($j = 0; $j < 9; $j++) {
                $multiplicador = ($j % 2 == 0) ? 2 : 1;
                $valor = intval($cedula[$j]) * $multiplicador;
                $suma += ($valor >= 10) ? $valor - 9 : $valor;
            }
            $digitoVerificador = ($suma % 10 === 0) ? 0 : 10 - ($suma % 10);
            $cedula .= $digitoVerificador;

            // Crear paciente
            $patient = Patient::create([
                'doctor_id' => $medico->id,
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName) . '.' . strtolower(explode(' ', $lastName)[0]) . '@gmail.com',
                'cedula' => $cedula,
                'phone' => '09' . $faker->numberBetween(80000000, 99999999),  // Asegurarse de usar 'phone' en lugar de 'phone_number'
                'address' => $faker->streetAddress . ', ' . $faker->randomElement([
                    'La Carolina', 'El Condado', 'La Mariscal', 'Quitumbe', 
                    'Cumbayá', 'El Batán', 'La González Suárez', 'San Carlos'
                ]) . ', Quito',
                'birth_date' => $faker->dateTimeBetween('-70 years', '-18 years'),
                'gender' => $gender == 'male' ? 'M' : 'F',
                'blood_type' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'allergies' => $faker->optional()->randomElement([
                    'Penicilina', 'Aspirina', 'Polen', 'Mariscos', 
                    'Nueces', 'Látex', 'Ninguna conocida'
                ])
            ]);

            // Crear historial médico
            if ($patient) {
                $this->createMedicalHistory($patient, $faker);
            }
        }
    }

    private function createMedicalHistory($patient, $faker)
    {
        return MedicalHistory::create([
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
    }
}