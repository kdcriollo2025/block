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
        try {
            $medicos = Medico::with('user')
                ->select('medicos.*') // Selecciona explícitamente las columnas
                ->get();

            return view('admin.medicos.index', compact('medicos'));
        } catch (\Exception $e) {
            \Log::error('Error en MedicoController@index: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al cargar la lista de médicos');
        }
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
                'phone' => 'required|string',
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
                    'phone' => $validated['phone'],
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
        try {
            $medico->load('user');  // Asegurarnos de cargar la relación user
            
            // No necesitamos crear un nuevo objeto, pasamos el modelo directamente
            return view('admin.medicos.form', compact('medico'));
            
        } catch (\Exception $e) {
            \Log::error('Error en MedicoController@edit: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al cargar el formulario de edición');
        }
    }

    public function update(Request $request, Medico $medico)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $medico->user_id,
                'specialty' => 'required|string',
                'phone' => 'required|string',
                'password' => 'nullable|min:8|confirmed'
            ]);

            // Actualizar usuario
            $userData = [
                'email' => $validated['email']
            ];

            // Si se proporcionó una nueva contraseña, actualizarla
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $medico->user->update($userData);

            // Actualizar médico
            $medico->update([
                'specialty' => $validated['specialty'],
                'phone' => $validated['phone']
            ]);

            return redirect()
                ->route('admin.medicos.index')
                ->with('success', 'Médico actualizado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar médico: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el médico');
        }
    }

    public function destroy(Medico $medico)
    {
        // Implement the logic to delete a medico
    }

    public function toggleEstado(Medico $medico)
    {
        $medico->estado = !$medico->estado;
        $medico->save();

        return response()->json(['success' => true]);
    }
} 