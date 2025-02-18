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
use App\Http\Controllers\MedicoController;
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

// Rutas públicas
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->type === 'admin') {
            return redirect('/admin/medicos');
        } else {
            return redirect('/medico/dashboard');
        }
    }
    return redirect('/login');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rutas para restablecimiento de contraseña
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rutas para administradores
Route::middleware(['auth', \App\Http\Middleware\CheckUserType::class.':admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::resource('medicos', MedicoController::class);
    Route::patch('medicos/{medico}/toggle-status', [MedicoController::class, 'toggleStatus'])->name('medicos.toggle-status');

    // Rutas de reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/patients-per-doctor', [ReportController::class, 'patientsPerDoctor'])->name('reports.patients-per-doctor');
    Route::get('/reports/common-diagnoses', [ReportController::class, 'commonDiagnoses'])->name('reports.common-diagnoses');
    Route::get('/reports/consultations-over-time', [ReportController::class, 'consultationsOverTime'])->name('reports.consultations-over-time');
    Route::get('/reports/patient-demographics', [ReportController::class, 'patientDemographics'])->name('reports.patient-demographics');
});

// Rutas para médicos
Route::middleware(['auth', \App\Http\Middleware\CheckUserType::class.':medico'])->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para pacientes
    Route::resource('patients', PatientController::class);
    
    // Rutas para historias médicas
    Route::get('medical_histories/{medicalHistory}/download-pdf', [MedicalHistoryController::class, 'downloadPdf'])
        ->name('medical_histories.download-pdf');
    Route::resource('medical_histories', MedicalHistoryController::class);
    
    // Rutas para alergias
    Route::resource('allergy_records', AllergyRecordController::class);
    
    // Rutas para cirugías
    Route::resource('surgery_records', SurgeryRecordController::class);
    
    // Rutas para consultas médicas
    Route::get('medical-consultation-records', [MedicalConsultationRecordController::class, 'index'])
        ->name('medical_consultation_records.index');
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

Route::post('/medico/registrar', [MedicoController::class, 'store'])->name('medico.store');
