<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Obtener usuario
            $user = Auth::user();
            Log::info('1. Usuario autenticado', ['user' => $user->toArray()]);

            // Obtener médico con la relación user
            $medico = Medico::with('user')
                ->where('user_id', $user->id)
                ->first();

            Log::info('2. Médico encontrado', [
                'medico' => $medico ? $medico->toArray() : null,
                'estado' => $medico ? $medico->estado : null
            ]);

            if (!$medico || !$medico->estado) {
                Log::error('Médico no encontrado o inactivo', [
                    'user_id' => $user->id,
                    'estado' => $medico ? $medico->estado : null
                ]);
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            // 3. Retornar vista con datos mínimos
            return view('medico.dashboard', [
                'medico' => $medico,
                'totalPatients' => 0,
                'totalConsultations' => 0,
                'currentDate' => now()->format('l, d \d\e F \d\e Y'),
                'currentTime' => now()->format('h:i A')
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // En desarrollo, mostrar el error
            if (app()->environment('local')) {
                dd($e->getMessage());
            }
            
            return redirect()->route('home')->with('error', 'Error al cargar el dashboard');
        }
    }
} 