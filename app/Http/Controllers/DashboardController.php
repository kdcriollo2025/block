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
            // Obtener la fecha y hora actual
            $now = Carbon::now();
            
            $data = [
                'currentDate' => $now->format('d/m/Y'),
                'currentTime' => $now->format('H:i:s'),
            ];

            return view('medico.dashboard', $data);
        } catch (\Exception $e) {
            \Log::error('Error en DashboardController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el dashboard');
        }
    }
}