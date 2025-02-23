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
            $user = auth()->user();
            
            if ($user->type === 'medico') {
                $totalPatients = Patient::where('doctor_id', $user->medico->id)->count();
                $recentConsultations = MedicalConsultationRecord::where('doctor_id', $user->medico->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                return view('medico.dashboard', compact('totalPatients', 'recentConsultations'));
            }

            return redirect()->route('admin.medicos.index');
        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el dashboard');
        }
    }
} 