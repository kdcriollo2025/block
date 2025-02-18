<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFT extends Model
{
    use HasFactory;

    protected $table = 'nfts';

    protected $fillable = [
        'asset_id',
        'name',
        'owner',
        'blockchain_hash',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
} 