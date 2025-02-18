<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->type !== 'admin') {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicos = Medico::all();
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicos.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Datos recibidos:', $request->all());
        
        try {
            // Validación de datos
            $request->validate([
                'nombre_completo' => 'required|string|max:255',
                'correo_electronico' => 'required|email|unique:medicos',
                'especialidad' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'contrasena' => 'required|string|min:6|confirmed',
            ]);

            // Crear nuevo médico
            $medico = Medico::create([
                'nombre_completo' => $request->nombre_completo,
                'correo_electronico' => $request->correo_electronico,
                'especialidad' => $request->especialidad,
                'telefono' => $request->telefono,
                'contrasena' => Hash::make($request->contrasena),
            ]);

            \Log::info('Médico creado:', $medico->toArray());

            // Redireccionar con mensaje de éxito
            return redirect()->route('medico.dashboard')
                ->with('success', 'Médico registrado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al crear médico:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error al registrar el médico: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        return view('medicos.form', compact('medico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $medico->user->id,
            'specialty' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request, $medico) {
            $medico->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $medico->update([
                'specialty' => $request->specialty,
                'phone_number' => $request->phone_number,
            ]);
        });

        return redirect()->route('admin.medicos.index')->with('success', 'Médico actualizado exitosamente.');
    }

    public function toggleStatus(Medico $medico)
    {
        $medico->update(['is_active' => !$medico->is_active]);
        
        $status = $medico->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.medicos.index')
            ->with('success', "El médico ha sido {$status} exitosamente.");
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:patients'],
            'cedula' => ['required', 'string', 'size:10', 'unique:patients'],
            'phone_number' => ['required', 'string', 'max:20'],
        
        ]);
    }
} 