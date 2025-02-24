<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'name',
        'email',
        'cedula',
        'phone',
        'address',
        'birth_date',
        'gender',
        'blood_type',
        'allergies'
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
    public function doctor()
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

    public function therapyRecords(): HasMany
    {
        return $this->hasMany(TherapyRecord::class);
    }

    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }
}
