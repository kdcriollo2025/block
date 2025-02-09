@extends('adminlte::page')

@section('title', 'Nueva Consulta Médica')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Registrar Nueva Consulta Médica</h1>
        <a href="{{ route('medico.medical_consultation_records.index') }}" class="btn btn-secondary">
            <i class="fas fa-list"></i> Ver Lista de Consultas
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('medico.medical_consultation_records.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="medical_history_id">Paciente</label>
                        <select name="medical_history_id" id="medical_history_id" class="form-control" required>
                            <option value="">Seleccione un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->history_id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reason">Motivo de la consulta</label>
                        <input type="text" name="reason" id="reason" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="symptoms">Síntomas</label>
                        <textarea name="symptoms" id="symptoms" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="consultation_date">Fecha de consulta</label>
                        <input type="date" name="consultation_date" id="consultation_date" class="form-control" required>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="diagnosis">Diagnóstico</label>
                        <textarea name="diagnosis" id="diagnosis" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="treatment">Tratamiento</label>
                        <textarea name="treatment" id="treatment" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="next_appointment">Próxima cita (opcional)</label>
                        <input type="date" name="next_appointment" id="next_appointment" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Consulta
                    </button>
                    <a href="{{ route('medico.medical_consultation_records.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer la fecha actual como valor predeterminado
        document.getElementById('consultation_date').valueAsDate = new Date();
        
        // Validar que la próxima cita sea después de la fecha de consulta
        document.getElementById('consultation_date').addEventListener('change', function() {
            document.getElementById('next_appointment').min = this.value;
        });
    });
</script>
@stop 