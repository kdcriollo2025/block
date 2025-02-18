<?php

namespace App\Services;

use Web3\Web3;
use Web3\Contract;
use App\Models\NFT;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class NFTService
{
    protected $web3;
    protected $contract;

    public function __construct()
    {
        try {
            $this->web3 = new Web3(env('BLOCKCHAIN_RPC_URL'));
            // Aquí iría la configuración del contrato NFT
        } catch (\Exception $e) {
            Log::error('Error initializing Web3: ' . $e->getMessage());
        }
    }

    public function createNFTFromRecord($record)
    {
        try {
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
                'blockchain_hash' => $transaction ? $transaction->hash : null,
                'medical_record_id' => $record->id
            ]);

            return $nft;
        } catch (\Exception $e) {
            Log::error('Error creating NFT: ' . $e->getMessage());
            return null;
        }
    }

    protected function mintNFT($metadata)
    {
        try {
            // Por ahora, simularemos la transacción
            return (object)[
                'hash' => Str::random(64)
            ];
        } catch (\Exception $e) {
            Log::error('Error minting NFT: ' . $e->getMessage());
            return null;
        }
    }

    public function generateQRCode($nft)
    {
        try {
            return \QrCode::size(300)
                ->generate(route('nft.view', $nft->asset_id));
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            return null;
        }
    }
} 