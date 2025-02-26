<div class="card">
    <div class="card-header">
        <h3>Historial Médico</h3>
    </div>
    <div class="card-body">
        <!-- Contenido existente del historial -->
        
        <!-- Sección del NFT -->
        <div class="nft-section mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <h4>NFT Digital Certificate</h4>
                    <p class="text-muted">ID: {{ $nftId }}</p>
                    <div class="qr-code-container">
                        {!! $qrCode !!}
                    </div>
                    <p class="mt-3">
                        <small class="text-muted">
                            Este código QR representa un certificado digital único 
                            del historial médico del paciente.
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.qr-code-container {
    background: white;
    padding: 20px;
    display: inline-block;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.nft-section {
    background: linear-gradient(145deg, #f3f4f6, #ffffff);
    border-radius: 15px;
    padding: 20px;
}
</style> 