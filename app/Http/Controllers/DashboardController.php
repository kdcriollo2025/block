<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medico;
use Illuminate\Support\Facades\Log;

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
            return view('medico.dashboard');
        } catch (\Exception $e) {
            \Log::error('Error en DashboardController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el dashboard');
        }
    }
}