<?php

namespace App\Http\Controllers;

use App\Models\MedicalConsultationRecord;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalConsultationRecordController extends Controller
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
        $medicalConsultationRecords = MedicalConsultationRecord::whereHas('medicalHistory.patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('medical_consultation_records.index', compact('medicalConsultationRecords'));
    }

    public function create(MedicalHistory $medicalHistory)
    {
        // Verificar que el médico actual puede acceder a esta historia médica
        if ($medicalHistory->patient->doctor_id !== auth()->user()->medico->id) {
            return redirect()->back()->with('error', 'No tiene permiso para acceder a esta historia médica');
        }

        return view('medical_consultation_records.form', compact('medicalHistory'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'medical_history_id' => 'required|exists:medical_histories,id',
                'consultation_date' => 'required|date',
                'reason' => 'nullable|string|max:255',
                'symptoms' => 'required|string|max:255',
                'diagnosis' => 'required|string|max:255',
                'treatment' => 'required|string|max:255',
                'next_appointment' => 'nullable|date',
            ]);

            // Agregar el ID del médico actual
            $validated['doctor_id'] = auth()->user()->medico->id;

            // Imprimir los datos para debug
            dd([
                'validated_data' => $validated,
                'request_all' => $request->all(),
                'auth_user' => auth()->user(),
                'medico' => auth()->user()->medico
            ]);

            $record = MedicalConsultationRecord::create($validated);
            
            return redirect()->back()->with('success', 'Consulta médica registrada exitosamente.');
        } catch (\Exception $e) {
            // Mostrar el error específico
            dd([
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error al registrar la consulta médica: ' . $e->getMessage());
        }
    }

    public function show(MedicalConsultationRecord $medicalConsultationRecord)
    {
        if ($medicalConsultationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_consultation_records.index')
                ->with('error', 'No tiene permiso para ver este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('medical_consultation_records.form', compact('medicalConsultationRecord', 'medicalHistories'));
    }

    public function edit(MedicalConsultationRecord $medicalConsultationRecord)
    {
        if ($medicalConsultationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_consultation_records.index')
                ->with('error', 'No tiene permiso para editar este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('medical_consultation_records.form', compact('medicalConsultationRecord', 'medicalHistories'));
    }

    public function update(Request $request, MedicalConsultationRecord $medicalConsultationRecord)
    {
        if ($medicalConsultationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_consultation_records.index')
                ->with('error', 'No tiene permiso para actualizar este registro.');
        }

        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'consultation_date' => 'required|date',
            'reported_symptoms' => 'required|string|max:300',
            'diagnosis' => 'required|string|max:300',
            'treatment' => 'required|string|max:300',
        ]);

        $medicalConsultationRecord->update([
            'medical_history_id' => $request->medical_history_id,
            'doctor_id' => Auth::user()->medico->id,
            'consultation_date' => $request->consultation_date,
            'reported_symptoms' => $request->reported_symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        return redirect()->route('medico.medical_consultation_records.index')
            ->with('success', 'Registro de consulta médica actualizado exitosamente.');
    }

    public function destroy(MedicalConsultationRecord $medicalConsultationRecord)
    {
        if ($medicalConsultationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_consultation_records.index')
                ->with('error', 'No tiene permiso para eliminar este registro.');
        }

        $medicalConsultationRecord->delete();
        return redirect()->route('medico.medical_consultation_records.index')
            ->with('success', 'Registro de consulta médica eliminado exitosamente.');
    }
}
