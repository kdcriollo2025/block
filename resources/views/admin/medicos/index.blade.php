@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Gestión de Médicos</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4">
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
                @foreach($medicos as $medico)
                <tr>
                    <td>{{ $medico->id }}</td>
                    <td>{{ $medico->user->name }}</td>
                    <td>{{ $medico->user->email }}</td>
                    <td>{{ $medico->specialty }}</td>
                    <td>{{ $medico->phone_number }}</td>
                    <td>
                        <span class="badge {{ $medico->estado ? 'badge-success' : 'badge-danger' }}">
                            {{ $medico->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.medicos.edit', $medico->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm {{ $medico->estado ? 'btn-danger' : 'btn-success' }}" 
                                onclick="toggleEstado({{ $medico->id }})">
                            <i class="fas {{ $medico->estado ? 'fa-ban' : 'fa-check' }}"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
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
@endpush
@endsection 