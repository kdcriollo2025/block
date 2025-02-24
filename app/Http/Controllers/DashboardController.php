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
        $this->middleware('auth');
        $this->middleware('medico');
    }

    public function index()
    {
        try {
            if (!Auth::user()->medico) {
                \Log::error('Usuario médico sin registro en tabla medicos');
                return redirect()->route('login')->with('error', 'Error de configuración de cuenta');
            }

            // Obtener datos necesarios para la vista
            $medico = Auth::user()->medico;
            $patients = Patient::where('doctor_id', $medico->id)->get();

            // Retornar la vista correcta
            return view('medicos.index', compact('patients'));

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->route('login')->with('error', 'Error al cargar el dashboard');
        }
    }
}