<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalConsultationRecord;
use App\Models\Medico;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->type !== 'admin') {
                return redirect()->route('home');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            return view('admin.reports.index');
        } catch (\Exception $e) {
            \Log::error('Error en página de reportes: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar la página de reportes');
        }
    }

    public function patientsPerDoctor()
    {
        try {
            $data = Medico::with('user')
                ->withCount('pacientes')
                ->get();

            if ($data->isEmpty()) {
                \Log::info('No hay médicos registrados');
            } else {
                \Log::info('Reporte generado. Total médicos: ' . $data->count());
            }

            return view('admin.reports.patients-per-doctor', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error en reporte de pacientes por doctor: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    public function commonDiagnoses(Request $request)
    {
        try {
            $query = MedicalConsultationRecord::query();

            if ($request->filled(['start_date', 'end_date'])) {
                $query->whereBetween('consultation_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            $data = $query->select('diagnosis', DB::raw('COUNT(*) as total'))
                ->groupBy('diagnosis')
                ->orderByDesc('total')
                ->get()
                ->map(function ($record) use ($query) {
                    $total_consultas = $query->count();
                    return [
                        'diagnostico' => $record->diagnosis,
                        'total_casos' => $record->total,
                        'porcentaje' => round(($record->total / $total_consultas) * 100, 2)
                    ];
                });

            return view('admin.reports.common-diagnoses', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error en reporte de diagnósticos comunes: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el reporte');
        }
    }

    public function consultationsOverTime(Request $request)
    {
        try {
            $query = MedicalConsultationRecord::query();

            if ($request->filled(['start_date', 'end_date'])) {
                $query->whereBetween('consultation_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            $data = $query->select(
                DB::raw('DATE(consultation_date) as fecha'),
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            return view('admin.reports.consultations-over-time', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error en reporte de consultas en el tiempo: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el reporte');
        }
    }

    public function patientDemographics()
    {
        try {
            $data = [
                'gender' => Patient::select('gender', DB::raw('COUNT(*) as total'))
                    ->groupBy('gender')
                    ->get(),
                'age_groups' => Patient::select(
                    DB::raw('
                        CASE 
                            WHEN age < 18 THEN "0-17"
                            WHEN age BETWEEN 18 AND 30 THEN "18-30"
                            WHEN age BETWEEN 31 AND 50 THEN "31-50"
                            WHEN age BETWEEN 51 AND 70 THEN "51-70"
                            ELSE "71+"
                        END as age_group
                    '),
                    DB::raw('COUNT(*) as total')
                )
                    ->groupBy('age_group')
                    ->orderBy('age_group')
                    ->get()
            ];

            return view('admin.reports.patient-demographics', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error en reporte demográfico: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el reporte');
        }
    }
} 