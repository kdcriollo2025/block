<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;
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
            // 1. Verificar autenticación
            if (!Auth::check()) {
                throw new \Exception('Usuario no autenticado');
            }

            // 2. Obtener usuario
            $user = Auth::user();
            Log::info('Usuario intentando acceder al dashboard', [
                'id' => $user->id,
                'email' => $user->email,
                'type' => $user->type
            ]);

            // 3. Verificar que la vista existe
            if (!View::exists('medico.dashboard')) {
                throw new \Exception('Vista no encontrada: medico.dashboard');
            }

            // 4. Verificar que es médico
            if ($user->type !== 'medico') {
                throw new \Exception('Usuario no es médico');
            }

            // 5. Obtener datos del médico
            $medico = Medico::where('user_id', $user->id)->first();
            if (!$medico) {
                throw new \Exception('Información de médico no encontrada');
            }

            // 6. Retornar vista con datos básicos
            return view('medico.dashboard', [
                'nombre' => $user->name,
                'email' => $user->email,
                'tipo' => $user->type,
                'medico' => $medico
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'no-auth'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }

            // En desarrollo, mostrar error detallado
            if (config('app.debug')) {
                throw $e;
            }

            // En producción, redirigir con mensaje
            return redirect()
                ->route('home')
                ->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }
} 