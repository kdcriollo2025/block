<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'hash'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function allergyRecords()
    {
        return $this->hasMany(AllergyRecord::class, 'medical_history_id');
    }

    public function surgeryRecords()
    {
        return $this->hasMany(SurgeryRecord::class, 'medical_history_id');
    }

    public function medicalConsultationRecords()
    {
        return $this->hasMany(MedicalConsultationRecord::class, 'medical_history_id');
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
