<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Verificar que estamos llegando aquí
            Log::info('Intentando acceder al dashboard');

            // 2. Verificar la autenticación
            if (!Auth::check()) {
                Log::error('Usuario no autenticado');
                throw new \Exception('Usuario no autenticado');
            }

            $user = Auth::user();
            Log::info('Usuario autenticado', [
                'id' => $user->id,
                'email' => $user->email,
                'type' => $user->type
            ]);

            // 3. Retornar una vista muy simple
            return view('medico.dashboard', [
                'test_message' => 'Prueba de Dashboard - ' . date('Y-m-d H:i:s'),
                'currentDate' => date('Y-m-d'),
                'currentTime' => date('H:i:s'),
                'medico' => [
                    'specialty' => 'Prueba',
                    'phone' => '123456789',
                    'cedula' => '123456789'
                ]
            ]);

        } catch (\Exception $e) {
            // 4. Log detallado del error
            Log::error('Error en DashboardController', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // 5. Mostrar error detallado en desarrollo
            if (config('app.debug')) {
                throw $e;
            }

            // 6. Retornar respuesta de error
            return response()->view('errors.500', ['exception' => $e], 500);
        }
    }
} 