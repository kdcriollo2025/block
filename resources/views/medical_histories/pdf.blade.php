<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial Médico - {{ $medicalHistory->patient->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 5px 10px;
            margin-bottom: 10px;
            font-weight: bold;
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
        .patient-info {
            margin-bottom: 20px;
        }
        .patient-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historial Médico</h1>
        <h2>{{ $medicalHistory->patient->name }}</h2>
    </div>

    <div class="section">
        <div class="section-title">Información del Paciente</div>
        <div class="patient-info">
            <p><strong>Nombre:</strong> {{ $medicalHistory->patient->name }}</p>
            <p><strong>Fecha de Nacimiento:</strong> {{ $medicalHistory->patient->birth_date->format('d/m/Y') }}</p>
            <p><strong>Género:</strong> {{ $medicalHistory->patient->gender }}</p>
            <p><strong>Dirección:</strong> {{ $medicalHistory->patient->address }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Consultas Médicas</div>
        @if($medicalHistory->medicalConsultationRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Síntomas</th>
                        <th>Diagnóstico</th>
                        <th>Tratamiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalHistory->medicalConsultationRecords as $consultation)
                        <tr>
                            <td>{{ $consultation->consultation_date->format('d/m/Y') }}</td>
                            <td>{{ $consultation->reported_symptoms }}</td>
                            <td>{{ $consultation->diagnosis }}</td>
                            <td>{{ $consultation->treatment }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de consultas médicas</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Alergias</div>
        @if($medicalHistory->allergyRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha de Diagnóstico</th>
                        <th>Nombre de la Alergia</th>
                        <th>Nivel de Severidad</th>
                        <th>Síntomas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalHistory->allergyRecords as $allergy)
                        <tr>
                            <td>{{ $allergy->diagnosis_date ? $allergy->diagnosis_date->format('d/m/Y') : '' }}</td>
                            <td>{{ $allergy->allergy_name }}</td>
                            <td>{{ $allergy->severity_level }}</td>
                            <td>{{ Str::limit($allergy->allergy_symptoms, 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de alergias</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Cirugías</div>
        @if($medicalHistory->surgeryRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha de Cirugía</th>
                        <th>Nombre de la Cirugía</th>
                        <th>Cirujano</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalHistory->surgeryRecords as $surgery)
                        <tr>
                            <td>{{ $surgery->surgery_date ? $surgery->surgery_date->format('d/m/Y') : '' }}</td>
                            <td>{{ $surgery->surgery_name }}</td>
                            <td>{{ $surgery->surgeon }}</td>
                            <td>{{ Str::limit($surgery->details, 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de cirugías</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Vacunas</div>
        @if($medicalHistory->vaccinationRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha de Aplicación</th>
                        <th>Nombre de la Vacuna</th>
                        <th>Dosis</th>
                        <th>Próxima Aplicación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalHistory->vaccinationRecords as $vaccination)
                        <tr>
                            <td>{{ $vaccination->application_date ? $vaccination->application_date->format('d/m/Y') : '' }}</td>
                            <td>{{ $vaccination->vaccine_name }}</td>
                            <td>{{ $vaccination->dose }}</td>
                            <td>{{ $vaccination->next_application_date ? $vaccination->next_application_date->format('d/m/Y') : 'No programada' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de vacunación</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Terapias</div>
        @if($medicalHistory->therapyRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Tipo de Terapia</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalHistory->therapyRecords as $therapy)
                        <tr>
                            <td>{{ $therapy->type }}</td>
                            <td>{{ $therapy->start_date->format('d/m/Y') }}</td>
                            <td>{{ $therapy->end_date->format('d/m/Y') }}</td>
                            <td>{{ $therapy->detail }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de terapias</p>
        @endif
    </div>
</body>
</html> 