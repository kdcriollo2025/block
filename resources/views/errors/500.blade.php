@extends('adminlte::page')

@section('title', 'Error del Servidor')

@section('content_header')
    <h1 class="text-danger">
        <i class="fas fa-exclamation-triangle"></i> Error del Servidor
    </h1>
@stop

@section('content')
    <div class="error-page">
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Algo salió mal.</h3>

            <p>
                {{ $exception->getMessage() ?? 'Ha ocurrido un error en el servidor.' }}
            </p>

            @if(config('app.debug'))
                <div class="callout callout-danger">
                    <h5>Detalles del error:</h5>
                    <p>Archivo: {{ $exception->getFile() }}</p>
                    <p>Línea: {{ $exception->getLine() }}</p>
                    <pre>{{ $exception->getTraceAsString() }}</pre>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .error-page {
            margin: 20px auto 0;
            width: 800px;
            max-width: 100%;
        }
        .error-page > .error-content {
            margin-left: 0;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
    </style>
@stop 