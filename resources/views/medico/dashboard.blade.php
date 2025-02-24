@extends('adminlte::page')

@section('title', 'Dashboard Médico')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Médico</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $medico->patients()->count() }}</h3>
                    <p>Pacientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('medico.patients.index') }}" class="small-box-footer">
                    Ver pacientes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $medico->consultations()->count() }}</h3>
                    <p>Consultas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <a href="{{ route('medico.medical_consultation_records.index') }}" class="small-box-footer">
                    Ver consultas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-md mr-1"></i>
                        Información del Médico
                    </h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8">{{ $nombre }}</dd>

                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">{{ $email }}</dd>

                        <dt class="col-sm-4">Especialidad:</dt>
                        <dd class="col-sm-8">{{ $medico->specialty }}</dd>

                        <dt class="col-sm-4">Teléfono:</dt>
                        <dd class="col-sm-8">{{ $medico->phone }}</dd>

                        <dt class="col-sm-4">Cédula:</dt>
                        <dd class="col-sm-8">{{ $medico->cedula }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Actividad Reciente
                    </h3>
                </div>
                <div class="card-body">
                    <p>Últimas consultas registradas</p>
                    @if($medico->consultations()->count() > 0)
                        <ul class="timeline">
                            @foreach($medico->consultations()->latest()->take(5)->get() as $consulta)
                                <li>
                                    <i class="fas fa-clock bg-info"></i>
                                    <div class="timeline-item">
                                        <span class="time">
                                            <i class="fas fa-calendar"></i> 
                                            {{ $consulta->created_at->format('d/m/Y H:i') }}
                                        </span>
                                        <h3 class="timeline-header">Consulta con {{ $consulta->patient->name }}</h3>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info">
                            No hay consultas registradas aún.
                        </div>
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
        console.log('Dashboard médico cargado');
    </script>
@stop 