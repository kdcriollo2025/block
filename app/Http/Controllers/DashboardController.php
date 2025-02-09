<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
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
            // Obtener el médico actual
            $medico = Auth::user()->medico;

            // Obtener el total de pacientes
            $totalPatients = Patient::where('doctor_id', $medico->id)->count();

            // Obtener el total de consultas médicas
            $totalConsultations = MedicalConsultationRecord::whereHas('medicalHistory.patient', function($query) use ($medico) {
                $query->where('doctor_id', $medico->id);
            })->count();

            // Obtener consultas por mes (últimos 6 meses)
            $consultationsByMonth = DB::table('medical_consultation_records')
                ->join('medical_histories', 'medical_consultation_records.medical_history_id', '=', 'medical_histories.id')
                ->join('patients', 'medical_histories.patient_id', '=', 'patients.id')
                ->where('patients.doctor_id', $medico->id)
                ->where('consultation_date', '>=', DB::raw('CURRENT_DATE - INTERVAL \'6 months\''))
                ->select(
                    DB::raw('DATE_TRUNC(\'month\', consultation_date) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    return [
                        'month' => date('F Y', strtotime($item->month)),
                        'total' => $item->total
                    ];
                });

            return view('medico.dashboard', compact(
                'medico',
                'totalPatients',
                'totalConsultations',
                'consultationsByMonth'
            ));

        } catch (\Exception $e) {
            // Log el error
            \Log::error('Error en DashboardController: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Retornar una vista de error o redireccionar
            return response()->view('errors.500', [], 500);
        }
    }
} 