@extends('adminlte::page')

@section('title', 'Test Dashboard')

@section('content_header')
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h3>Vista de Prueba</h3>
            <p>{{ $test_message }}</p>
            <p>Fecha: {{ $currentDate }}</p>
            <p>Hora: {{ $currentTime }}</p>
            
            <hr>
            
            <h4>Datos de Prueba:</h4>
            <p>Especialidad: {{ $medico['specialty'] }}</p>
            <p>Teléfono: {{ $medico['phone'] }}</p>
            <p>Cédula: {{ $medico['cedula'] }}</p>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 