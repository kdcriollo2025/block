<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
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
        return view('admin.reports.index');
    }

    public function patientsPerDoctor(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        $report = DB::table('medical_consultation_records')
            ->join('medical_histories', 'medical_consultation_records.medical_history_id', '=', 'medical_histories.id')
            ->join('patients', 'medical_histories.patient_id', '=', 'patients.id')
            ->join('medicos', 'patients.doctor_id', '=', 'medicos.id')
            ->whereBetween('medical_consultation_records.consultation_date', [$startDate, $endDate])
            ->select(
                'medicos.name as doctor_name',
                DB::raw('COUNT(DISTINCT patients.id) as total_patients'),
                DB::raw('COUNT(medical_consultation_records.id) as total_consultations')
            )
            ->groupBy('medicos.id', 'medicos.name')
            ->get();

        return view('admin.reports.patients_per_doctor', compact('report', 'startDate', 'endDate'));
    }

    public function commonDiagnoses(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        $report = DB::table('medical_consultation_records')
            ->whereBetween('consultation_date', [$startDate, $endDate])
            ->select('diagnosis', DB::raw('COUNT(*) as total'))
            ->groupBy('diagnosis')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.reports.common_diagnoses', compact('report', 'startDate', 'endDate'));
    }

    public function consultationsOverTime(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonths(6);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        $report = DB::table('medical_consultation_records')
            ->whereBetween('consultation_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_TRUNC(\'month\', consultation_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::parse($item->month)->format('Y-m'),
                    'total' => $item->total
                ];
            });

        return view('admin.reports.consultations_over_time', compact('report', 'startDate', 'endDate'));
    }

    public function patientDemographics()
    {
        $genderDistribution = DB::table('patients')
            ->select('gender', DB::raw('COUNT(*) as total'))
            ->groupBy('gender')
            ->get();

        $ageGroups = DB::table('patients')
            ->select(DB::raw('
                CASE 
                    WHEN age(birth_date) < interval \'18 years\' THEN \'0-17\'
                    WHEN age(birth_date) < interval \'30 years\' THEN \'18-29\'
                    WHEN age(birth_date) < interval \'50 years\' THEN \'30-49\'
                    WHEN age(birth_date) < interval \'70 years\' THEN \'50-69\'
                    ELSE \'70+\'
                END as age_group
            '), DB::raw('COUNT(*) as total'))
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        return view('admin.reports.patient_demographics', compact('genderDistribution', 'ageGroups'));
    }
} 