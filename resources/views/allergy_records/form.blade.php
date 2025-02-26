@extends('adminlte::page')

@section('title', 'Registros de Alergias')

@section('content_header')
    <h1>Registros de Alergias</h1>
@stop

@section('content')
    <form action="{{ !isset($allergyRecord) ? route('medico.allergy_records.store') : route('medico.allergy_records.update', ['allergy_record' => $allergyRecord->id]) }}"
          method="POST">
        @csrf
        @if (isset($allergyRecord))
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-4">
                <label for="medical_history_id" class="form-label">Paciente (Historia médica)</label>
                <select name="medical_history_id" id="medical_history_id" class="form-control @error('medical_history_id') is-invalid @enderror" required>
                    <option value="" {{ !isset($allergyRecord) ? 'selected' : '' }} disabled>Seleccione...</option>
                    @foreach($medicalHistories as $medicalHistory)
                        <option value="{{ $medicalHistory->id }}" 
                            {{ (isset($allergyRecord) && $allergyRecord->medical_history_id == $medicalHistory->id) || old('medical_history_id') == $medicalHistory->id ? 'selected' : '' }}>
                            {{ $medicalHistory->patient->name }}
                        </option>
                    @endforeach
                </select>
                @error('medical_history_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-4">
                <label for="allergy_name" class="form-label">Nombre de la Alergia <span class="text-danger">*</span></label>
                <input type="text" name="allergy_name" id="allergy_name" class="form-control @error('allergy_name') is-invalid @enderror"
                       value="{{ old('allergy_name', $allergyRecord->allergy_name ?? '') }}" required>
                @error('allergy_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-4">
                <label for="severity_level" class="form-label">Nivel de Severidad <span class="text-danger">*</span></label>
                <select name="severity_level" id="severity_level" class="form-control @error('severity_level') is-invalid @enderror" required>
                    <option value="" {{ !isset($allergyRecord) ? 'selected' : '' }} disabled>Seleccione...</option>
                    <option value="Leve" {{ (isset($allergyRecord) && $allergyRecord->severity_level == 'Leve') || old('severity_level') == 'Leve' ? 'selected' : '' }}>Leve</option>
                    <option value="Moderada" {{ (isset($allergyRecord) && $allergyRecord->severity_level == 'Moderada') || old('severity_level') == 'Moderada' ? 'selected' : '' }}>Moderada</option>
                    <option value="Grave" {{ (isset($allergyRecord) && $allergyRecord->severity_level == 'Grave') || old('severity_level') == 'Grave' ? 'selected' : '' }}>Grave</option>
                </select>
                @error('severity_level')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-4">
                <label for="diagnosis_date" class="form-label">Fecha de Diagnóstico <span class="text-danger">*</span></label>
                <input type="date" name="diagnosis_date" id="diagnosis_date" class="form-control @error('diagnosis_date') is-invalid @enderror"
                       value="{{ old('diagnosis_date', isset($allergyRecord) ? $allergyRecord->diagnosis_date->format('Y-m-d') : '') }}" required>
                @error('diagnosis_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-8">
                <label for="allergy_symptoms" class="form-label">Síntomas <span class="text-danger">*</span></label>
                <textarea name="allergy_symptoms" id="allergy_symptoms" class="form-control @error('allergy_symptoms') is-invalid @enderror"
                          rows="3" required>{{ old('allergy_symptoms', $allergyRecord->allergy_symptoms ?? '') }}</textarea>
                @error('allergy_symptoms')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ isset($allergyRecord) ? 'Actualizar' : 'Guardar' }}</button>
                <a href="{{ route('medico.allergy_records.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
