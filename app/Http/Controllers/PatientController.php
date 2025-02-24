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
            Log::info('Iniciando PatientController@index');
            
            // Verificar usuario autenticado
            if (!Auth::check()) {
                Log::warning('Usuario no autenticado intentando acceder a patients.index');
                return redirect()->route('login');
            }

            // Verificar que el usuario es médico
            $user = Auth::user();
            if (!$user->medico) {
                Log::error('Usuario sin registro de médico intentando acceder: ' . $user->id);
                return redirect()->route('login')
                    ->with('error', 'No tienes acceso a esta sección');
            }

            // Obtener pacientes con eager loading
            $patients = Patient::where('doctor_id', $user->medico->id)
                ->with(['medicalHistory'])
                ->get();

            Log::info('Pacientes obtenidos exitosamente', ['count' => $patients->count()]);

            return view('medico.patients.index', compact('patients'));

        } catch (\Exception $e) {
            Log::error('Error en PatientController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // En producción, mostrar un mensaje genérico
            if (app()->environment('production')) {
                return back()->with('error', 'Ocurrió un error al cargar los pacientes');
            }
            
            // En desarrollo, mostrar el error específico
            throw $e;
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

        $validated['doctor_id'] = Auth::user()->medico->id;
        
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
