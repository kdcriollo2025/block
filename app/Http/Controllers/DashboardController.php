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
            
            // Debug paso 1: Verificar usuario
            \Log::info('Usuario autenticado:', [
                'id' => $user->id,
                'type' => $user->type,
                'name' => $user->name
            ]);

            // Debug paso 2: Verificar relación médico
            $medico = DB::table('medicos')
                ->select('medicos.*', 'users.name as user_name')
                ->join('users', 'users.id', '=', 'medicos.user_id')
                ->where('medicos.user_id', $user->id)
                ->first();

            \Log::info('Datos del médico desde DB:', ['medico' => $medico]);

            if (!$medico) {
                \Log::error('Médico no encontrado para usuario: ' . $user->id);
                return redirect()->route('home')->with('error', 'No se encontró información del médico');
            }

            // Debug paso 3: Verificar conteos
            $totalPatients = DB::table('patients')
                ->where('doctor_id', $medico->id)
                ->count();

            $totalConsultations = DB::table('medical_consultation_records')
                ->join('medical_histories', 'medical_consultation_records.medical_history_id', '=', 'medical_histories.id')
                ->join('patients', 'medical_histories.patient_id', '=', 'patients.id')
                ->where('patients.doctor_id', $medico->id)
                ->count();

            \Log::info('Conteos:', [
                'patients' => $totalPatients,
                'consultations' => $totalConsultations
            ]);

            return view('medico.dashboard', [
                'medico' => $medico,
                'totalPatients' => $totalPatients,
                'totalConsultations' => $totalConsultations,
                'currentDate' => Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'currentTime' => Carbon::now()->format('h:i A')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e; // En desarrollo, mostrar el error completo
        }
    }
} 