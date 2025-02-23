@extends('adminlte::page')

@section('title', 'Médicos')

@section('content_header')
    <h1>Médicos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Médicos</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.medicos.create') }}" class="btn btn-primary">
                            Nuevo Médico
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($medicos) && $medicos->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Especialidad</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicos as $medico)
                                    <tr>
                                        <td>{{ $medico->user->name ?? 'N/A' }}</td>
                                        <td>{{ $medico->cedula }}</td>
                                        <td>{{ $medico->specialty }}</td>
                                        <td>{{ $medico->phone_number }}</td>
                                        <td>{{ $medico->user->email ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.medicos.edit', $medico->id) }}" 
                                               class="btn btn-sm btn-info">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No hay médicos registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Cualquier JavaScript adicional que necesites
    </script>
@stop 