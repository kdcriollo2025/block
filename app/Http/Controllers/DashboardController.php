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
    }

    public function index()
    {
        try {
            $medico = Auth::user()->medico;
            
            // Obtener estadísticas básicas
            $totalPatients = Patient::where('doctor_id', $medico->id)->count();

            // Obtener fecha y hora actual
            $currentDate = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
            $currentTime = Carbon::now()->format('h:i A');

            return view('medico.dashboard', compact(
                'medico',
                'totalPatients',
                'currentDate',
                'currentTime'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 