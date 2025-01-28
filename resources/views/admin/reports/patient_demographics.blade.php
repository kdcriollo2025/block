@extends('adminlte::page')

@section('title', 'Reporte de Demografía de Pacientes')

@section('content_header')
    <h1>Reporte de Demografía de Pacientes</h1>
@stop

@section('content')
    <div class="row">
        <!-- Distribución por Género -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Género</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Género</th>
                                            <th>Total</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPatients = $genderDistribution->sum('total');
                                        @endphp
                                        @foreach($genderDistribution as $row)
                                            <tr>
                                                <td>{{ $row->gender }}</td>
                                                <td>{{ $row->total }}</td>
                                                <td>{{ number_format(($row->total / $totalPatients) * 100, 1) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <canvas id="genderChart" style="min-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribución por Grupos de Edad -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Grupos de Edad</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Grupo de Edad</th>
                                            <th>Total</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalByAge = $ageGroups->sum('total');
                                        @endphp
                                        @foreach($ageGroups as $row)
                                            <tr>
                                                <td>{{ $row->age_group }} años</td>
                                                <td>{{ $row->total }}</td>
                                                <td>{{ number_format(($row->total / $totalByAge) * 100, 1) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <canvas id="ageChart" style="min-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Adicionales -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Pacientes</span>
                    <span class="info-box-number">{{ $totalPatients }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-venus-mars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Género Predominante</span>
                    <span class="info-box-number">
                        {{ $genderDistribution->sortByDesc('total')->first()->gender }}
                        ({{ number_format(($genderDistribution->sortByDesc('total')->first()->total / $totalPatients) * 100, 1) }}%)
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-user-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Grupo de Edad Principal</span>
                    <span class="info-box-number">
                        {{ $ageGroups->sortByDesc('total')->first()->age_group }} años
                        ({{ number_format(($ageGroups->sortByDesc('total')->first()->total / $totalByAge) * 100, 1) }}%)
                    </span>
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
        $(document).ready(function() {
            // Gráfico de Género
            const genderData = @json($genderDistribution);
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            
            new Chart(genderCtx, {
                type: 'pie',
                data: {
                    labels: genderData.map(row => row.gender),
                    datasets: [{
                        data: genderData.map(row => row.total),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 206, 86, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Distribución por Género'
                        }
                    }
                }
            });

            // Gráfico de Grupos de Edad
            const ageData = @json($ageGroups);
            const ageCtx = document.getElementById('ageChart').getContext('2d');
            
            new Chart(ageCtx, {
                type: 'bar',
                data: {
                    labels: ageData.map(row => row.age_group + ' años'),
                    datasets: [{
                        label: 'Pacientes',
                        data: ageData.map(row => row.total),
                        backgroundColor: 'rgba(75, 192, 192, 0.8)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribución por Grupos de Edad'
                        }
                    },
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
        });
    </script>
@stop 