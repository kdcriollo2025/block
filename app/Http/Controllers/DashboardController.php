<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            Log::info('1. Usuario autenticado', ['user' => $user->toArray()]);

            // Verificar si el usuario existe en la tabla médicos
            $medicoQuery = DB::select("
                SELECT m.*, u.name as doctor_name 
                FROM medicos m 
                JOIN users u ON u.id = m.user_id 
                WHERE m.user_id = ?
            ", [$user->id]);

            Log::info('2. Query médico', [
                'sql' => "SELECT m.*, u.name FROM medicos m JOIN users u ON u.id = m.user_id WHERE m.user_id = {$user->id}",
                'result' => $medicoQuery
            ]);

            if (empty($medicoQuery)) {
                Log::error('3. Médico no encontrado', ['user_id' => $user->id]);
                return redirect()->route('home')->with('error', 'No se encontró información del médico');
            }

            $medico = $medicoQuery[0];
            
            // Contar pacientes
            $totalPatients = DB::select("
                SELECT COUNT(*) as total 
                FROM patients 
                WHERE doctor_id = ?
            ", [$medico->id])[0]->total;

            Log::info('4. Total pacientes', ['total' => $totalPatients]);

            // Contar consultas
            $totalConsultations = DB::select("
                SELECT COUNT(*) as total 
                FROM medical_consultation_records mcr
                JOIN medical_histories mh ON mh.id = mcr.medical_history_id
                JOIN patients p ON p.id = mh.patient_id
                WHERE p.doctor_id = ?
            ", [$medico->id])[0]->total;

            Log::info('5. Total consultas', ['total' => $totalConsultations]);

            // Preparar datos para la vista
            $data = [
                'medico' => $medico,
                'totalPatients' => $totalPatients,
                'totalConsultations' => $totalConsultations,
                'currentDate' => Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'currentTime' => Carbon::now()->format('h:i A')
            ];

            Log::info('6. Datos para la vista', $data);

            return view('medico.dashboard', $data);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 