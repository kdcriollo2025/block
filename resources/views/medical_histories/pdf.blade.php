<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historia Médica - {{ $patient->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section-title { background-color: #f0f0f0; padding: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historia Médica</h1>
        <h2>{{ $patient->name }}</h2>
    </div>

    <!-- Información del Paciente -->
    <div class="section">
        <h3 class="section-title">Información del Paciente</h3>
        <p><strong>Nombre:</strong> {{ $patient->name }}</p>
        <p><strong>Fecha de Nacimiento:</strong> {{ $patient->birth_date->format('d/m/Y') }}</p>
        <p><strong>Género:</strong> {{ $patient->gender }}</p>
        <p><strong>Dirección:</strong> {{ $patient->address }}</p>
    </div>

    <!-- Consultas Médicas -->
    <div class="section">
        <h3 class="section-title">Consultas Médicas</h3>
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
                        <td>{{ $consultation->consultation_date->format('d/m/Y') }}</td>
                        <td>{{ $consultation->reason }}</td>
                        <td>{{ $consultation->symptoms }}</td>
                        <td>{{ $consultation->diagnosis }}</td>
                        <td>{{ $consultation->treatment }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Alergias -->
    <div class="section">
        <h3 class="section-title">Alergias</h3>
        <table>
            <thead>
                <tr>
                    <th>Alergia</th>
                    <th>Severidad</th>
                    <th>Síntomas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allergies as $allergy)
                    <tr>
                        <td>{{ $allergy->allergy_name }}</td>
                        <td>{{ $allergy->severity_level }}</td>
                        <td>{{ $allergy->allergy_symptoms }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Cirugías -->
    <div class="section">
        <h3 class="section-title">Cirugías</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cirugía</th>
                    <th>Cirujano</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surgeries as $surgery)
                    <tr>
                        <td>{{ $surgery->surgery_date->format('d/m/Y') }}</td>
                        <td>{{ $surgery->surgery_name }}</td>
                        <td>{{ $surgery->surgeon }}</td>
                        <td>{{ $surgery->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Documento generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 