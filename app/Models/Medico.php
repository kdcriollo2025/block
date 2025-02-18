<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medico extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'medicos';

    protected $fillable = [
        'nombre_completo',
        'correo_electronico',
        'especialidad',
        'telefono',
        'contrasena',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /**
     * Get the patients for the doctor.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'doctor_id');
    }

    /**
     * Get the medical consultations for the doctor.
     */
    public function medicalConsultations(): HasMany
    {
        return $this->hasMany(MedicalConsultationRecord::class, 'doctor_id');
    }

    /**
     * Get the user associated with the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
} 