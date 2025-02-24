@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <h5 class="text-muted">{{ $currentDate }}</h5>
                <h5 class="text-muted text-right">{{ $currentTime }}</h5>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-6 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalPatients }}</h3>
                    <p>Pacientes Totales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('medico.patients.index') }}" class="small-box-footer">
                    Ver pacientes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalConsultations }}</h3>
                    <p>Consultas Médicas</p>
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
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 