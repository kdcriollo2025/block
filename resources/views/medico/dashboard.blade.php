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
        <div class="col-12">
            <div class="card bg-gradient-info">
                <div class="card-body">
                    <h5 class="card-title">¡Hola, Dr. {{ $medico->user->name }}!</h5>
                    <p class="card-text">
                        Tienes {{ $totalPatients }} pacientes registrados.
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 