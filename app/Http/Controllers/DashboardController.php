<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Establecer la zona horaria para Quito
        date_default_timezone_set('America/Guayaquil');
        Carbon::setLocale('es');
        
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'medico') {
                return redirect()->route('home');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            $user = Auth::user();
            \Log::info('Usuario autenticado:', ['id' => $user->id, 'type' => $user->type]);

            // Cargar la relación médico con usuario
            $medico = $user->medico()->with('user')->first();
            \Log::info('Datos del médico:', ['medico' => $medico]);

            if (!$medico) {
                \Log::error('Médico no encontrado para el usuario: ' . $user->id);
                return redirect()->route('home')->with('error', 'No se encontró información del médico');
            }

            // Solo datos básicos primero
            $data = [
                'medico' => $medico,
                'totalPatients' => Patient::where('doctor_id', $medico->id)->count(),
                'currentDate' => Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'currentTime' => Carbon::now()->format('h:i A'),
            ];

            \Log::info('Datos del dashboard:', $data);
            return view('medico.dashboard', $data);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 