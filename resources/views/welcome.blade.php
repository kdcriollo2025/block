@extends('adminlte::page')

@section('title', 'Bienvenido')

@section('content_header')
    <h1>Sistema de Gestión Médica</h1>
@stop

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @auth
        @if(Auth::user()->type === 'medico')
            <script>window.location = "{{ route('medico.dashboard') }}";</script>
        @elseif(Auth::user()->type === 'admin')
            <script>window.location = "{{ route('admin.medicos.index') }}";</script>
        @endif
    @else
        <div class="card">
            <div class="card-body text-center">
                <h2>Bienvenido al Sistema</h2>
                <p class="lead">Por favor, inicie sesión para continuar</p>
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    @endauth
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Welcome page loaded');
    </script>
@stop
