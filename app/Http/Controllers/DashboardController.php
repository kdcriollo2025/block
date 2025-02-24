<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Debug información del usuario
            Log::info('Dashboard access attempt', [
                'user_id' => $user->id,
                'user_type' => $user->type,
                'user_email' => $user->email
            ]);

            // Verificar si la vista existe
            if (!View::exists('medico.dashboard')) {
                Log::error('Vista no encontrada', [
                    'view' => 'medico.dashboard',
                    'views_path' => resource_path('views/medico')
                ]);
                throw new \Exception('Vista del dashboard no encontrada');
            }

            $medico = Medico::where('user_id', $user->id)->first();
            
            // Debug información del médico
            Log::info('Médico encontrado', [
                'medico_id' => $medico->id ?? null,
                'specialty' => $medico->specialty ?? null,
                'estado' => $medico->estado ?? null
            ]);

            if (!$medico) {
                Log::error('Médico no encontrado', ['user_id' => $user->id]);
                return redirect()->route('home')->with('error', 'Información de médico no encontrada');
            }

            $data = [
                'medico' => $medico,
                'totalPatients' => $medico->patients()->count(),
                'totalConsultations' => $medico->consultations()->count(),
                'currentDate' => now()->locale('es')->format('l, d \d\e F \d\e Y'),
                'currentTime' => now()->format('h:i A')
            ];

            // Debug datos enviados a la vista
            Log::info('Datos para la vista', $data);

            return view('medico.dashboard', $data);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (app()->environment('local')) {
                throw $e;
            }
            
            return redirect()->route('home')
                ->with('error', 'Error al cargar el dashboard');
        }
    }
} 