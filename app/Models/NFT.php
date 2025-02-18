<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFT extends Model
{
    use HasFactory;

    protected $table = 'nfts';

    protected $fillable = [
        'patient_id',
        'qr_code'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
} 