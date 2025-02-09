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
        'name',
        'email',
        'cedula',
        'specialty',
        'phone_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
    public function consultations(): HasMany
    {
        return $this->hasMany(MedicalConsultationRecord::class, 'doctor_id');
    }

    /**
     * Get the user associated with the doctor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
} 