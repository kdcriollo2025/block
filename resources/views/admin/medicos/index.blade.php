@extends('adminlte::page')

@section('title', 'Lista de Médicos')

@section('content_header')
    <h1>Lista de Médicos</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.medicos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Médico
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Email</th>
                    <th>Especialidad</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicos as $medico)
                    <tr>
                        <td>{{ $medico->name }}</td>
                        <td>{{ $medico->cedula }}</td>
                        <td>{{ $medico->email }}</td>
                        <td>{{ $medico->specialty }}</td>
                        <td>{{ $medico->phone_number }}</td>
                        <td>
                            <span class="badge badge-{{ $medico->is_active ? 'success' : 'danger' }}">
                                {{ $medico->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.medicos.edit', $medico) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.medicos.toggle-status', $medico) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-{{ $medico->is_active ? 'warning' : 'success' }}">
                                        <i class="fas fa-{{ $medico->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                }
            });
        });
    </script>
@stop 