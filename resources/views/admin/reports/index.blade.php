@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <h1>Reportes del Sistema</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Pacientes</h3>
                    <p>Pacientes por Doctor</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <a href="{{ route('admin.reports.patients-per-doctor') }}" class="small-box-footer">
                    Ver reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Diagnósticos</h3>
                    <p>Diagnósticos Frecuentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <a href="{{ route('admin.reports.common-diagnoses') }}" class="small-box-footer">
                    Ver reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Consultas</h3>
                    <p>Consultas en el Tiempo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('admin.reports.consultations-over-time') }}" class="small-box-footer">
                    Ver reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Demografía</h3>
                    <p>Demografía de Pacientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.reports.patient-demographics') }}" class="small-box-footer">
                    Ver reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 