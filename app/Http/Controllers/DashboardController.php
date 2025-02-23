<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Medico;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Establecer la zona horaria para Quito
        date_default_timezone_set('America/Guayaquil');
        Carbon::setLocale('es');
        
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'medico') {
                return redirect()->route('home');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            $user = Auth::user();
            \Log::info('Usuario autenticado:', ['id' => $user->id, 'type' => $user->type]);

            // Verificar si el usuario es médico
            if ($user->type !== 'medico') {
                \Log::error('Usuario no es médico:', ['type' => $user->type]);
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            // Obtener el médico sin eager loading primero
            $medico = Medico::where('user_id', $user->id)->first();
            \Log::info('Datos del médico:', ['medico' => $medico]);

            if (!$medico) {
                \Log::error('Médico no encontrado para el usuario: ' . $user->id);
                return redirect()->route('home')->with('error', 'No se encontró información del médico');
            }

            // Datos básicos
            $data = [
                'medico' => $medico,
                'totalPatients' => Patient::where('doctor_id', $medico->id)->count(),
                'currentDate' => now()->format('l, d \d\e F \d\e Y'),
                'currentTime' => now()->format('h:i A')
            ];

            return view('medico.dashboard', $data);

        } catch (\Exception $e) {
            // En desarrollo, mostrar el error completo
            if (config('app.debug')) {
                throw $e;
            }
            
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 