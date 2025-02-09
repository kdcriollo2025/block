<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        return view('admin.medicos.index', compact('medicos'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|size:10|unique:medicos|unique:users',
            'email' => 'required|string|email|max:255|unique:medicos|unique:users',
            'specialty' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'cedula' => $request->cedula,
                'password' => Hash::make($request->password),
                'type' => 'medico',
                'first_login' => true,
            ]);

            Medico::create([
                'name' => $request->name,
                'email' => $request->email,
                'cedula' => $request->cedula,
                'specialty' => $request->specialty,
                'phone_number' => $request->phone_number,
                'is_active' => true,
            ]);

            DB::commit();
            return redirect()->route('admin.medicos.index')
                ->with('success', 'Médico creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error al crear el médico.']);
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
            'cedula' => 'required|string|size:10|unique:users,cedula,' . $medico->user->id . '|unique:medicos,cedula,' . $medico->id,
            'specialty' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request, $medico) {
            $medico->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'cedula' => $request->cedula,
            ]);

            $medico->update([
                'name' => $request->name,
                'email' => $request->email,
                'cedula' => $request->cedula,
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
} 