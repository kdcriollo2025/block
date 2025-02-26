<div class="nft-verification mt-4">
    <h4>Verificación NFT</h4>
    <div class="qr-code text-center">
        {!! $qrCode !!}
    </div>
    <div class="nft-details text-center mt-2">
        <p>ID: {{ $medicalHistory->id }}</p>
        <p>Hash: {{ $medicalHistory->hash }}</p>
        <p>Fecha de creación: {{ $medicalHistory->created_at->format('Y-m-d H:i:s') }}</p>
    </div>
</div> 