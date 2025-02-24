<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Verificamos que el usuario esté autenticado y sea médico
        $this->middleware(['auth', 'type:medico']);
    }

    public function index()
    {
        try {
            $user = Auth::user();

            // Verificar tipo de usuario
            if ($user->type !== 'medico') {
                Log::warning('Intento de acceso no autorizado al dashboard médico', [
                    'user_id' => $user->id,
                    'user_type' => $user->type
                ]);
                throw new \Exception("Acceso no autorizado. Tipo de usuario: {$user->type}");
            }

            // Obtener información del médico
            $medico = Medico::where('user_id', $user->id)->first();
            if (!$medico) {
                Log::error('Médico no encontrado para usuario', [
                    'user_id' => $user->id
                ]);
                throw new \Exception('No se encontró la información del médico');
            }

            Log::info('Acceso exitoso al dashboard', [
                'user_id' => $user->id,
                'medico_id' => $medico->id
            ]);

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
                'user_id' => Auth::id() ?? 'no-auth',
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }
} 