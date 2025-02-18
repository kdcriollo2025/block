<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use TCPDF;
use PDF;

class MedicalHistoryController extends Controller
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
        $medicalHistories = MedicalHistory::whereHas('patient', function ($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();

        return view('medical_histories.index', compact('medicalHistories'));
    }

    public function create()
    {
        $patients = Patient::where('doctor_id', Auth::user()->medico->id)->get();
        return view('medical_histories.form', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        // Verificar que el paciente pertenece al médico
        $patient = Patient::findOrFail($validated['patient_id']);
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        // Generar hash único basado en información relevante
        $hashData = [
            'patient_id' => $validated['patient_id'],
            'doctor_id' => Auth::user()->medico->id,
            'timestamp' => now()->timestamp,
            'random' => Str::random(16)
        ];
        
        $hash = hash('sha256', json_encode($hashData));

        MedicalHistory::create([
            'patient_id' => $validated['patient_id'],
            'hash' => $hash
        ]);

        return redirect()->route('medico.medical_histories.index')
            ->with('success', 'Historial médico creado exitosamente.');
    }

    public function show(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        return view('medical_histories.show', compact('medicalHistory'));
    }

    public function edit(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        $patients = Patient::where('doctor_id', Auth::user()->medico->id)->get();
        return view('medical_histories.form', compact('medicalHistory', 'patients'));
    }

    public function update(Request $request, MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        // Verificar que el paciente pertenece al médico
        $patient = Patient::findOrFail($validated['patient_id']);
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        // Generar nuevo hash si el paciente cambió
        if ($medicalHistory->patient_id != $validated['patient_id']) {
            $hashData = [
                'patient_id' => $validated['patient_id'],
                'doctor_id' => Auth::user()->medico->id,
                'timestamp' => now()->timestamp,
                'random' => Str::random(16)
            ];
            
            $validated['hash'] = hash('sha256', json_encode($hashData));
        }

        $medicalHistory->update($validated);

        return redirect()->route('medico.medical_histories.index')
            ->with('success', 'Historial médico actualizado exitosamente.');
    }

    public function destroy(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        $medicalHistory->delete();

        return redirect()->route('medico.medical_histories.index')
            ->with('success', 'Historial médico eliminado exitosamente.');
    }

    public function downloadPdf(MedicalHistory $medicalHistory)
    {
        // Verifica que el médico actual tenga acceso a este historial
        $this->authorize('view', $medicalHistory);

        $pdf = PDF::loadView('medical_histories.pdf', [
            'medicalHistory' => $medicalHistory
        ]);

        $fileName = 'historial_medico_' . $medicalHistory->patient->full_name . '.pdf';
        
        return $pdf->download($fileName);
    }
}
