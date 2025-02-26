<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use TCPDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

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

    public function create()
    {
        $patients = Patient::where('doctor_id', Auth::user()->medico->id)->get();
        return view('medical_histories.form', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        // Verificar que el paciente pertenece al médico
        $patient = Patient::findOrFail($validated['patient_id']);
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        // Generar hash único basado en información relevante
        $hashData = [
            'patient_id' => $validated['patient_id'],
            'doctor_id' => Auth::user()->medico->id,
            'timestamp' => now()->timestamp,
            'random' => Str::random(16)
        ];
        
        $hash = hash('sha256', json_encode($hashData));

        MedicalHistory::create([
            'patient_id' => $validated['patient_id'],
            'hash' => $hash
        ]);

        return redirect()->route('medico.medical_histories.index')
            ->with('success', 'Historial médico creado exitosamente.');
    }

    public function show(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        // Generar un identificador único para el "NFT"
        $nftId = "NFT-" . $medicalHistory->hash;
        
        // Crear datos para el QR
        $qrData = [
            'type' => 'Medical History NFT',
            'id' => $nftId,
            'patient' => $medicalHistory->patient->name,
            'created' => $medicalHistory->created_at->format('Y-m-d'),
            'hash' => $medicalHistory->hash
        ];

        // Generar el código QR
        $qrCode = QrCode::size(200)
                       ->backgroundColor(255, 255, 255)
                       ->color(0, 0, 0)
                       ->generate(json_encode($qrData));

        return view('medical-histories.show', [
            'medicalHistory' => $medicalHistory,
            'qrCode' => $qrCode,
            'nftId' => $nftId
        ]);
    }

    public function edit(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        $patients = Patient::where('doctor_id', Auth::user()->medico->id)->get();
        return view('medical_histories.form', compact('medicalHistory', 'patients'));
    }

    public function update(Request $request, MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        // Verificar que el paciente pertenece al médico
        $patient = Patient::findOrFail($validated['patient_id']);
        if ($patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        // Generar nuevo hash si el paciente cambió
        if ($medicalHistory->patient_id != $validated['patient_id']) {
            $hashData = [
                'patient_id' => $validated['patient_id'],
                'doctor_id' => Auth::user()->medico->id,
                'timestamp' => now()->timestamp,
                'random' => Str::random(16)
            ];
            
            $validated['hash'] = hash('sha256', json_encode($hashData));
        }

        $medicalHistory->update($validated);

        return redirect()->route('medico.medical_histories.index')
            ->with('success', 'Historial médico actualizado exitosamente.');
    }

    public function destroy(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->patient->doctor_id !== Auth::user()->medico->id) {
            return redirect()->route('medico.medical_histories.index');
        }

        $medicalHistory->delete();

        return redirect()->route('medico.medical_histories.index')
            ->with('success', 'Historial médico eliminado exitosamente.');
    }

    public function downloadPdf(MedicalHistory $medicalHistory)
    {
        try {
            // Verificar permisos
            if ($medicalHistory->patient->doctor_id !== auth()->user()->medico->id) {
                return redirect()->back()->with('error', 'No tiene permiso para acceder a esta historia médica');
            }

            // Crear nueva instancia de TCPDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Configurar el documento
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sistema Médico');
            $pdf->SetTitle('Historia Médica - ' . $medicalHistory->patient->name);

            // Eliminar cabeceras y pies de página predeterminados
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Establecer márgenes
            $pdf->SetMargins(15, 15, 15);

            // Establecer saltos de página automáticos
            $pdf->SetAutoPageBreak(TRUE, 15);

            // Agregar una página
            $pdf->AddPage();

            // Establecer fuente
            $pdf->SetFont('helvetica', '', 10);

            // Preparar datos para la vista
            $data = [
                'medicalHistory' => $medicalHistory,
                'patient' => $medicalHistory->patient,
                'consultations' => $medicalHistory->consultationRecords()->orderBy('consultation_date', 'desc')->get(),
                'allergies' => $medicalHistory->allergyRecords,
                'surgeries' => $medicalHistory->surgeryRecords,
                'vaccinations' => $medicalHistory->vaccinationRecords,
                'therapies' => $medicalHistory->therapyRecords,
            ];

            // Generar el HTML
            $html = View::make('medical_histories.pdf', $data)->render();

            // Escribir el HTML en el PDF
            $pdf->writeHTML($html, true, false, true, false, '');

            // Generar nombre del archivo
            $fileName = 'historia_medica_' . str_replace(' ', '_', $medicalHistory->patient->name) . '_' . date('Y-m-d') . '.pdf';

            // Descargar el PDF
            return $pdf->Output($fileName, 'D');

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF. Por favor, intente nuevamente.');
        }
    }

    public function downloadCertificate(MedicalHistory $medicalHistory)
    {
        $nftId = "NFT-CERT-" . $medicalHistory->hash;
        
        $qrData = [
            'type' => 'Medical Certificate NFT',
            'id' => $nftId,
            'patient' => $medicalHistory->patient->name,
            'doctor' => $medicalHistory->patient->doctor->name,
            'date' => now()->format('Y-m-d'),
            'hash' => $medicalHistory->hash
        ];

        $qrCode = QrCode::size(200)
                       ->backgroundColor(255, 255, 255)
                       ->color(0, 0, 0)
                       ->generate(json_encode($qrData));

        $pdf = PDF::loadView('medical-histories.certificate', [
            'medicalHistory' => $medicalHistory,
            'qrCode' => $qrCode,
            'nftId' => $nftId
        ]);

        return $pdf->download('medical-certificate.pdf');
    }
}
