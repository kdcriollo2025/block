<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historia Médica</h1>
        <h2>{{ $patient->name }}</h2>
    </div>

    <div class="section">
        <h3>Información del Paciente</h3>
        <p><strong>Nombre:</strong> {{ $patient->name }}</p>
        <p><strong>Fecha de Nacimiento:</strong> {{ $patient->birth_date->format('d/m/Y') }}</p>
        <p><strong>Género:</strong> {{ $patient->gender }}</p>
        <p><strong>Dirección:</strong> {{ $patient->address }}</p>
    </div>

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
                <td>{{ $consultation->consultation_date->format('d/m/Y') }}</td>
                <td>{{ $consultation->reason }}</td>
                <td>{{ $consultation->symptoms }}</td>
                <td>{{ $consultation->diagnosis }}</td>
                <td>{{ $consultation->treatment }}</td>
            </tr>
            @endforeach
        </table>
    </div>

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
                <td>{{ $allergy->allergy_symptoms }}</td>
            </tr>
            @endforeach
        </table>
    </div>

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
                <td>{{ $surgery->surgery_date->format('d/m/Y') }}</td>
                <td>{{ $surgery->surgery_name }}</td>
                <td>{{ $surgery->surgeon }}</td>
                <td>{{ $surgery->details }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        <p>Documento generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 