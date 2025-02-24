<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Vista de prueba simple
            return view('medico.dashboard', [
                'test_message' => 'Esta es una prueba del dashboard médico',
                'currentDate' => now()->format('Y-m-d'),
                'currentTime' => now()->format('H:i:s'),
                'medico' => [
                    'specialty' => 'Test Specialty',
                    'phone' => 'Test Phone',
                    'cedula' => 'Test Cedula'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
} 