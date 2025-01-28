@extends('adminlte::page')

@section('title', 'Registros de Cirugía')

@section('content_header')
    <h1>Registros de Cirugía</h1>
@stop

@section('content')
    <form action="{{ !isset($surgeryRecord) ? route('medico.surgery_records.store') : route('medico.surgery_records.update', ['surgery_record' => $surgeryRecord->id]) }}"
          method="POST">
        @csrf
        @if (isset($surgeryRecord))
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-4">
                <label for="medical_history_id" class="form-label">Paciente (Historia medica)</label>
                <select name="medical_history_id" id="medical_history_id" class="form-control {{ $errors->has('medical_history_id') ? 'is-invalid' : '' }}">
                    <option value="" {{ !isset($surgeryRecord) ? 'selected' : '' }} disabled>Seleccione...</option>
                    @foreach($medicalHistories as $medicalHistory)
                        <option value="{{ $medicalHistory->id }}" {{ isset($surgeryRecord) && $surgeryRecord->medicalHistory->id == $medicalHistory->id ? 'selected' : '' }}>{{ $medicalHistory->patient->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('medical_history_id'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('medical_history_id')) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-4">
                <label for="surgery_name" class="form-label">Nombre de la Cirugía <span class="text-danger">*</span></label>
                <input type="text" name="surgery_name" id="surgery_name" class="form-control @error('surgery_name') is-invalid @enderror"
                       value="{{ old('surgery_name', $surgeryRecord->surgery_name ?? '') }}" required>
                @error('surgery_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-4">
                <label for="surgeon" class="form-label">Cirujano <span class="text-danger">*</span></label>
                <input type="text" name="surgeon" id="surgeon" class="form-control @error('surgeon') is-invalid @enderror"
                       value="{{ old('surgeon', $surgeryRecord->surgeon ?? '') }}" required>
                @error('surgeon')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-4">
                <label for="surgery_date" class="form-label">Fecha de la Cirugía <span class="text-danger">*</span></label>
                <input type="date" name="surgery_date" id="surgery_date" class="form-control @error('surgery_date') is-invalid @enderror"
                       value="{{ old('surgery_date', isset($surgeryRecord) ? $surgeryRecord->surgery_date->format('Y-m-d') : '') }}" required>
                @error('surgery_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-4">
                <label for="details" class="form-label">Detalles <span class="text-danger">*</span></label>
                <textarea name="details" id="details" class="form-control @error('details') is-invalid @enderror"
                          rows="3" required>{{ old('details', $surgeryRecord->details ?? '') }}</textarea>
                @error('details')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ isset($surgeryRecord) ? 'Actualizar' : 'Guardar' }}</button>
                <a href="{{ route('medico.surgery_records.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
