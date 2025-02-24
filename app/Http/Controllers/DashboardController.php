<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            $user = auth()->user();
            
            if ($user->type !== 'medico') {
                return redirect()->route('admin.medicos.index');
            }

            $medico = $user->medico;
            
            if (!$medico) {
                \Log::error('Médico no encontrado para el usuario: ' . $user->id);
                return redirect()->route('login')->with('error', 'No se encontró información del médico');
            }

            $data = [
                'medico' => $medico,
                'totalPatients' => Patient::where('doctor_id', $medico->id)->count(),
                'currentDate' => now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'currentTime' => now()->format('h:i A'),
            ];

            return view('medico.dashboard', $data);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 