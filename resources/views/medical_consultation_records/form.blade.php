@extends('adminlte::page')

@section('title', 'Registros de Consultas Médicas')

@section('content_header')
    <h1>Registros de Consultas Médicas</h1>
@stop

@section('content')
    <form action="{{ route('medico.medical_consultation_records.store') }}" method="POST">
        @csrf
        <input type="hidden" name="medical_history_id" value="{{ $medicalHistory->id }}">
        
        <div class="form-group">
            <label for="consultation_date">Fecha de Consulta</label>
            <input type="date" name="consultation_date" class="form-control @error('consultation_date') is-invalid @enderror" 
                   value="{{ old('consultation_date', date('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="reason">Motivo de Consulta</label>
            <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror" 
                   value="{{ old('reason') }}">
        </div>

        <div class="form-group">
            <label for="symptoms">Síntomas</label>
            <input type="text" name="symptoms" class="form-control @error('symptoms') is-invalid @enderror" 
                   value="{{ old('symptoms') }}" required>
        </div>

        <div class="form-group">
            <label for="diagnosis">Diagnóstico</label>
            <input type="text" name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" 
                   value="{{ old('diagnosis') }}" required>
        </div>

        <div class="form-group">
            <label for="treatment">Tratamiento</label>
            <input type="text" name="treatment" class="form-control @error('treatment') is-invalid @enderror" 
                   value="{{ old('treatment') }}" required>
        </div>

        <div class="form-group">
            <label for="next_appointment">Próxima Cita</label>
            <input type="date" name="next_appointment" class="form-control @error('next_appointment') is-invalid @enderror" 
                   value="{{ old('next_appointment') }}">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Consulta</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
