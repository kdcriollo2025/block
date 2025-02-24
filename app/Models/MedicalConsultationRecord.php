<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalConsultationRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_consultation_records';

    protected $fillable = [
        'medical_history_id',
        'doctor_id',
        'consultation_date',
        'reason',
        'symptoms',
        'diagnosis',
        'treatment',
        'observations'
    ];

    protected $casts = [
        'consultation_date' => 'datetime'
    ];

    /**
     * Get the medical history that owns the consultation.
     */
    public function medicalHistory(): BelongsTo
    {
        return $this->belongsTo(MedicalHistory::class, 'medical_history_id');
    }

    /**
     * Get the doctor that performed the consultation.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'doctor_id');
    }

    /**
     * Get the patient through medical history.
     */
    public function patient()
    {
        return $this->medicalHistory->patient;
    }
}
