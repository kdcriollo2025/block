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
            
            // Verificar si el usuario es médico
            if ($user->type !== 'medico') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            // Obtener el médico
            $medico = Medico::where('user_id', $user->id)->first();
            
            if (!$medico) {
                return redirect()->route('home')->with('error', 'No se encontró información del médico');
            }

            // Datos mínimos
            return view('medico.dashboard', [
                'medico' => $medico,
                'currentDate' => date('l, d \d\e F \d\e Y'),
                'currentTime' => date('h:i A'),
                'totalPatients' => 0
            ]);

        } catch (\Exception $e) {
            throw $e; // En desarrollo, mostrar el error completo
        }
    }
} 