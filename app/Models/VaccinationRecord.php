<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_history_id',
        'vaccine_name',
        'application_date',
        'dose',
        'next_application_date'
    ];

    protected $casts = [
        'application_date' => 'date',
        'next_application_date' => 'date'
    ];

    public function medicalHistory()
    {
        return $this->belongsTo(MedicalHistory::class, 'medical_history_id');
    }
}
