<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Verificar autenticación
            if (!Auth::check()) {
                Log::error('Usuario no autenticado');
                return redirect()->route('login');
            }

            $user = Auth::user();
            Log::info('Usuario autenticado', [
                'id' => $user->id,
                'type' => $user->type
            ]);

            // 2. Verificar tipo de usuario
            if ($user->type !== 'medico') {
                Log::error('Usuario no es médico', [
                    'id' => $user->id,
                    'type' => $user->type
                ]);
                return redirect()->route('home');
            }

            // 3. Obtener médico
            $medico = Medico::where('user_id', $user->id)
                           ->where('estado', true)
                           ->first();

            if (!$medico) {
                Log::error('Médico no encontrado', ['user_id' => $user->id]);
                return redirect()->route('home');
            }

            // 4. Verificar que la vista existe
            if (!View::exists('medico.dashboard')) {
                Log::error('Vista no encontrada: medico.dashboard');
                return redirect()->route('home');
            }

            // 5. Retornar vista
            return view('medico.dashboard', [
                'medico' => $medico,
                'totalPatients' => 0,
                'totalConsultations' => 0,
                'currentDate' => date('l, d \d\e F \d\e Y'),
                'currentTime' => date('h:i A')
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if (app()->environment('local')) {
                throw $e;
            }

            return redirect()->route('home')->with('error', 'Error al cargar el dashboard');
        }
    }
} 