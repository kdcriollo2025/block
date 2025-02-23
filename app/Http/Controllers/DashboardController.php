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
            $medico = Auth::user()->medico;
            
            // Obtener estadísticas básicas
            $totalPatients = Patient::where('doctor_id', $medico->id)->count();
            $totalConsultations = MedicalConsultationRecord::whereHas('medicalHistory.patient', function($query) use ($medico) {
                $query->where('doctor_id', $medico->id);
            })->count();

            // Obtener fecha y hora actual
            $currentDate = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
            $currentTime = Carbon::now()->format('h:i A');

            // Consultas por mes (últimos 6 meses)
            $consultationsByMonth = DB::table('medical_consultation_records')
                ->join('medical_histories', 'medical_consultation_records.medical_history_id', '=', 'medical_histories.id')
                ->join('patients', 'medical_histories.patient_id', '=', 'patients.id')
                ->where('patients.doctor_id', $medico->id)
                ->where('consultation_date', '>=', now()->subMonths(6))
                ->select(DB::raw('DATE_TRUNC(\'month\', consultation_date) as month'), DB::raw('COUNT(*) as total'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    return [
                        'month' => Carbon::parse($item->month)->locale('es')->isoFormat('MMMM YYYY'),
                        'total' => $item->total
                    ];
                });

            // Consultas recientes
            $recentConsultations = MedicalConsultationRecord::whereHas('medicalHistory.patient', function($query) use ($medico) {
                $query->where('doctor_id', $medico->id);
            })
            ->with(['medicalHistory.patient'])
            ->orderBy('consultation_date', 'desc')
            ->take(5)
            ->get();

            return view('medico.dashboard', compact(
                'medico',
                'totalPatients',
                'totalConsultations',
                'currentDate',
                'currentTime',
                'consultationsByMonth',
                'recentConsultations'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 