<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\NFT;

class BlockchainNFT extends Controller
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('BLOCKCHAIN_NODE_URL', 'http://localhost:3000');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 5.0,
        ]);
    }

    public function createNFT(Request $request)
    {
        try {
            // Validar request
            $validated = $request->validate([
                'asset_id' => 'required|string|unique:nfts',
                'name' => 'required|string',
                'owner' => 'required|string',
            ]);

            // Llamar a la blockchain
            $response = $this->client->post('/invoke', [
                'json' => [
                    'function' => 'CreateNFT',
                    'args' => [
                        $validated['asset_id'],
                        $validated['name'],
                        $validated['owner'],
                    ]
                ]
            ]);

            $blockchainResponse = json_decode($response->getBody()->getContents());

            // Guardar en la base de datos local
            NFT::create($validated);

            return response()->json([
                'message' => 'NFT creado exitosamente',
                'blockchain_response' => $blockchainResponse,
                'data' => $validated
            ], 201);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Error en la conexión con blockchain: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error en la conexión con la blockchain',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error al crear NFT: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al crear NFT',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getNFTs()
    {
        try {
            $response = $this->client->post('/query', [
                'json' => [
                    'function' => 'GetAllNFTs',
                    'args' => []
                ]
            ]);

            $blockchainNFTs = json_decode($response->getBody()->getContents());

            // Obtener NFTs de la base de datos local
            $localNFTs = NFT::all();

            return response()->json([
                'blockchain_nfts' => $blockchainNFTs,
                'local_nfts' => $localNFTs
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener NFTs: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener NFTs',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getNFTByAssetId($assetId)
    {
        try {
            $response = $this->client->post('/query', [
                'json' => [
                    'function' => 'GetNFT',
                    'args' => [$assetId]
                ]
            ]);

            return response()->json(json_decode($response->getBody()->getContents()));

        } catch (\Exception $e) {
            Log::error('Error al obtener NFT: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener NFT',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function transferNFT(Request $request)
    {
        try {
            $validated = $request->validate([
                'asset_id' => 'required|string|exists:nfts',
                'new_owner' => 'required|string'
            ]);

            $response = $this->client->post('/invoke', [
                'json' => [
                    'function' => 'TransferNFT',
                    'args' => [
                        $validated['asset_id'],
                        $validated['new_owner']
                    ]
                ]
            ]);

            // Actualizar en la base de datos local
            NFT::where('asset_id', $validated['asset_id'])
                ->update(['owner' => $validated['new_owner']]);

            return response()->json([
                'message' => 'NFT transferido exitosamente',
                'blockchain_response' => json_decode($response->getBody()->getContents())
            ]);

        } catch (\Exception $e) {
            Log::error('Error al transferir NFT: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al transferir NFT',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 