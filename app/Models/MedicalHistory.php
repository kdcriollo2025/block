<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'hash'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function allergyRecords()
    {
        return $this->hasMany(AllergyRecord::class, 'medical_history_id');
    }

    public function surgeryRecords()
    {
        return $this->hasMany(SurgeryRecord::class, 'medical_history_id');
    }

    public function consultationRecords(): HasMany
    {
        return $this->hasMany(MedicalConsultationRecord::class);
    }

    public function therapyRecords()
    {
        return $this->hasMany(TherapyRecord::class, 'medical_history_id');
    }

    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class, 'medical_history_id');
    }
}
