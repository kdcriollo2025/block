<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'birth_date',
        'gender',
        'blood_type',
        'address',
        'doctor_id'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    /**
     * Get the medical history associated with the patient.
     */
    public function medicalHistory(): HasOne
    {
        return $this->hasOne(MedicalHistory::class);
    }

    /**
     * Get the doctor that owns the patient.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'doctor_id');
    }

    /**
     * Get all medical consultations for the patient through medical history.
     */
    public function medicalConsultations()
    {
        return $this->hasManyThrough(
            MedicalConsultationRecord::class,
            MedicalHistory::class,
            'patient_id', // Foreign key on medical_histories table...
            'medical_history_id', // Foreign key on medical_consultation_records table...
            'id', // Local key on patients table...
            'id' // Local key on medical_histories table...
        );
    }
}
