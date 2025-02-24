<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Medico;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            Log::info('Dashboard access attempt', [
                'user_id' => $user->id,
                'user_type' => $user->type
            ]);

            $medico = Medico::where('user_id', $user->id)->first();
            
            if (!$medico) {
                Log::error('Médico no encontrado', ['user_id' => $user->id]);
                return redirect()->route('home')->with('error', 'Información de médico no encontrada');
            }

            return view('medico.dashboard', [
                'medico' => $medico,
                'totalPatients' => 0,
                'totalConsultations' => 0,
                'currentDate' => now()->format('l, d \d\e F \d\e Y'),
                'currentTime' => now()->format('h:i A')
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            if (app()->environment('local')) {
                throw $e;
            }
            
            return redirect()->route('home')
                ->with('error', 'Error al cargar el dashboard');
        }
    }
} 