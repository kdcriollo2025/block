<?php

namespace App\Observers;

use App\Models\MedicalRecord;
use App\Services\NFTService;

class MedicalRecordObserver
{
    protected $nftService;

    public function __construct(NFTService $nftService)
    {
        $this->nftService = $nftService;
    }

    public function created(MedicalRecord $record)
    {
        // Crear NFT cuando se crea un nuevo registro médico
        $this->nftService->createNFTFromRecord($record);
    }

    public function deleted(MedicalRecord $record)
    {
        // El NFT permanecerá en la blockchain aunque el registro se elimine
        // Podemos registrar esta eliminación en la blockchain si es necesario
        $this->nftService->recordDeletion($record);
    }
} 