<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurgeryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_history_id',
        'surgery_name',
        'surgeon',
        'surgery_date',
        'details'
    ];

    protected $casts = [
        'surgery_date' => 'date'
    ];

    public function medicalHistory()
    {
        return $this->belongsTo(MedicalHistory::class, 'medical_history_id');
    }
}
