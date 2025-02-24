<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medico;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.role:medico');
    }

    public function index()
    {
        try {
            \Log::info('Iniciando DashboardController@index');
            
            // Obtener el usuario autenticado y su información
            $user = Auth::user();
            \Log::info('Usuario autenticado:', ['id' => $user->id, 'type' => $user->type]);
            
            if (!$user) {
                throw new \Exception('Usuario no autenticado');
            }
            
            // Verificar el médico asociado
            $medico = $user->medico;
            \Log::info('Información del médico:', ['medico' => $medico ? $medico->toArray() : null]);
            
            if (!$medico) {
                throw new \Exception('Usuario no tiene registro de médico asociado');
            }

            // Obtener la fecha y hora actual
            $now = Carbon::now();
            
            $data = [
                'currentDate' => $now->format('d/m/Y'),
                'currentTime' => $now->format('H:i:s'),
            ];

            \Log::info('Renderizando vista dashboard con datos:', $data);
            return view('medico.dashboard', $data);
        } catch (\Exception $e) {
            \Log::error('Error en DashboardController@index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}