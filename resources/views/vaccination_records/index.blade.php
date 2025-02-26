@extends('adminlte::page')

@section('title', 'Registros de Vacunación')

@section('content_header')
    <h1>Registros de Vacunación</h1>
@stop

@section('content')
    <a href="{{ route('medico.vaccination_records.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <table class="table table-hover table-dark dataTable">
        <thead>
        <tr>
            <th scope="col">Paciente</th>
            <th scope="col">Vacuna</th>
            <th scope="col">Fecha</th>
            <th scope="col">Dosis</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($vaccinationRecords as $vaccination)
            <tr>
                <td>{{ $vaccination->medicalHistory->patient->name }}</td>
                <td>{{ $vaccination->vaccine_name }}</td>
                <td>{{ $vaccination->application_date ? $vaccination->application_date->format('d/m/Y') : '' }}</td>
                <td>{{ $vaccination->dose }}</td>
                <td>
                    <a href="{{ route('medico.vaccination_records.edit', ['vaccination_record' => $vaccination->id]) }}"
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
                "order": [[2, "desc"]]
            });
        });
    </script>
@stop
