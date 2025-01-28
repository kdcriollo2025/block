<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalConsultationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_history_id',
        'doctor_id',
        'consultation_date',
        'reported_symptoms',
        'diagnosis',
        'treatment'
    ];

    protected $casts = [
        'consultation_date' => 'date'
    ];

    /**
     * Get the medical history that owns the consultation record.
     */
    public function medicalHistory(): BelongsTo
    {
        return $this->belongsTo(MedicalHistory::class);
    }

    /**
     * Get the doctor that performed the consultation.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'doctor_id');
    }
}
