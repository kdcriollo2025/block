<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('medico');
    }
    
    public function index()
    {
        try {
            // Obtener el médico autenticado
            $user = Auth::user();
            if (!$user || !$user->medico) {
                \Log::error('Usuario sin registro de médico: ' . ($user ? $user->id : 'no auth'));
                return redirect()->route('login')->with('error', 'Acceso no autorizado');
            }

            // Obtener los pacientes del médico
            $patients = Patient::where('medico_id', $user->medico->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('medico.patients.index', compact('patients'));
        } catch (\Exception $e) {
            \Log::error('Error en PatientController@index: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Hubo un error al cargar los pacientes.');
        }
    }

    public function create()
    {
        return view('patients.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|size:10|unique:patients',
            'birth_date' => 'required|date',
            'gender' => 'required|string|in:Masculino,Femenino,Otro',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        $validated['medico_id'] = Auth::user()->medico->id;
        
        Patient::create($validated);

        return redirect()->route('medicos.patients.index')
            ->with('success', 'Paciente creado exitosamente.');
    }

    public function show(Patient $patient)
    {
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medicos.patients.index');
        }

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.patients.index');
        }

        return view('patients.form', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.patients.index');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|size:10|unique:patients,cedula,' . $patient->id,
            'birth_date' => 'required|date',
            'gender' => 'required|string|in:Masculino,Femenino,Otro',
            'address' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        $patient->update($validated);

        return redirect()->route('medico.patients.index')
            ->with('success', 'Paciente actualizado exitosamente.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.patients.index');
        }

        $patient->delete();

        return redirect()->route('medico.patients.index')
            ->with('success', 'Paciente eliminado exitosamente.');
    }
}
