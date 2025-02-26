<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccinationRecordController extends Controller
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
        $vaccinationRecords = VaccinationRecord::whereHas('medicalHistory.patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('vaccination_records.index', compact('vaccinationRecords'));
    }

    public function create()
    {
        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('vaccination_records.form', compact('medicalHistories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'vaccine_name' => 'required|string|max:36',
            'application_date' => 'required|date',
            'dose' => 'required|string|max:50',
        ]);

        // Verificar que la historia médica pertenece a un paciente del médico actual
        $medicalHistory = MedicalHistory::findOrFail($request->medical_history_id);
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.vaccination_records.index')
                ->with('error', 'No tiene permiso para acceder a este historial médico.');
        }

        VaccinationRecord::create([
            'medical_history_id' => $request->medical_history_id,
            'vaccine_name' => $request->vaccine_name,
            'application_date' => $request->application_date,
            'dose' => $request->dose,
        ]);

        return redirect()->route('medico.vaccination_records.index')
            ->with('success', 'Registro de vacunación creado exitosamente.');
    }

    public function show(VaccinationRecord $vaccinationRecord)
    {
        if ($vaccinationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.vaccination_records.index')
                ->with('error', 'No tiene permiso para ver este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('vaccination_records.form', compact('vaccinationRecord', 'medicalHistories'));
    }

    public function edit(VaccinationRecord $vaccinationRecord)
    {
        if ($vaccinationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.vaccination_records.index')
                ->with('error', 'No tiene permiso para editar este registro.');
        }

        $medicalHistories = MedicalHistory::whereHas('patient', function($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();
        return view('vaccination_records.form', compact('vaccinationRecord', 'medicalHistories'));
    }

    public function update(Request $request, VaccinationRecord $vaccinationRecord)
    {
        if ($vaccinationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.vaccination_records.index')
                ->with('error', 'No tiene permiso para actualizar este registro.');
        }

        $request->validate([
            'medical_history_id' => 'required|integer|exists:medical_histories,id',
            'vaccine_name' => 'required|string|max:36',
            'application_date' => 'required|date',
            'dose' => 'required|string|max:50',
        ]);

        $vaccinationRecord->update([
            'medical_history_id' => $request->medical_history_id,
            'vaccine_name' => $request->vaccine_name,
            'application_date' => $request->application_date,
            'dose' => $request->dose,
        ]);

        return redirect()->route('medico.vaccination_records.index')
            ->with('success', 'Registro de vacunación actualizado exitosamente.');
    }

    public function destroy(VaccinationRecord $vaccinationRecord)
    {
        if ($vaccinationRecord->medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.vaccination_records.index')
                ->with('error', 'No tiene permiso para eliminar este registro.');
        }

        $vaccinationRecord->delete();
        return redirect()->route('medico.vaccination_records.index')
            ->with('success', 'Registro de vacunación eliminado exitosamente.');
    }
}
