@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <h1>Reportes Administrativos</h1>
@stop

@section('content')
    <div class="row">
        <!-- Reporte de Pacientes por Médico -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pacientes por Médico</h3>
                </div>
                <div class="card-body">
                    <p>Análisis del número de pacientes atendidos por cada médico en un período específico.</p>
                    <a href="{{ route('admin.reports.patients-per-doctor') }}" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Diagnósticos Frecuentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Diagnósticos Frecuentes</h3>
                </div>
                <div class="card-body">
                    <p>Lista de los diagnósticos más comunes realizados por los médicos.</p>
                    <a href="{{ route('admin.reports.common-diagnoses') }}" class="btn btn-primary">
                        <i class="fas fa-chart-pie"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Consultas a lo Largo del Tiempo -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Consultas a lo Largo del Tiempo</h3>
                </div>
                <div class="card-body">
                    <p>Análisis de la tendencia de consultas médicas a lo largo del tiempo.</p>
                    <a href="{{ route('admin.reports.consultations-over-time') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Demografía de Pacientes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Demografía de Pacientes</h3>
                </div>
                <div class="card-body">
                    <p>Distribución de pacientes por género y grupos de edad.</p>
                    <a href="{{ route('admin.reports.patient-demographics') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 