<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalConsultationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Medico;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Establecer la zona horaria para Quito
        date_default_timezone_set('America/Guayaquil');
        Carbon::setLocale('es');
    }

    public function index()
    {
        // 1. Verificar el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            dd('Usuario no autenticado');
        }
        
        // 2. Verificar los datos del usuario
        dd([
            'user_id' => $user->id,
            'user_type' => $user->type,
            'user_name' => $user->name,
            // 3. Buscar directamente en la tabla medicos
            'medico' => Medico::where('user_id', $user->id)->first(),
            // 4. Verificar la relación desde User
            'medico_relation' => $user->medico,
            // 5. Verificar todos los médicos
            'all_medicos' => Medico::all()->toArray()
        ]);
    }
} 