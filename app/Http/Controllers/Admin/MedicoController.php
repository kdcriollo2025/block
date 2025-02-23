<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MedicoController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:medicos'],
            'cedula' => ['required', 'string', 'size:10', 'unique:medicos'],
            'specialty' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:15'],
        ]);
    }

    public function index()
    {
        // Implement the logic to retrieve and display a list of medicos
    }

    public function create()
    {
        // Implement the logic to display the form for creating a new medico
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cedula' => 'required|string|unique:users',
                'especialidad' => 'required|string',
                'telefono' => 'required|string',
            ]);

            // Crear el usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'cedula' => $validated['cedula'],
                'type' => 'medico',
                'first_login' => true,
            ]);

            // Crear el registro en la tabla médicos si existe
            if ($user) {
                Medico::create([
                    'user_id' => $user->id,
                    'especialidad' => $validated['especialidad'],
                    'telefono' => $validated['telefono'],
                    'cedula' => $validated['cedula'],
                ]);
            }

            // Redireccionar con mensaje de éxito
            return redirect()
                ->route('admin.medicos.index')
                ->with('success', 'Médico registrado exitosamente');

        } catch (\Exception $e) {
            // En caso de error, regresar con mensaje de error
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al registrar el médico: ' . $e->getMessage());
        }
    }

    public function show(Medico $medico)
    {
        // Implement the logic to retrieve and display a specific medico
    }

    public function edit(Medico $medico)
    {
        // Implement the logic to retrieve and display the form for editing a medico
    }

    public function update(Request $request, Medico $medico)
    {
        // Implement the logic to handle the form submission and update an existing medico
    }

    public function destroy(Medico $medico)
    {
        // Implement the logic to delete a medico
    }
} 