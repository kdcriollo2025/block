<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Obtener usuario y verificar tipo
            $user = Auth::user();
            
            // 2. Consulta directa a la base de datos
            $query = "
                SELECT u.id as user_id, u.name, u.email, u.type, 
                       m.id as medico_id, m.specialty, m.cedula
                FROM users u
                LEFT JOIN medicos m ON u.id = m.user_id
                WHERE u.id = ?
            ";
            
            $result = DB::select($query, [$user->id]);
            
            // 3. Mostrar resultados
            dd([
                'user_auth' => $user->only(['id', 'name', 'email', 'type']),
                'query_result' => $result,
                'raw_sql' => $query
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
} 