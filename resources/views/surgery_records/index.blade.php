@extends('adminlte::page')

@section('title', 'Registros de Cirugía')

@section('content_header')
    <h1>Registros de Cirugía</h1>
@stop

@section('content')
    <a href="{{ route('medico.surgery_records.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <table class="table table-hover table-dark dataTable">
        <thead>
        <tr>
            <th scope="col">Paciente</th>
            <th scope="col">Nombre de la Cirugía</th>
            <th scope="col">Cirujano</th>
            <th scope="col">Fecha de Cirugía</th>
            <th scope="col">Detalles</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($surgeryRecords as $surgery)
            <tr>
                <td>{{ $surgery->medicalHistory->patient->name }}</td>
                <td>{{ $surgery->surgery_name }}</td>
                <td>{{ $surgery->surgeon }}</td>
                <td>{{ $surgery->surgery_date ? $surgery->surgery_date->format('d/m/Y') : '' }}</td>
                <td>{{ Str::limit($surgery->details, 50) }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('medico.surgery_records.edit', ['surgery_record' => $surgery->id]) }}"
                           class="btn btn-warning btn-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('medico.surgery_records.destroy', ['surgery_record' => $surgery->id]) }}" 
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
                "order": [[3, "desc"]]
            });
        });
    </script>
@stop
