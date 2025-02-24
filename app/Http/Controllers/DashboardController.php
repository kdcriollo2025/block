<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            // Verificar si la vista existe
            if (!View::exists('medico.dashboard')) {
                throw new \Exception('Vista dashboard no encontrada');
            }

            // Verificar autenticación
            if (!Auth::check()) {
                throw new \Exception('Usuario no autenticado');
            }

            $user = Auth::user();
            
            // Log para debugging
            Log::info('Acceso al dashboard', [
                'user_id' => $user->id,
                'user_type' => $user->type,
                'view_exists' => View::exists('medico.dashboard'),
                'auth_check' => Auth::check()
            ]);

            return view('medico.dashboard', [
                'nombre' => $user->name
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // En modo debug, mostrar el error completo
            if (config('app.debug')) {
                dd([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            abort(500, $e->getMessage());
        }
    }
} 