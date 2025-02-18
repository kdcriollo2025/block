<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial Médico - {{ $medicalHistory->patient->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .patient-info {
            margin-bottom: 20px;
        }
        .nft-container {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 150px;
            padding: 10px;
            background: linear-gradient(145deg, #2a2a2a, #3a3a3a);
            border-radius: 10px;
            text-align: center;
        }
        .qr-wrapper {
            background: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .nft-info {
            color: #333;
            font-size: 10px;
        }
        .content {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historial Médico</h1>
    </div>

    <div class="nft-container">
        <div class="qr-wrapper">
            {!! QrCode::size(130)
                ->backgroundColor(255,255,255)
                ->color(0,0,0)
                ->generate(route('medico.medical_histories.show', $medicalHistory->id)) !!}
        </div>
        <div class="nft-info">
            <strong>NFT #{{ $medicalHistory->id }}</strong><br>
            Expediente Médico Digital<br>
            {{ date('d/m/Y') }}
        </div>
    </div>

    <div class="patient-info">
        <h2>Información del Paciente</h2>
        <p><strong>Nombre:</strong> {{ $medicalHistory->patient->full_name }}</p>
        <p><strong>Fecha de Nacimiento:</strong> {{ $medicalHistory->patient->birth_date }}</p>
        <p><strong>Género:</strong> {{ $medicalHistory->patient->gender }}</p>
    </div>

    <div class="content">
        <h2>Historial Clínico</h2>
        {!! $medicalHistory->history !!}
    </div>
</body>
</html> 