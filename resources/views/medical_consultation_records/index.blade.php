@extends('adminlte::page')

@section('title', 'Registros de Consultas Médicas')

@section('content_header')
    <h1>Registros de Consultas Médicas</h1>
@stop

@section('content')
    <a href="{{ route('medico.medical_consultation_records.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <table class="table table-hover table-dark dataTable">
        <thead>
        <tr>
            <th scope="col">Paciente</th>
            <th scope="col">Fecha</th>
            <th scope="col">Síntomas</th>
            <th scope="col">Diagnóstico</th>
            <th scope="col">Tratamiento</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($medicalConsultationRecords as $consultation)
            <tr>
                <td>{{ $consultation->medicalHistory->patient->name }}</td>
                <td>{{ $consultation->consultation_date->format('d/m/Y') }}</td>
                <td>{{ Str::limit($consultation->reported_symptoms, 50) }}</td>
                <td>{{ Str::limit($consultation->diagnosis, 50) }}</td>
                <td>{{ Str::limit($consultation->treatment, 50) }}</td>
                <td>
                    <a href="{{ route('medico.medical_consultation_records.edit', ['medical_consultation_record' => $consultation->id]) }}"
                       class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[1, "desc"]]
            });
        });
    </script>
@stop
