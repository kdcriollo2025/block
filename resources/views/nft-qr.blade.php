<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFT QR Code</title>
    <style>
        .nft-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: linear-gradient(145deg, #2a2a2a, #3a3a3a);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        
        .qr-wrapper {
            background: white;
            padding: 20px;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .qr-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                #ff3366,
                #ff6b6b,
                #4ecdc4,
                #45b7d1,
                #ff3366
            );
            animation: rotate 10s linear infinite;
            opacity: 0.1;
        }
        
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .nft-info {
            color: white;
            margin-top: 15px;
            text-align: center;
        }
        
        .nft-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .nft-details {
            font-size: 14px;
            color: #cccccc;
        }
    </style>
</head>
<body>
    <div class="nft-container">
        <div class="qr-wrapper">
            {!! QrCode::size(300)
                ->backgroundColor(255,255,255)
                ->color(0,0,0)
                ->generate('https://tu-url-aqui.com') !!}
        </div>
        <div class="nft-info">
            <div class="nft-title">NFT #1234</div>
            <div class="nft-details">
                Colección: QR Art Collection<br>
                Creador: Tu Nombre<br>
                Fecha: {{ date('d/m/Y') }}
            </div>
        </div>
    </div>
</body>
</html> 