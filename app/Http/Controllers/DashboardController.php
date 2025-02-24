<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            Log::info('Usuario accediendo al dashboard', [
                'user_id' => $user->id,
                'name' => $user->name,
                'type' => $user->type
            ]);

            return view('medico.dashboard', ['nombre' => $user->name]);
            
        } catch (\Exception $e) {
            Log::error('Error en dashboard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
} 