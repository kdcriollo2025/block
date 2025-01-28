@extends('adminlte::page')

@section('title', 'Registros de Vacunación')

@section('content_header')
    <h1>Registros de Vacunación</h1>
@stop

@section('content')
    <form action="{{ !isset($vaccinationRecord) ? route('medico.vaccination_records.store') : route('medico.vaccination_records.update', ['vaccination_record' => $vaccinationRecord->id]) }}"
          method="POST">
        @csrf
        @if (isset($vaccinationRecord))
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-4">
                <label for="medical_history_id" class="form-label">Paciente (Historia medica)</label>
                <select name="medical_history_id" id="medical_history_id" class="form-control {{ $errors->has('medical_history_id') ? 'is-invalid' : '' }}">
                    <option value="" {{ !isset($vaccinationRecord) ? 'selected' : '' }} disabled>Seleccione...</option>
                    @foreach($medicalHistories as $medicalHistory)
                        <option value="{{ $medicalHistory->id }}" {{ isset($vaccinationRecord) && $vaccinationRecord->medicalHistory->id == $medicalHistory->id ? 'selected' : '' }}>{{ $medicalHistory->patient->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('medical_history_id'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('medical_history_id')) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-4">
                <label for="vaccine_name" class="form-label">Nombre de la Vacuna</label>
                <input type="text" name="vaccine_name" id="vaccine_name" class="form-control {{ $errors->has('vaccine_name') ? 'is-invalid' : '' }}"
                       value="{{ old('vaccine_name', isset($vaccinationRecord) ? $vaccinationRecord->vaccine_name : '') }}">
                @if ($errors->has('vaccine_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('vaccine_name')) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-4">
                <label for="application_date">Fecha de Aplicación <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" 
                       id="application_date" name="application_date" 
                       value="{{ old('application_date', isset($vaccinationRecord) ? $vaccinationRecord->application_date->format('Y-m-d') : '') }}" required>
                @error('application_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="dose" class="form-label">Dosis</label>
                <input type="text" name="dose" id="dose" class="form-control {{ $errors->has('dose') ? 'is-invalid' : '' }}"
                       value="{{ old('dose', isset($vaccinationRecord) ? $vaccinationRecord->dose : '') }}">
                @if ($errors->has('dose'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('dose')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ isset($vaccinationRecord) ? 'Actualizar' : 'Guardar' }}</button>
                <a href="{{ route('medico.vaccination_records.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
