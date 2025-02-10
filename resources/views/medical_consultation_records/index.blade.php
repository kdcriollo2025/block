@extends('adminlte::page')

@section('title', 'Consultas Médicas')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Consultas Médicas</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newConsultationModal">
                    <i class="fas fa-plus"></i> Nueva Consulta
                </button>
            </div>
        </div>
    </div>
@stop

@section('content')
    <!-- Modal para seleccionar paciente -->
    <div class="modal fade" id="newConsultationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Seleccionar Paciente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="patient">Paciente</label>
                        <select class="form-control" id="patient" onchange="redirectToNewConsultation(this.value)">
                            <option value="">Seleccione un paciente</option>
                            @foreach(Auth::user()->medico->patients as $patient)
                                <option value="{{ $patient->medicalHistory->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
