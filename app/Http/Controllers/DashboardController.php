<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;

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
            // Obtener el médico actual
            $medico = Auth::user()->medico;

            // Obtener los pacientes del médico actual con sus historiales médicos
            $patients = Patient::where('doctor_id', $medico->id)
                ->with(['medicalHistory', 'medicalConsultations'])
                ->get();

            // Retornar la vista con la lista de pacientes
            return view('medicos.index', compact('patients'));

        } catch (\Exception $e) {
            Log::error('Error en dashboard médico: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al cargar la lista de pacientes');
        }
    }
}