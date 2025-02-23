@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Error</div>
                <div class="card-body">
                    <p>Lo sentimos, ha ocurrido un error en el servidor.</p>
                    <p>Por favor, contacte al administrador del sistema.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 