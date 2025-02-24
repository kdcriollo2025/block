<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log para debugging
            Log::info('Información del usuario:', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type
            ]);

            // Verificar tipo de usuario
            if ($user->type !== 'medico') {
                Log::warning('Usuario no autorizado intentando acceder', [
                    'user_id' => $user->id,
                    'type' => $user->type
                ]);
                abort(403, 'No autorizado');
            }

            // Buscar información del médico con consultas
            $medico = Medico::with(['consultations' => function($query) {
                $query->latest()->take(5);
            }])->where('user_id', $user->id)->first();
            
            // Log para debugging
            Log::info('Búsqueda de médico:', [
                'user_id' => $user->id,
                'medico_encontrado' => $medico ? 'sí' : 'no',
                'consultas_count' => $medico ? $medico->consultations()->count() : 0
            ]);

            if (!$medico) {
                Log::error('Médico no encontrado', [
                    'user_id' => $user->id
                ]);
                throw new \Exception('No se encontró la información del médico');
            }

            return view('medico.dashboard', [
                'nombre' => $user->name,
                'email' => $user->email,
                'tipo' => $user->type,
                'medico' => $medico
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard médico', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            throw $e;
        }
    }
} 