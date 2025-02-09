<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Asegurarse de que el rol existe
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'admin']);
        }

        // Crear usuario administrador
        User::create([
            'name' => 'Admin ESPE',
            'email' => 'admin@espe.edu.ec',
            'cedula' => '1234567890',
            'password' => Hash::make('12345678'),
            'type' => 'admin',
            'first_login' => false,
            'email_verified_at' => now(),
        ]);

        // Crear usuario médico de ejemplo
        User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'medico@espe.edu.ec',
            'cedula' => '0987654321',
            'password' => Hash::make('12345678'),
            'type' => 'medico',
            'first_login' => false,
            'email_verified_at' => now(),
        ]);

        // Asignar rol
        $admin = User::where('email', 'admin@espe.edu.ec')->first();
        if ($admin) {
            $admin->assignRole($adminRole);
        }
    }
}
