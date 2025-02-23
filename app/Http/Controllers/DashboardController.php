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
        try {
            // 1. Verificar el usuario autenticado
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }

            // 2. Verificar el tipo de usuario
            if ($user->type !== 'medico') {
                return redirect()->route('home');
            }

            // 3. Buscar el médico de diferentes formas
            $medicoFromDB = DB::table('medicos')->where('user_id', $user->id)->first();
            $medicoFromModel = Medico::where('user_id', $user->id)->first();
            $medicoFromRelation = $user->medico;

            // 4. Mostrar todos los datos para depuración
            dd([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => $user->type,
                    'cedula' => $user->cedula
                ],
                'medico_from_db' => $medicoFromDB,
                'medico_from_model' => $medicoFromModel,
                'medico_from_relation' => $medicoFromRelation,
                'all_medicos' => Medico::all(['id', 'user_id', 'cedula'])->toArray(),
                'all_users' => User::where('type', 'medico')->get(['id', 'name', 'cedula'])->toArray()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e;
        }
    }
} 