@extends('adminlte::page')

@section('title', 'Registros de Consultas Médicas')

@section('content_header')
    <h1>Registros de Consultas Médicas</h1>
@stop

@section('content')
    <form action="{{ !isset($medicalConsultationRecord) ? route('medico.medical_consultation_records.store') : route('medico.medical_consultation_records.update', ['medical_consultation_record' => $medicalConsultationRecord->id]) }}"
          method="POST">
        @csrf
        @if (isset($medicalConsultationRecord))
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-6">
                <label for="medical_history_id" class="form-label">Paciente (Historia medica)</label>
                <select name="medical_history_id" id="medical_history_id" class="form-control {{ $errors->has('medical_history_id') ? 'is-invalid' : '' }}">
                    <option value="" {{ !isset($medicalConsultationRecord) ? 'selected' : '' }} disabled>Seleccione...</option>
                    @foreach($medicalHistories as $medicalHistory)
                        <option value="{{ $medicalHistory->id }}" {{ isset($medicalConsultationRecord) && $medicalConsultationRecord->medicalHistory->id == $medicalHistory->id ? 'selected' : '' }}>{{ $medicalHistory->patient->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('medical_history_id'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('medical_history_id')) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-6">
                <label for="consultation_date" class="form-label">Fecha de Consulta</label>
                <input type="date" name="consultation_date" id="consultation_date" class="form-control {{ $errors->has('consultation_date') ? 'is-invalid' : '' }}"
                       value="{{ old('consultation_date', isset($medicalConsultationRecord) ? $medicalConsultationRecord->consultation_date : '') }}">
                @if ($errors->has('consultation_date'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('consultation_date')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="reported_symptoms" class="form-label">Síntomas Reportados</label>
                <textarea name="reported_symptoms" id="reported_symptoms" class="form-control {{ $errors->has('reported_symptoms') ? 'is-invalid' : '' }}" rows="3">{{ old('reported_symptoms', isset($medicalConsultationRecord) ? $medicalConsultationRecord->reported_symptoms : '') }}</textarea>
                @if ($errors->has('reported_symptoms'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('reported_symptoms')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="diagnosis" class="form-label">Diagnóstico</label>
                <textarea name="diagnosis" id="diagnosis" class="form-control {{ $errors->has('diagnosis') ? 'is-invalid' : '' }}" rows="3">{{ old('diagnosis', isset($medicalConsultationRecord) ? $medicalConsultationRecord->diagnosis : '') }}</textarea>
                @if ($errors->has('diagnosis'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('diagnosis')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="treatment" class="form-label">Tratamiento</label>
                <textarea name="treatment" id="treatment" class="form-control {{ $errors->has('treatment') ? 'is-invalid' : '' }}"
                          rows="3">{{ old('treatment', isset($medicalConsultationRecord) ? $medicalConsultationRecord->treatment : '') }}</textarea>
                @if ($errors->has('treatment'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('treatment')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ isset($medicalConsultationRecord) ? 'Actualizar' : 'Guardar' }}</button>
                <a href="{{ route('medico.medical_consultation_records.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
