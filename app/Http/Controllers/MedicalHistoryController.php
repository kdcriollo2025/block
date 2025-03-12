<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MedicalHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'medico') {
                return redirect()->route('home');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $medicalHistories = MedicalHistory::whereHas('patient', function ($query) {
            $query->where('doctor_id', Auth::user()->medico->id);
        })->get();

        return view('medical_histories.index', compact('medicalHistories'));
    }

    public function generateNewHash(Request $request, $id)
    {
        $medicalHistory = MedicalHistory::findOrFail($id);

        // Obtener los hashes actuales y añadir uno nuevo
        $currentHashes = json_decode($medicalHistory->hash_history, true) ?? [];
        
        // Generar un nuevo hash aleatorio
        $newHash = substr(hash('sha256', Str::random(16)), 0, 10);
        
        // Agregar el nuevo hash a la lista
        $currentHashes[] = $newHash;

        // Guardar la nueva lista de hashes en la base de datos
        $medicalHistory->update([
            'hash_history' => json_encode($currentHashes)
        ]);

        // Generar los datos actualizados del QR
        $qrData = [
            'patient' => $medicalHistory->patient->name,
            'doctor' => Auth::user()->name,
            'hash_history' => $currentHashes,
            'status' => "Verificado",
            'time' => now()->format('Y-m-d H:i:s')
        ];

        return response()->json([
            'qr' => QrCode::size(200)->generate(json_encode($qrData)),
            'hash_history' => $currentHashes
        ]);
    }
}
