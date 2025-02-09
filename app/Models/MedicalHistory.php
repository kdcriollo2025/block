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

    public function allergyRecords(): HasMany
    {
        return $this->hasMany(AllergyRecord::class);
    }

    public function surgeryRecords(): HasMany
    {
        return $this->hasMany(SurgeryRecord::class);
    }

    public function medicalConsultationRecords(): HasMany
    {
        return $this->hasMany(MedicalConsultationRecord::class);
    }

    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }
}
