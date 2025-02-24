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

// Rutas públicas
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->type === 'admin') {
            return redirect()->route('admin.medicos.index');
        }
        return redirect()->route('medico.dashboard');
    }
    return redirect()->route('login');
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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
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
Route::middleware(['auth', 'role:medico'])->group(function () {
    Route::get('/medico/dashboard', function () {
        return view('medico.dashboard');
    })->name('medico.dashboard');
    
    // Otras rutas específicas para médicos
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
