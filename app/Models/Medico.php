<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'especialidad',
        'telefono',
        'cedula'
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
        return $this->belongsTo(User::class);
    }
} 