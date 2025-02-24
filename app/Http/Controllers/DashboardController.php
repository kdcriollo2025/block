<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medico;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('medico');
    }

    public function index()
    {
        try {
            // Obtener todos los médicos con sus relaciones
            $medicos = Medico::with(['user', 'pacientes'])
                ->select('medicos.*')
                ->get();

            // Retornar la vista correcta que está en la carpeta medicos
            return view('medicos.index', compact('medicos'));

        } catch (\Exception $e) {
            Log::error('Error en dashboard médico: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al cargar la lista de médicos');
        }
    }
}