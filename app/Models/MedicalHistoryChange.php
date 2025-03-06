<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistoryChange extends Model
{
    protected $fillable = [
        'medical_history_id',
        'change_type',
        'record_type',
        'record_id',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function medicalHistory()
    {
        return $this->belongsTo(MedicalHistory::class);
    }
} 