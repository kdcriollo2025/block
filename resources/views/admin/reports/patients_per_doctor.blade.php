@extends('adminlte::page')

@section('title', 'Reporte de Pacientes por Médico')

@section('content_header')
    <h1>Reporte de Pacientes por Médico</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar período</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.patients-per-doctor') }}" class="mb-4">
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

            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>Médico</th>
                            <th>Total de Pacientes</th>
                            <th>Total de Consultas</th>
                            <th>Promedio de Consultas por Paciente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $row)
                            <tr>
                                <td>{{ $row->doctor_name }}</td>
                                <td>{{ $row->total_patients }}</td>
                                <td>{{ $row->total_consultations }}</td>
                                <td>{{ number_format($row->total_consultations / $row->total_patients, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gráfico de Pacientes por Médico</h3>
        </div>
        <div class="card-body">
            <canvas id="doctorsChart" style="min-height: 300px;"></canvas>
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
                "order": [[1, "desc"]]
            });

            // Preparar datos para el gráfico
            const data = @json($report);
            const ctx = document.getElementById('doctorsChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(row => row.doctor_name),
                    datasets: [{
                        label: 'Total de Pacientes',
                        data: data.map(row => row.total_patients),
                        backgroundColor: 'rgba(60, 141, 188, 0.8)',
                        borderColor: 'rgba(60, 141, 188, 1)',
                        borderWidth: 1
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
        });
    </script>
@stop 