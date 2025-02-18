<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial Médico - {{ $medicalHistory->patient->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
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
            background-color: #f5f5f5;
        }
        .section {
            margin-bottom: 30px;
        }
        .nft-container {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 150px;
            text-align: center;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 10px;
            background: linear-gradient(145deg, #f3f3f3, #ffffff);
        }
        .qr-wrapper {
            background: white;
            padding: 10px;
            border-radius: 8px;
        }
        .nft-info {
            margin-top: 5px;
            font-size: 12px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="nft-container">
        <div class="qr-wrapper">
            {!! QrCode::size(120)
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

    <div class="header">
        <h1>Historial Médico</h1>
        <h2>{{ $medicalHistory->patient->full_name }}</h2>
    </div>

    <div class="section">
        <h3>Información del Paciente</h3>
        <p><strong>Nombre:</strong> {{ $medicalHistory->patient->full_name }}</p>
        <p><strong>Fecha de Nacimiento:</strong> {{ $medicalHistory->patient->birth_date }}</p>
        <p><strong>Género:</strong> {{ $medicalHistory->patient->gender }}</p>
        <p><strong>Dirección:</strong> {{ $medicalHistory->patient->address }}</p>
    </div>

    @if($consultations->count() > 0)
    <div class="section">
        <h3>Consultas Médicas</h3>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Síntomas</th>
                <th>Diagnóstico</th>
                <th>Tratamiento</th>
            </tr>
            @foreach($consultations as $consultation)
            <tr>
                <td>{{ $consultation->consultation_date }}</td>
                <td>{{ $consultation->reason }}</td>
                <td>{{ $consultation->symptoms }}</td>
                <td>{{ $consultation->diagnosis }}</td>
                <td>{{ $consultation->treatment }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    @if($allergies->count() > 0)
    <div class="section">
        <h3>Alergias</h3>
        <table>
            <tr>
                <th>Alergia</th>
                <th>Severidad</th>
                <th>Síntomas</th>
            </tr>
            @foreach($allergies as $allergy)
            <tr>
                <td>{{ $allergy->allergy_name }}</td>
                <td>{{ $allergy->severity_level }}</td>
                <td>{{ $allergy->symptoms }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    @if($surgeries->count() > 0)
    <div class="section">
        <h3>Cirugías</h3>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Cirugía</th>
                <th>Cirujano</th>
                <th>Detalles</th>
            </tr>
            @foreach($surgeries as $surgery)
            <tr>
                <td>{{ $surgery->surgery_date }}</td>
                <td>{{ $surgery->surgery_name }}</td>
                <td>{{ $surgery->surgeon }}</td>
                <td>{{ $surgery->details }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ date('d/m/Y H:i:s') }}</p>
        <p>Este documento es un registro médico oficial y confidencial.</p>
    </div>
</body>
</html> 