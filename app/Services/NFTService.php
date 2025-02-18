<?php

namespace App\Services;

use Web3\Web3;
use Web3\Contract;
use App\Models\NFT;
use Illuminate\Support\Str;

class NFTService
{
    protected $web3;
    protected $contract;

    public function __construct()
    {
        $this->web3 = new Web3(env('BLOCKCHAIN_RPC_URL'));
        // Aquí iría la configuración del contrato NFT
    }

    public function createNFTFromRecord($record)
    {
        // Generar metadata del NFT
        $metadata = [
            'name' => "Medical Record #" . $record->id,
            'description' => "Medical record created at " . $record->created_at,
            'attributes' => [
                'patient_id' => $record->patient_id,
                'doctor_id' => $record->doctor_id,
                'timestamp' => $record->created_at,
            ]
        ];

        // Crear el NFT en la blockchain
        $transaction = $this->mintNFT($metadata);

        // Guardar el registro en la base de datos
        $nft = NFT::create([
            'asset_id' => Str::uuid(),
            'name' => $metadata['name'],
            'owner' => $record->patient_id,
            'blockchain_hash' => $transaction->hash,
        ]);

        return $nft;
    }

    protected function mintNFT($metadata)
    {
        // Lógica para mintear el NFT en la blockchain
        // Este es un ejemplo simplificado
        return $this->contract->methods->mint(
            env('BLOCKCHAIN_PRIVATE_KEY'),
            json_encode($metadata)
        )->send();
    }

    public function generateQRCode($nft)
    {
        // Generar QR code con la información del NFT
        return QrCode::size(300)
            ->generate(route('nft.view', $nft->asset_id));
    }
} 