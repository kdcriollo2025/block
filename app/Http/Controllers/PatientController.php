<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
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
        $patients = Patient::where('doctor_id', Auth::user()->medico->id)->get();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|size:10|unique:patients',
            'email' => 'required|string|email|max:255|unique:patients',
            'birth_date' => 'required|date',
            'gender' => 'required|string|in:Masculino,Femenino,Otro',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        $validated = $request->all();
        $validated['doctor_id'] = Auth::user()->medico->id;
        
        Patient::create($validated);

        return redirect()->route('medico.patients.index')
            ->with('success', 'Paciente creado exitosamente.');
    }

    public function show(Patient $patient)
    {
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.patients.index');
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
            'email' => 'required|string|email|max:255|unique:patients,email,' . $patient->id,
            'birth_date' => 'required|date',
            'gender' => 'required|string|in:Masculino,Femenino,Otro',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
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
