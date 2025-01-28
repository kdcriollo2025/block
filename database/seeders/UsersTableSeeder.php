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
        $admin = User::create([
            'name' => 'Admin ESPE',
            'email' => 'admin@espe.edu.ec',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'type' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Asignar rol
        $admin->assignRole($adminRole);
    }
}
