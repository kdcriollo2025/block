@extends('adminlte::page')

@section('title', 'Registros de Terapias')

@section('content_header')
    <h1>Registros de Terapias</h1>
@stop

@section('content')
    <a href="{{ route('medico.therapy_records.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <table class="table table-hover table-dark dataTable">
        <thead>
        <tr>
            <th scope="col">Paciente</th>
            <th scope="col">Tipo de Terapia</th>
            <th scope="col">Fecha Inicio</th>
            <th scope="col">Fecha Fin</th>
            <th scope="col">Detalles</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($therapyRecords as $therapy)
            <tr>
                <td>{{ $therapy->medicalHistory->patient->name }}</td>
                <td>{{ $therapy->type }}</td>
                <td>{{ $therapy->start_date->format('d/m/Y') }}</td>
                <td>{{ $therapy->end_date->format('d/m/Y') }}</td>
                <td>{{ Str::limit($therapy->detail, 50) }}</td>
                <td>
                    <a href="{{ route('medico.therapy_records.edit', ['therapy_record' => $therapy->id]) }}"
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
