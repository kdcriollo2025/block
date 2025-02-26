@extends('adminlte::page')

@section('title', 'Registrar Consulta Médica')

@section('content_header')
    <h1>Registrar Nueva Consulta Médica</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('medico.medical_consultation_records.store') }}" method="POST">
                @csrf
                <input type="hidden" name="medical_history_id" value="{{ $medicalHistory->id }}">
                
                <div class="form-group">
                    <label for="consultation_date">Fecha de Consulta</label>
                    <input type="date" name="consultation_date" class="form-control @error('consultation_date') is-invalid @enderror" 
                           value="{{ old('consultation_date', date('Y-m-d')) }}" required>
                    @error('consultation_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reason">Motivo de Consulta</label>
                    <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror" 
                           value="{{ old('reason') }}">
                    @error('reason')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="symptoms">Síntomas</label>
                    <input type="text" name="symptoms" class="form-control @error('symptoms') is-invalid @enderror" 
                           value="{{ old('symptoms') }}" required>
                    @error('symptoms')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="diagnosis">Diagnóstico</label>
                    <input type="text" name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" 
                           value="{{ old('diagnosis') }}" required>
                    @error('diagnosis')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="treatment">Tratamiento</label>
                    <input type="text" name="treatment" class="form-control @error('treatment') is-invalid @enderror" 
                           value="{{ old('treatment') }}" required>
                    @error('treatment')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="next_appointment">Próxima Cita</label>
                    <input type="date" name="next_appointment" class="form-control @error('next_appointment') is-invalid @enderror" 
                           value="{{ old('next_appointment') }}">
                    @error('next_appointment')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar Consulta</button>
                    <a href="{{ route('medico.medical_histories.show', $medicalHistory) }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
