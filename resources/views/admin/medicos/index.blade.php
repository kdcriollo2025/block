@extends('adminlte::page')

@section('title', 'Gestión de Médicos')

@section('content_header')
    <h1>Gestión de Médicos</h1>
@stop

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.medicos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Médico
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filtrar por Especialidad</label>
                        <select class="form-control">
                            <option>Todas las especialidades</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filtrar por Estado</label>
                        <select class="form-control">
                            <option>Todos los estados</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filtrar por № de Pacientes</label>
                        <select class="form-control">
                            <option>Todos</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Búsqueda General</label>
                        <input type="text" class="form-control" placeholder="Buscar...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicos as $medico)
                            <tr>
                                <td>{{ $medico->id }}</td>
                                <td>{{ $medico->user->name ?? 'N/A' }}</td>
                                <td>{{ $medico->user->email ?? 'N/A' }}</td>
                                <td>{{ $medico->specialty }}</td>
                                <td>{{ $medico->phone_number }}</td>
                                <td>
                                    <span class="badge {{ $medico->estado ? 'badge-success' : 'badge-danger' }}">
                                        {{ $medico->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.medicos.edit', $medico->id) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-{{ $medico->estado ? 'danger' : 'success' }} btn-sm"
                                                onclick="toggleEstado({{ $medico->id }})"
                                                title="{{ $medico->estado ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $medico->estado ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay médicos registrados</td>
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
    function toggleEstado(id) {
        if (confirm('¿Está seguro de cambiar el estado del médico?')) {
            axios.patch(`/admin/medicos/${id}/toggle-estado`)
                .then(response => {
                    if (response.data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al cambiar el estado');
                });
        }
    }
    </script>
@stop 