@extends('adminlte::page')

@section('title', 'Reporte de Consultas a lo Largo del Tiempo')

@section('content_header')
    <h1>Reporte de Consultas a lo Largo del Tiempo</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar período</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.consultations-over-time') }}" class="mb-4">
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
                <!-- Tabla de Consultas -->
                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Total de Consultas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report as $row)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->format('F Y') }}</td>
                                        <td>{{ $row['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Gráfico de Consultas -->
                <div class="col-md-8">
                    <canvas id="consultationsChart" style="min-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Estadísticas Adicionales -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-calendar-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Consultas</span>
                            <span class="info-box-number">{{ $report->sum('total') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Promedio Mensual</span>
                            <span class="info-box-number">{{ number_format($report->avg('total'), 1) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mes con Más Consultas</span>
                            <span class="info-box-number">
                                {{ $report->max('total') }}
                                ({{ \Carbon\Carbon::createFromFormat('Y-m', $report->sortByDesc('total')->first()['month'])->format('F Y') }})
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mes con Menos Consultas</span>
                            <span class="info-box-number">
                                {{ $report->min('total') }}
                                ({{ \Carbon\Carbon::createFromFormat('Y-m', $report->sortBy('total')->first()['month'])->format('F Y') }})
                            </span>
                        </div>
                    </div>
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
                "order": [[0, "desc"]],
                "pageLength": 12
            });

            // Preparar datos para el gráfico
            const data = @json($report);
            const ctx = document.getElementById('consultationsChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(row => {
                        const date = new Date(row.month + '-01');
                        return date.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Número de Consultas',
                        data: data.map(row => row.total),
                        borderColor: 'rgba(60, 141, 188, 1)',
                        backgroundColor: 'rgba(60, 141, 188, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Tendencia de Consultas Médicas'
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