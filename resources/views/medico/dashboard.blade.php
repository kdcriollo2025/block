@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Dashboard de Prueba</h3>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <h4>Información de Prueba</h4>
                        <p>{{ $test_message }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Fecha y Hora</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Fecha:</strong> {{ $currentDate }}</p>
                                    <p><strong>Hora:</strong> {{ $currentTime }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Información del Médico</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Especialidad:</strong> {{ $medico['specialty'] }}</p>
                                    <p><strong>Teléfono:</strong> {{ $medico['phone'] }}</p>
                                    <p><strong>Cédula:</strong> {{ $medico['cedula'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Vista cargada correctamente');
</script>
@endsection 