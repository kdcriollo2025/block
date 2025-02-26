@extends('adminlte::page')

@section('title', 'Historial Médico')

@section('content_header')
    <h1>Historial Médico</h1>
@stop

@section('content')
    <a href="{{ route('medico.medical_histories.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <table class="table table-hover table-dark dataTable">
        <thead>
        <tr>
            <th scope="col">Paciente</th>
            <th scope="col">Fecha de Creación</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($medicalHistories as $medicalHistory)
            <tr>
                <td>{{ $medicalHistory->patient->name }}</td>
                <td>{{ $medicalHistory->created_at->format('d/m/Y H:i:s') }}</td>
                <td>
                    <a href="{{ route('medico.medical_histories.show', $medicalHistory->id) }}" 
                       class="btn btn-info btn-sm" title="Ver Historial Completo">
                        <i class="fas fa-eye"></i>
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
