@extends('adminlte::page')

@section('title', 'Demografía de Pacientes')

@section('content_header')
    <h1>Demografía de Pacientes</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribución por Género</h3>
            </div>
            <div class="card-body">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribución por Edad</h3>
            </div>
            <div class="card-body">
                <canvas id="ageChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tipos de Sangre</h3>
            </div>
            <div class="card-body">
                <canvas id="bloodTypeChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alergias Comunes</h3>
            </div>
            <div class="card-body">
                <canvas id="allergiesChart"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Género
    new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: ['Masculino', 'Femenino'],
            datasets: [{
                data: [{{ $genderData['M'] ?? 0 }}, {{ $genderData['F'] ?? 0 }}],
                backgroundColor: ['#36A2EB', '#FF6384']
            }]
        }
    });

    // Gráfico de Edad
    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: ['0-18', '19-30', '31-50', '51-70', '70+'],
            datasets: [{
                label: 'Pacientes por Rango de Edad',
                data: {{ json_encode($ageData) }},
                backgroundColor: '#4BC0C0'
            }]
        }
    });

    // Gráfico de Tipos de Sangre
    new Chart(document.getElementById('bloodTypeChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($bloodTypeData)) !!},
            datasets: [{
                data: {!! json_encode(array_values($bloodTypeData)) !!},
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#36A2EB'
                ]
            }]
        }
    });

    // Gráfico de Alergias
    new Chart(document.getElementById('allergiesChart'), {
        type: 'horizontalBar',
        data: {
            labels: {!! json_encode(array_keys($allergiesData)) !!},
            datasets: [{
                label: 'Número de Pacientes',
                data: {!! json_encode(array_values($allergiesData)) !!},
                backgroundColor: '#FF9F40'
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});
</script>
@stop 