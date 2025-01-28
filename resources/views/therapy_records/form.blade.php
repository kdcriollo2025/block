@extends('adminlte::page')

@section('title', 'Registros de Terapias')

@section('content_header')
    <h1>Registros de Terapias</h1>
@stop

@section('content')
    <form action="{{ !isset($therapyRecord) ? route('medico.therapy_records.store') : route('medico.therapy_records.update', ['therapy_record' => $therapyRecord->id]) }}"
          method="POST">
        @csrf
        @if (isset($therapyRecord))
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-4">
                <label for="medical_history_id" class="form-label">Paciente (Historia medica)</label>
                <select name="medical_history_id" id="medical_history_id" class="form-control {{ $errors->has('medical_history_id') ? 'is-invalid' : '' }}">
                    <option value="" {{ !isset($therapyRecord) ? 'selected' : '' }} disabled>Seleccione...</option>
                    @foreach($medicalHistories as $medicalHistory)
                        <option value="{{ $medicalHistory->id }}" {{ isset($therapyRecord) && $therapyRecord->medicalHistory->id == $medicalHistory->id ? 'selected' : '' }}>{{ $medicalHistory->patient->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('medical_history_id'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('medical_history_id')) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-4">
                <label for="type" class="form-label">Tipo de Terapia</label>
                <input type="text" name="type" id="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                       value="{{ old('type', isset($therapyRecord) ? $therapyRecord->type : '') }}">
                @if ($errors->has('type'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('type')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="start_date" class="form-label">Fecha de Inicio</label>
                <input type="date" name="start_date" id="start_date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                       value="{{ old('start_date', isset($therapyRecord) ? $therapyRecord->start_date : '') }}">
                @if ($errors->has('start_date'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('start_date')) }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-6">
                <label for="end_date" class="form-label">Fecha de Fin</label>
                <input type="date" name="end_date" id="end_date" class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                       value="{{ old('end_date', isset($therapyRecord) ? $therapyRecord->end_date : '') }}">
                @if ($errors->has('end_date'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('end_date')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="detail" class="form-label">Detalles</label>
                <textarea name="detail" id="detail" class="form-control {{ $errors->has('detail') ? 'is-invalid' : '' }}" rows="3">{{ old('detail', isset($therapyRecord) ? $therapyRecord->detail : '') }}</textarea>
                @if ($errors->has('detail'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($errors->first('detail')) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ isset($therapyRecord) ? 'Actualizar' : 'Guardar' }}</button>
                <a href="{{ route('medico.therapy_records.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
