@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Gestión de Médicos</h2>

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

    <div class="table-responsive">
        <table class="table">
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
                            <span class="badge {{ $medico->estado ? 'bg-success' : 'bg-danger' }}">
                                {{ $medico->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.medicos.edit', $medico->id) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
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
@endsection 