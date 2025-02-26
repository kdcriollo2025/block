<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .nft-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
        .nft-title {
            color: #2196F3;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .qr-container {
            margin: 20px auto;
            padding: 15px;
            background: #fff;
            display: inline-block;
        }
        .nft-details {
            font-size: 12px;
            color: #666;
            margin-top: 15px;
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

    <!-- Sección NFT -->
    <div class="nft-section">
        <div class="nft-title">Certificado Digital NFT</div>
        <div class="qr-container">
            {!! $qrCode !!}
        </div>
        <div class="nft-details">
            <p><strong>ID:</strong> {{ $medicalHistory->id }}</p>
            <p><strong>Paciente:</strong> {{ $patient->name }}</p>
            <p><strong>Médico:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Fecha de Emisión:</strong> {{ $medicalHistory->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Hash:</strong> {{ $medicalHistory->hash }}</p>
        </div>
    </div>

    <!-- Resto de las secciones del historial médico -->
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

    <div class="footer">
        <p>Documento generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 