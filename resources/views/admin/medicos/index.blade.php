@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Gestión de Médicos</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
                @foreach($medicos as $medico)
                    <tr>
                        <td>{{ $medico->id }}</td>
                        <td>{{ $medico->user->name }}</td>
                        <td>{{ $medico->user->email }}</td>
                        <td>{{ $medico->specialty }}</td>
                        <td>{{ $medico->phone_number }}</td>
                        <td>{{ $medico->estado ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <a href="{{ route('admin.medicos.edit', $medico->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 