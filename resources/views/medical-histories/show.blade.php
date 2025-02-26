<div class="mt-4">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nftModal">
        Verificar NFT
    </button>
</div>

<!-- Modal NFT -->
<div class="modal fade" id="nftModal" tabindex="-1" aria-labelledby="nftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nftModalLabel">Verificación NFT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <h6>Historial Médico NFT</h6>
                    <p>ID: {{ $medicalHistory->id }}</p>
                    <p>Paciente: {{ $medicalHistory->patient->name }}</p>
                    <p>Hash: {{ $medicalHistory->hash }}</p>
                </div>
                <div class="qr-code">
                    {!! $qrCode !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> 