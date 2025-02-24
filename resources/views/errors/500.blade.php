@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Error del Servidor (500)</h5>
                </div>
                <div class="card-body">
                    <h4>Detalles del Error:</h4>
                    @if(config('app.debug'))
                        <div class="alert alert-danger">
                            <strong>Mensaje:</strong> {{ $exception->getMessage() }}<br>
                            <strong>Archivo:</strong> {{ $exception->getFile() }}<br>
                            <strong>Línea:</strong> {{ $exception->getLine() }}<br>
                            <hr>
                            <pre>{{ $exception->getTraceAsString() }}</pre>
                        </div>
                    @else
                        <p>Ha ocurrido un error en el servidor. Por favor, contacte al administrador.</p>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 