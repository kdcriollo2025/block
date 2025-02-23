<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Medico;

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
        $user = Auth::user();
        
        // Cargar el médico con su relación user
        $medico = Medico::with('user')->where('user_id', $user->id)->first();
        
        if (!$medico) {
            dd('Médico no encontrado', $user->toArray());
        }

        // Vista simplificada
        return view('medico.dashboard', [
            'medico' => $medico,
            'name' => $medico->user->name
        ]);
    }
} 