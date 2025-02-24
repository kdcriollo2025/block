<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            return view('medico.dashboard', [
                'nombre' => Auth::user()->name
            ]);
        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Error al cargar el dashboard');
        }
    }
} 