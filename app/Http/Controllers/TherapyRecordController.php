<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\TherapyRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TherapyRecordController extends Controller
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
        $therapyRecords = TherapyRecord::whereHas('medicalHistory.patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('therapy_records.index', compact('therapyRecords'));
    }

    public function create()
    {
        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('therapy_records.form', compact('medicalHistories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'detail' => 'required|string|max:255',
        ]);

        // Verificar que la historia médica pertenece a un paciente del médico actual
        $medicalHistory = MedicalHistory::findOrFail($request->medical_history_id);
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.therapy_records.index')
                ->with('error', 'No tiene permiso para acceder a este historial médico.');
        }

        TherapyRecord::create($request->all());
        return redirect()->route('medico.therapy_records.index')
            ->with('success', 'Registro de terapia creado exitosamente.');
    }

    public function show(TherapyRecord $therapyRecord)
    {
        if ($therapyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.therapy_records.index')
                ->with('error', 'No tiene permiso para ver este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('therapy_records.form', compact('therapyRecord', 'medicalHistories'));
    }

    public function edit(TherapyRecord $therapyRecord)
    {
        if ($therapyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.therapy_records.index')
                ->with('error', 'No tiene permiso para editar este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('therapy_records.form', compact('therapyRecord', 'medicalHistories'));
    }

    public function update(Request $request, TherapyRecord $therapyRecord)
    {
        if ($therapyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.therapy_records.index')
                ->with('error', 'No tiene permiso para actualizar este registro.');
        }

        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'detail' => 'required|string|max:255',
        ]);

        $therapyRecord->update($request->all());
        return redirect()->route('medico.therapy_records.index')
            ->with('success', 'Registro de terapia actualizado exitosamente.');
    }

    public function destroy(TherapyRecord $therapyRecord)
    {
        if ($therapyRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.therapy_records.index')
                ->with('error', 'No tiene permiso para eliminar este registro.');
        }

        $therapyRecord->delete();
        return redirect()->route('medico.therapy_records.index')
            ->with('success', 'Registro de terapia eliminado exitosamente.');
    }
}
