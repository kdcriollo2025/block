@extends('adminlte::page')

@section('title', 'Diagnósticos Frecuentes')

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
                            <input type="date" 
                                   class="form-control" 
                                   id="start_date" 
                                   name="start_date"
                                   value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Fecha Final</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="end_date" 
                                   name="end_date"
                                   value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>

            @if(isset($data) && $data->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Diagnóstico</th>
                                <th>Total de Casos</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $record)
                                <tr>
                                    <td>{{ $record['diagnostico'] }}</td>
                                    <td>{{ $record['total_casos'] }}</td>
                                    <td>{{ $record['porcentaje'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No hay datos disponibles para el período seleccionado.
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Validación de fechas
        document.getElementById('end_date').addEventListener('change', function() {
            var startDate = document.getElementById('start_date').value;
            var endDate = this.value;
            
            if(startDate && endDate && startDate > endDate) {
                alert('La fecha final debe ser mayor que la fecha inicial');
                this.value = '';
            }
        });
    </script>
@stop 