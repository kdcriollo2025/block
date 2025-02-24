@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Información del Médico</h5>
            <p><strong>Especialidad:</strong> {{ $medico->specialty }}</p>
            <p><strong>Teléfono:</strong> {{ $medico->phone }}</p>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 