<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function medicos()
    {
        try {
            $medicos = Medico::with('user')->get();
            return view('admin.medicos.index', compact('medicos'));
        } catch (\Exception $e) {
            \Log::error('Error en AdminController@medicos: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al cargar la lista de médicos.');
        }
    }
} 