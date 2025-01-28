@extends('adminlte::page')

@section('title', 'Reporte de Diagnósticos Frecuentes')

@section('content_header')
    <h1>Reporte de Diagnósticos Frecuentes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar período</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.common-diagnoses') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Fecha Inicial</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Fecha Final</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <!-- Tabla de Diagnósticos -->
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                                <tr>
                                    <th>Diagnóstico</th>
                                    <th>Total de Casos</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalCases = $report->sum('total');
                                @endphp
                                @foreach($report as $row)
                                    <tr>
                                        <td>{{ $row->diagnosis }}</td>
                                        <td>{{ $row->total }}</td>
                                        <td>{{ number_format(($row->total / $totalCases) * 100, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Gráfico de Diagnósticos -->
                <div class="col-md-6">
                    <canvas id="diagnosesChart" style="min-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $('.dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[1, "desc"]],
                "pageLength": 10
            });

            // Preparar datos para el gráfico
            const data = @json($report);
            const ctx = document.getElementById('diagnosesChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.map(row => row.diagnosis),
                    datasets: [{
                        data: data.map(row => row.total),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Diagnósticos'
                        }
                    }
                }
            });
        });
    </script>
@stop 