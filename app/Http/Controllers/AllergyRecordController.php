<?php

namespace App\Http\Controllers;

use App\Models\AllergyRecord;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllergyRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'medico') {
                return redirect()->route('home');
            }
            return $next($request);
        });
    }
    
    public function index()
    {
        $allergyRecords = AllergyRecord::whereHas('medicalHistory.patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('allergy_records.index', compact('allergyRecords'));
    }

    public function create()
    {
        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('allergy_records.form', compact('medicalHistories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'allergy_name' => 'required|string|max:100',
            'severity_level' => 'required|string|max:50',
            'allergy_symptoms' => 'required|string|max:255',
            'diagnosis_date' => 'required|date'
        ]);

        // Verificar que la historia médica pertenece a un paciente del médico actual
        $medicalHistory = MedicalHistory::findOrFail($request->medical_history_id);
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.allergy_records.index')
                ->with('error', 'No tiene permiso para acceder a este historial médico.');
        }

        AllergyRecord::create([
            'medical_history_id' => $request->medical_history_id,
            'allergy_name' => $request->allergy_name,
            'severity_level' => $request->severity_level,
            'allergy_symptoms' => $request->allergy_symptoms,
            'diagnosis_date' => $request->diagnosis_date
        ]);

        return redirect()->route('medico.allergy_records.index')
            ->with('success', 'Registro de alergia creado exitosamente.');
    }

    public function show(AllergyRecord $allergyRecord)
    {
        if ($allergyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.allergy_records.index')
                ->with('error', 'No tiene permiso para ver este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('allergy_records.form', compact('allergyRecord', 'medicalHistories'));
    }

    public function edit(AllergyRecord $allergyRecord)
    {
        if ($allergyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.allergy_records.index')
                ->with('error', 'No tiene permiso para editar este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('allergy_records.form', compact('allergyRecord', 'medicalHistories'));
    }

    public function update(Request $request, AllergyRecord $allergyRecord)
    {
        if ($allergyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.allergy_records.index')
                ->with('error', 'No tiene permiso para actualizar este registro.');
        }

        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'allergy_name' => 'required|string|max:100',
            'severity_level' => 'required|string|max:50',
            'allergy_symptoms' => 'required|string|max:255',
            'diagnosis_date' => 'required|date'
        ]);

        $allergyRecord->update([
            'medical_history_id' => $request->medical_history_id,
            'allergy_name' => $request->allergy_name,
            'severity_level' => $request->severity_level,
            'allergy_symptoms' => $request->allergy_symptoms,
            'diagnosis_date' => $request->diagnosis_date
        ]);

        return redirect()->route('medico.allergy_records.index')
            ->with('success', 'Registro de alergia actualizado exitosamente.');
    }

    public function destroy(AllergyRecord $allergyRecord)
    {
        if ($allergyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.allergy_records.index')
                ->with('error', 'No tiene permiso para eliminar este registro.');
        }

        $allergyRecord->delete();
        return redirect()->route('medico.allergy_records.index')
            ->with('success', 'Registro de alergia eliminado exitosamente.');
    }
}
