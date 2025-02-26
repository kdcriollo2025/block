@extends('adminlte::page')

@section('title', 'Consultas Médicas')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Consultas Médicas</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <a href="{{ route('medico.medical_histories.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Consulta
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Motivo</th>
                            <th>Síntomas</th>
                            <th>Diagnóstico</th>
                            <th>Tratamiento</th>
                            <th>Próxima Cita</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultations as $consultation)
                            <tr>
                                <td>{{ $consultation->consultation_date->format('d/m/Y') }}</td>
                                <td>{{ $consultation->medicalHistory->patient->name }}</td>
                                <td>{{ $consultation->reason }}</td>
                                <td>{{ $consultation->symptoms }}</td>
                                <td>{{ $consultation->diagnosis }}</td>
                                <td>{{ $consultation->treatment }}</td>
                                <td>{{ $consultation->next_appointment ? $consultation->next_appointment->format('d/m/Y') : 'No programada' }}</td>
                                <td>
                                    <a href="{{ route('medico.medical_histories.show', $consultation->medicalHistory) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay consultas médicas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    function redirectToNewConsultation(medicalHistoryId) {
        if (medicalHistoryId) {
            window.location.href = '/medico/medical-histories/' + medicalHistoryId + '/consultations/create';
        }
    }
</script>
@stop
