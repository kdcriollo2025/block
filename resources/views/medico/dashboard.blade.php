@extends('adminlte::page')

@section('title', 'Dashboard de Prueba')

@section('content_header')
    <h1>Dashboard de Prueba</h1>
@stop

@section('content')
    <div class="alert alert-info">
        <h4>Información de Prueba</h4>
        <p>{{ $test_message }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Datos Básicos</h3>
        </div>
        <div class="card-body">
            <p>Fecha: {{ $currentDate }}</p>
            <p>Hora: {{ $currentTime }}</p>
            <hr>
            <p>Especialidad: {{ $medico['specialty'] }}</p>
            <p>Teléfono: {{ $medico['phone'] }}</p>
            <p>Cédula: {{ $medico['cedula'] }}</p>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 