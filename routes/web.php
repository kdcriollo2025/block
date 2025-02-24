<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AllergyRecordController;
use App\Http\Controllers\SurgeryRecordController;
use App\Http\Controllers\MedicalConsultationRecordController;
use App\Http\Controllers\TherapyRecordController;
use App\Http\Controllers\VaccinationRecordController;
use App\Http\Controllers\Admin\MedicoController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\BlockchainNFT;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will 
| be assigned to the "web" middleware group. Make something great!
*/

// Rutas de autenticación
Auth::routes();

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas para administrador
Route::middleware(['auth', 'type:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('medicos', MedicoController::class);
    Route::patch('medicos/{medico}/toggle-estado', [MedicoController::class, 'toggleEstado'])
        ->name('medicos.toggle-estado');

    // Rutas de reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/patients-per-doctor', [ReportController::class, 'patientsPerDoctor'])
        ->name('reports.patients-per-doctor');
    Route::get('/reports/common-diagnoses', [ReportController::class, 'commonDiagnoses'])->name('reports.common-diagnoses');
    Route::get('/reports/consultations-over-time', [ReportController::class, 'consultationsOverTime'])
        ->name('reports.consultations-over-time');
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/patient-demographics', [ReportController::class, 'patientDemographics'])
            ->name('patient-demographics');
    });
});

// Rutas para médicos
Route::middleware(['auth', 'type:medico'])->prefix('medico')->name('medico.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pacientes
    Route::resource('patients', PatientController::class);
    
    // Consultas médicas
    Route::get('/medical-consultation-records', [MedicalConsultationRecordController::class, 'index'])
        ->name('medical_consultation_records.index');
    
    // Rutas para historias médicas
    Route::get('medical_histories/{medicalHistory}/download-pdf', [MedicalHistoryController::class, 'downloadPdf'])
        ->name('medical_histories.download-pdf');
    Route::resource('medical_histories', MedicalHistoryController::class);
    
    // Rutas para alergias
    Route::resource('allergy_records', AllergyRecordController::class);
    
    // Rutas para cirugías
    Route::resource('surgery_records', SurgeryRecordController::class);
    
    // Rutas para consultas médicas
    Route::get('medical-histories/{medicalHistory}/consultations/create', [MedicalConsultationRecordController::class, 'create'])
        ->name('medical_consultation_records.create');
    Route::post('medical-consultation-records', [MedicalConsultationRecordController::class, 'store'])
        ->name('medical_consultation_records.store');
    
    // Rutas para terapias
    Route::resource('therapy_records', TherapyRecordController::class);
    
    // Rutas para vacunas
    Route::resource('vaccination_records', VaccinationRecordController::class);
});

// Rutas para cambio de contraseña
Route::middleware(['auth'])->group(function () {
    Route::get('change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change');
});

// Rutas para NFTs
Route::prefix('blockchain')->group(function () {
    Route::post('/nft', [BlockchainNFT::class, 'createNFT'])->name('nft.create');
    Route::get('/nfts', [BlockchainNFT::class, 'getNFTs'])->name('nft.all');
    Route::get('/nft/{assetId}', [BlockchainNFT::class, 'getNFTByAssetId'])->name('nft.get');
    Route::post('/nft/transfer', [BlockchainNFT::class, 'transferNFT'])->name('nft.transfer');
});

// Ruta de debug (temporal)
Route::get('/debug-user', function() {
    if (Auth::check()) {
        $user = Auth::user();
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type
            ],
            'medico' => $user->medico,
            'session' => [
                'id' => session()->getId(),
                'token' => csrf_token()
            ]
        ]);
    }
    return response()->json(['authenticated' => false]);
});
