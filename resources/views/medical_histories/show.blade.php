@extends('adminlte::page')

@section('title', 'Detalle del Historial Médico')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Historial Médico de {{ $medicalHistory->patient->name }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('medico.medical_histories.download-pdf', $medicalHistory->id) }}" 
               class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Información del Paciente -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Información del Paciente</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Nombre:</strong> {{ $medicalHistory->patient->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Fecha de Nacimiento:</strong> {{ $medicalHistory->patient->birth_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Género:</strong> {{ $medicalHistory->patient->gender }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Dirección:</strong> {{ $medicalHistory->patient->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultas Médicas -->
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Consultas Médicas</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Síntomas</th>
                                    <th>Diagnóstico</th>
                                    <th>Tratamiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalHistory->medicalConsultationRecords as $consultation)
                                    <tr>
                                        <td>{{ $consultation->consultation_date->format('d/m/Y') }}</td>
                                        <td>{{ $consultation->reported_symptoms }}</td>
                                        <td>{{ $consultation->diagnosis }}</td>
                                        <td>{{ $consultation->treatment }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay registros de consultas médicas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alergias -->
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Alergias</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha de Diagnóstico</th>
                                    <th>Nombre de la Alergia</th>
                                    <th>Nivel de Severidad</th>
                                    <th>Síntomas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalHistory->allergyRecords as $allergy)
                                    <tr>
                                        <td>{{ $allergy->diagnosis_date ? $allergy->diagnosis_date->format('d/m/Y') : '' }}</td>
                                        <td>{{ $allergy->allergy_name }}</td>
                                        <td>{{ $allergy->severity_level }}</td>
                                        <td>{{ Str::limit($allergy->allergy_symptoms, 50) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay registros de alergias</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cirugías -->
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Cirugías</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha de Cirugía</th>
                                    <th>Nombre de la Cirugía</th>
                                    <th>Cirujano</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalHistory->surgeryRecords as $surgery)
                                    <tr>
                                        <td>{{ $surgery->surgery_date ? $surgery->surgery_date->format('d/m/Y') : '' }}</td>
                                        <td>{{ $surgery->surgery_name }}</td>
                                        <td>{{ $surgery->surgeon }}</td>
                                        <td>{{ Str::limit($surgery->details, 50) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay registros de cirugías</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Vacunas -->
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Vacunas</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha de Aplicación</th>
                                    <th>Nombre de la Vacuna</th>
                                    <th>Dosis</th>
                                    <th>Próxima Aplicación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalHistory->vaccinationRecords as $vaccination)
                                    <tr>
                                        <td>{{ $vaccination->application_date ? $vaccination->application_date->format('d/m/Y') : '' }}</td>
                                        <td>{{ $vaccination->vaccine_name }}</td>
                                        <td>{{ $vaccination->dose }}</td>
                                        <td>{{ $vaccination->next_application_date ? $vaccination->next_application_date->format('d/m/Y') : 'No programada' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay registros de vacunación</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Terapias -->
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title">Terapias</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo de Terapia</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalHistory->therapyRecords as $therapy)
                                    <tr>
                                        <td>{{ $therapy->type }}</td>
                                        <td>{{ $therapy->start_date->format('d/m/Y') }}</td>
                                        <td>{{ $therapy->end_date->format('d/m/Y') }}</td>
                                        <td>{{ $therapy->detail }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay registros de terapias</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('medico.medical_histories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('medico.medical_consultation_records.create', $medicalHistory) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Consulta
            </a>
            <a href="{{ route('medico.medical_consultation_records.index') }}" class="btn btn-info">
                <i class="fas fa-list"></i> Ver Todas las Consultas
            </a>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop 