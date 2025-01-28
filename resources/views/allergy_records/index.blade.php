@extends('adminlte::page')

@section('title', 'Registros de Alergias')

@section('content_header')
    <h1>Registros de Alergias</h1>
@stop

@section('content')
    <a href="{{ route('medico.allergy_records.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <table class="table table-hover table-dark dataTable">
        <thead>
        <tr>
            <th scope="col">Paciente</th>
            <th scope="col">Nombre de la Alergia</th>
            <th scope="col">Nivel de Severidad</th>
            <th scope="col">Síntomas</th>
            <th scope="col">Fecha de Diagnóstico</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($allergyRecords as $allergy)
            <tr>
                <td>{{ $allergy->medicalHistory->patient->name }}</td>
                <td>{{ $allergy->allergy_name }}</td>
                <td>{{ $allergy->severity_level }}</td>
                <td>{{ Str::limit($allergy->allergy_symptoms, 50) }}</td>
                <td>{{ $allergy->diagnosis_date ? $allergy->diagnosis_date->format('d/m/Y') : '' }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('medico.allergy_records.edit', ['allergy_record' => $allergy->id]) }}"
                           class="btn btn-warning btn-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('medico.allergy_records.destroy', ['allergy_record' => $allergy->id]) }}" 
                              method="POST" 
                              style="display: inline;" 
                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                            @csrf
                            @method('DELETE')
                            
                        </form>
                    </div>
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
                "order": [[4, "desc"]]
            });
        });
    </script>
@stop
