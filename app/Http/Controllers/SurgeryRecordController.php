<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\SurgeryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurgeryRecordController extends Controller
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
        $surgeryRecords = SurgeryRecord::whereHas('medicalHistory.patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('surgery_records.index', compact('surgeryRecords'));
    }

    public function create()
    {
        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('surgery_records.form', compact('medicalHistories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'surgery_name' => 'required|string|max:50',
            'surgeon' => 'required|string|max:100',
            'surgery_date' => 'required|date',
            'details' => 'required|string|max:255',
        ]);

        // Verificar que la historia médica pertenece a un paciente del médico actual
        $medicalHistory = MedicalHistory::findOrFail($request->medical_history_id);
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.surgery_records.index')
                ->with('error', 'No tiene permiso para acceder a este historial médico.');
        }

        SurgeryRecord::create([
            'medical_history_id' => $request->medical_history_id,
            'surgery_name' => $request->surgery_name,
            'surgeon' => $request->surgeon,
            'surgery_date' => $request->surgery_date,
            'details' => $request->details,
        ]);

        return redirect()->route('medico.surgery_records.index')
            ->with('success', 'Registro de cirugía creado exitosamente.');
    }

    public function show(SurgeryRecord $surgeryRecord)
    {
        if ($surgeryRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.surgery_records.index')
                ->with('error', 'No tiene permiso para ver este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('surgery_records.form', compact('surgeryRecord', 'medicalHistories'));
    }

    public function edit(SurgeryRecord $surgeryRecord)
    {
        if ($surgeryRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.surgery_records.index')
                ->with('error', 'No tiene permiso para editar este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('surgery_records.form', compact('surgeryRecord', 'medicalHistories'));
    }

    public function update(Request $request, SurgeryRecord $surgeryRecord)
    {
        if ($surgeryRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.surgery_records.index')
                ->with('error', 'No tiene permiso para actualizar este registro.');
        }

        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'surgery_name' => 'required|string|max:50',
            'surgeon' => 'required|string|max:100',
            'surgery_date' => 'required|date',
            'details' => 'required|string|max:255',
        ]);

        $surgeryRecord->update([
            'medical_history_id' => $request->medical_history_id,
            'surgery_name' => $request->surgery_name,
            'surgeon' => $request->surgeon,
            'surgery_date' => $request->surgery_date,
            'details' => $request->details,
        ]);

        return redirect()->route('medico.surgery_records.index')
            ->with('success', 'Registro de cirugía actualizado exitosamente.');
    }

    public function destroy(SurgeryRecord $surgeryRecord)
    {
        if ($surgeryRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.surgery_records.index')
                ->with('error', 'No tiene permiso para eliminar este registro.');
        }

        $surgeryRecord->delete();
        return redirect()->route('medico.surgery_records.index')
            ->with('success', 'Registro de cirugía eliminado exitosamente.');
    }
}
