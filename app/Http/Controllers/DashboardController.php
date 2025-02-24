<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Guayaquil');
        Carbon::setLocale('es');
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            // Verificar que el usuario sea médico
            if ($user->type !== 'medico') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            $medico = $user->medico;
            
            if (!$medico) {
                \Log::error('Médico no encontrado para usuario: ' . $user->id);
                return redirect()->route('home')->with('error', 'No se encontró información del médico');
            }

            // Datos básicos
            $data = [
                'medico' => $medico,
                'totalPatients' => $medico->patients()->count(),
                'totalConsultations' => $medico->consultations()->count(),
                'currentDate' => Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'currentTime' => Carbon::now()->format('h:i A')
            ];

            return view('medico.dashboard', $data);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 