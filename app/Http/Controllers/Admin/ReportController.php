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
        // Datos de género
        $genderData = Patient::groupBy('gender')
            ->selectRaw('gender, count(*) as total')
            ->pluck('total', 'gender')
            ->toArray();

        // Datos de edad
        $ageData = Patient::selectRaw('
            CASE 
                WHEN age(birth_date) < interval \'18 years\' THEN \'0-18\'
                WHEN age(birth_date) < interval \'30 years\' THEN \'19-30\'
                WHEN age(birth_date) < interval \'50 years\' THEN \'31-50\'
                WHEN age(birth_date) < interval \'70 years\' THEN \'51-70\'
                ELSE \'70+\'
            END as age_range,
            count(*) as total
        ')
        ->groupBy('age_range')
        ->pluck('total', 'age_range')
        ->toArray();

        // Datos de tipo de sangre
        $bloodTypeData = Patient::whereNotNull('blood_type')
            ->groupBy('blood_type')
            ->selectRaw('blood_type, count(*) as total')
            ->pluck('total', 'blood_type')
            ->toArray();

        // Datos de alergias
        $allergiesData = Patient::whereNotNull('allergies')
            ->groupBy('allergies')
            ->selectRaw('allergies, count(*) as total')
            ->pluck('total', 'allergies')
            ->toArray();

        return view('admin.reports.patient-demographics', compact(
            'genderData',
            'ageData',
            'bloodTypeData',
            'allergiesData'
        ));
    }
} 