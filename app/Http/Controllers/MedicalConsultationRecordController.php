<?php

namespace App\Http\Controllers;

use App\Models\MedicalConsultationRecord;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        try {
            $medico = Auth::user()->medico;
            
            $consultations = MedicalConsultationRecord::with(['medicalHistory.patient'])
                ->whereHas('medicalHistory.patient', function($query) use ($medico) {
                    $query->where('doctor_id', $medico->id);
                })
                ->orderBy('consultation_date', 'desc')
                ->paginate(10);

            return view('medico.medical_consultation_records.index', compact('consultations'));

        } catch (\Exception $e) {
            \Log::error('Error en index: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Error al cargar las consultas médicas.');
        }
    }

    public function create()
    {
        try {
            $medico = Auth::user()->medico;
            $patients = $medico->patients()
                ->select('patients.id', 'patients.name', 'medical_histories.id as history_id')
                ->join('medical_histories', 'patients.id', '=', 'medical_histories.patient_id')
                ->get();

            return view('medico.medical_consultation_records.create', compact('patients'));

        } catch (\Exception $e) {
            \Log::error('Error en create: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Error al cargar el formulario de consulta.');
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'medical_history_id' => 'required|exists:medical_histories,id',
                'reason' => 'required|string|max:255',
                'symptoms' => 'required|string',
                'diagnosis' => 'required|string',
                'treatment' => 'required|string',
                'consultation_date' => 'required|date',
                'next_appointment' => 'nullable|date|after:consultation_date'
            ]);

            $medico = Auth::user()->medico;
            
            $consultation = new MedicalConsultationRecord();
            $consultation->medical_history_id = $validated['medical_history_id'];
            $consultation->doctor_id = $medico->id;
            $consultation->reason = $validated['reason'];
            $consultation->symptoms = $validated['symptoms'];
            $consultation->diagnosis = $validated['diagnosis'];
            $consultation->treatment = $validated['treatment'];
            $consultation->consultation_date = $validated['consultation_date'];
            $consultation->next_appointment = $validated['next_appointment'];
            
            $consultation->save();

            DB::commit();

            return redirect()
                ->route('medico.medical_consultation_records.index')
                ->with('success', 'Consulta médica registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear consulta médica: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Hubo un error al registrar la consulta médica. Por favor, intente nuevamente.');
        }
    }

    public function show(MedicalConsultationRecord $medical_consultation_record)
    {
        try {
            $consultation = $medical_consultation_record;
            return view('medico.medical_consultation_records.show', compact('consultation'));

        } catch (\Exception $e) {
            \Log::error('Error en show: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Error al mostrar la consulta médica.');
        }
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
