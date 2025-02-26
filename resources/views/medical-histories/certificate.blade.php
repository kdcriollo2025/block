<!DOCTYPE html>
<html>
<head>
    <style>
        .certificate-container {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .qr-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
        }
        .nft-id {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Contenido existente del certificado -->
        
        <div class="qr-section">
            <h4>Certificado Digital (NFT)</h4>
            {!! $qrCode !!}
            <div class="nft-id">
                ID: {{ $nftId }}
            </div>
        </div>
    </div>
</body>
</html> 