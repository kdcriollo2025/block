@extends('adminlte::page')

@section('title', 'Pacientes por Doctor')

@section('content_header')
    <h1>Reporte de Pacientes por Doctor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(isset($data) && $data->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Total de Pacientes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $medico)
                                <tr>
                                    <td>{{ $medico->user->name }}</td>
                                    <td>{{ $medico->pacientes_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No hay datos disponibles.
                </div>
            @endif
        </div>
    </div>
@stop 