<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'especialidad',
        'estado',
        'phone',
        'cedula'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    /**
     * Get the patients for the doctor.
     */
    public function pacientes()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the medical consultations for the doctor.
     */
    public function consultas(): HasMany
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