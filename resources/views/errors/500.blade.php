@extends('adminlte::page')

@section('title', 'Error')

@section('content_header')
    <h1>Error del Servidor</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-danger">500</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Algo salió mal.</h3>
            <p>
                Estamos trabajando para solucionar este problema.
                Mientras tanto, puedes <a href="{{ route('home') }}">volver al inicio</a>.
            </p>
        </div>
    </div>
@stop 