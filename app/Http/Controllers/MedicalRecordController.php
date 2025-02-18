<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Services\NFTService;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    protected $nftService;

    public function __construct(NFTService $nftService)
    {
        $this->nftService = $nftService;
    }

    public function index()
    {
        $records = MedicalRecord::with(['patient', 'doctor', 'nft'])->paginate(10);
        return view('medico.records.index', compact('records'));
    }

    public function show(MedicalRecord $record)
    {
        return view('medico.records.show', compact('record'));
    }
} 