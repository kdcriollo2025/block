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
    <!-- Mensaje de Bienvenida -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient-info">
                <div class="card-body">
                    <h5 class="card-title">¡Hola, Dr. {{ $medico->name }}!</h5>
                    <p class="card-text">
                        Aquí está el resumen de tu actividad para hoy. Asegúrate de revisar las citas pendientes y los reportes de salud de los pacientes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row">
        <!-- Total de Pacientes -->
        <div class="col-lg-6 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPatients }}</h3>
                    <p>Pacientes Totales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('medico.patients.index') }}" class="small-box-footer">
                    Ver pacientes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total de Consultas -->
        <div class="col-lg-6 col-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalConsultations }}</h3>
                    <p>Consultas Médicas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <a href="{{ route('medico.medical_consultation_records.index') }}" class="small-box-footer">
                    Ver consultas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Gráfica de Consultas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Consultas Médicas por Mes</h3>
                </div>
                <div class="card-body">
                    <canvas id="consultationsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="medical-history-section">
        <h2>Historial Médico</h2>
        <!-- Contenido del historial médico -->
        
        @if($nft)
        <div class="qr-section">
            <h3>Código QR del Historial Médico</h3>
            <div class="qr-code">
                {!! $nft->qr_code !!}
            </div>
            <p class="text-muted">Escanee este código QR para acceder al historial médico</p>
        </div>
        @endif
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para la gráfica
        const consultationData = @json($consultationsByMonth);
        
        // Preparar datos para Chart.js
        const labels = consultationData.map(item => item.month);
        const data = consultationData.map(item => item.total);

        // Crear la gráfica
        const ctx = document.getElementById('consultationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consultas por Mes',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@stop 