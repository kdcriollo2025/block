<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_history_id',
        'allergy_name',
        'severity_level',
        'allergy_symptoms',
        'diagnosis_date'
    ];

    protected $casts = [
        'diagnosis_date' => 'date'
    ];

    public function medicalHistory()
    {
        return $this->belongsTo(MedicalHistory::class);
    }
}
