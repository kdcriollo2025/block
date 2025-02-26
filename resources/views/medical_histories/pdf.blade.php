<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .patient-info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .nft-section {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .nft-title {
            color: #2196F3;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .nft-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .qr-container {
            display: inline-block;
            padding: 5px;
            background: white;
        }
        .nft-details {
            font-size: 10px;
            color: #666;
            text-align: left;
            margin-left: 10px;
        }
        .nft-details p {
            margin: 2px 0;
        }
        h4 {
            margin: 10px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Historial Médico</h2>
        <h3>{{ $patient->name }}</h3>
    </div>

    <div class="patient-info">
        <p><strong>Fecha de Nacimiento:</strong> {{ $patient->birth_date }}</p>
        <p><strong>Género:</strong> {{ $patient->gender }}</p>
        <p><strong>Dirección:</strong> {{ $patient->address }}</p>
        <p><strong>Tipo de Sangre:</strong> {{ $patient->blood_type }}</p>
    </div>

    <!-- Sección NFT Compacta -->
    <div class="nft-section">
        <div class="nft-title">Certificado Digital NFT</div>
        <div class="nft-content">
            <div class="qr-container">
                {!! QrCode::size(100)->generate(json_encode([
                    'type' => 'Medical History NFT',
                    'patient' => $patient->name,
                    'doctor' => Auth::user()->name,
                    'timestamp' => $medicalHistory->created_at->format('Y-m-d H:i:s'),
                    'hash' => $medicalHistory->hash
                ])) !!}
            </div>
            <div class="nft-details">
                <p><strong>ID:</strong> {{ $medicalHistory->id }}</p>
                <p><strong>Paciente:</strong> {{ $patient->name }}</p>
                <p><strong>Médico:</strong> {{ Auth::user()->name }}</p>
                <p><strong>Fecha:</strong> {{ $medicalHistory->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Hash:</strong> {{ substr($medicalHistory->hash, 0, 20) }}...</p>
            </div>
        </div>
    </div>

    @if($consultations->count() > 0)
    <h4>Consultas Médicas</h4>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Síntomas</th>
                <th>Diagnóstico</th>
                <th>Tratamiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultations as $consultation)
            <tr>
                <td>{{ $consultation->consultation_date }}</td>
                <td>{{ $consultation->reason }}</td>
                <td>{{ $consultation->symptoms }}</td>
                <td>{{ $consultation->diagnosis }}</td>
                <td>{{ $consultation->treatment }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($allergies->count() > 0)
    <h4>Alergias</h4>
    <table>
        <thead>
            <tr>
                <th>Alergia</th>
                <th>Severidad</th>
                <th>Síntomas</th>
                <th>Fecha de Diagnóstico</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allergies as $allergy)
            <tr>
                <td>{{ $allergy->allergy_name }}</td>
                <td>{{ $allergy->severity_level }}</td>
                <td>{{ $allergy->allergy_symptoms }}</td>
                <td>{{ $allergy->diagnosis_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html> 