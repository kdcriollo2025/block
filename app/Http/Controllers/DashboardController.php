<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = Auth::user();

            if ($user->type === 'admin') {
                return redirect()->route('admin.medicos.index');
            }

            // Si es médico
            if ($user->type === 'medico') {
                $medico = $user->medico;
                
                if (!$medico) {
                    Log::error('Médico no encontrado para el usuario: ' . $user->id);
                    return redirect()->route('home')
                        ->with('error', 'No se encontró el perfil de médico.');
                }

                // Obtener estadísticas
                $totalPatients = Patient::where('doctor_id', $medico->id)->count();

                $totalConsultations = MedicalConsultationRecord::whereHas('medicalHistory.patient', function($query) use ($medico) {
                    $query->where('doctor_id', $medico->id);
                })->count();

                // Consultas por mes (últimos 6 meses)
                $consultationsByMonth = DB::table('medical_consultation_records')
                    ->join('medical_histories', 'medical_consultation_records.medical_history_id', '=', 'medical_histories.id')
                    ->join('patients', 'medical_histories.patient_id', '=', 'patients.id')
                    ->where('patients.doctor_id', $medico->id)
                    ->where('medical_consultation_records.created_at', '>=', now()->subMonths(6))
                    ->select(
                        DB::raw('DATE_TRUNC(\'month\', medical_consultation_records.created_at) as month'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                return view('medico.dashboard', compact(
                    'medico',
                    'totalPatients',
                    'totalConsultations',
                    'consultationsByMonth'
                ));
            }

            return redirect()->route('home');

        } catch (\Exception $e) {
            Log::error('Error en DashboardController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->route('home')
                ->with('error', 'Ha ocurrido un error. Por favor, intenta de nuevo.');
        }
    }

    public function consultations()
    {
        try {
            $medico = auth()->user()->medico;
            if (!$medico) {
                throw new \Exception('Perfil de médico no encontrado');
            }
            
            $consultations = $medico->medicalConsultations()
                ->with(['patient', 'medicalHistory'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            return view('dashboard.consultations', compact('consultations'));
        } catch (\Exception $e) {
            Log::error('Error en consultations: ' . $e->getMessage());
            return back()->with('error', 'Ha ocurrido un error al cargar las consultas.');
        }
    }
} 