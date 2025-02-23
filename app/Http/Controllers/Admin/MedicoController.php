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
    public function index()
    {
        $medicos = Medico::all();
        return view('admin.medicos.index', compact('medicos'));
    }

    public function create()
    {
        return view('admin.medicos.form');
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
                'specialty' => 'required|string',
                'phone_number' => 'required|string',
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

            // Crear el registro en la tabla médicos
            if ($user) {
                Medico::create([
                    'user_id' => $user->id,
                    'specialty' => $validated['specialty'],
                    'phone_number' => $validated['phone_number'],
                    'cedula' => $validated['cedula'],
                ]);
            }

            return redirect()
                ->route('admin.medicos.index')
                ->with('success', 'Médico registrado exitosamente');

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error al crear médico: ' . $e->getMessage());
            
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