@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @auth
        @if(Auth::user()->type === 'medico')
            <script>window.location = "{{ route('medico.dashboard') }}";</script>
        @elseif(Auth::user()->type === 'admin')
            <script>window.location = "{{ route('admin.medicos.index') }}";</script>
        @else
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Atención</h5>
                Tipo de usuario no reconocido: {{ Auth::user()->type }}
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> Información</h5>
            Por favor inicie sesión para continuar.
        </div>
    @endauth
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Dashboard cargado');
    </script>
@stop
