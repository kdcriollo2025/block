@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Médicos</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Especialidad</th>
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
                    <td>{{ $medico->estado ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <a href="{{ route('admin.medicos.edit', $medico->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 