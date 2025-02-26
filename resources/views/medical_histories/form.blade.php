@extends('adminlte::page')

@section('title', 'Historial Médico')

@section('content_header')
    <h1>{{ isset($medicalHistory) ? 'Editar Historial Médico' : 'Nuevo Historial Médico' }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($medicalHistory) ? route('medico.medical_histories.update', $medicalHistory->id) : route('medico.medical_histories.store') }}"
                  method="POST">
                @csrf
                @if (isset($medicalHistory))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="patient_id">Paciente <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" 
                                    class="form-control @error('patient_id') is-invalid @enderror" 
                                    required>
                                <option value="">Seleccione un paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                        {{ old('patient_id', isset($medicalHistory) ? $medicalHistory->patient_id : '') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($medicalHistory) ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <a href="{{ route('medico.medical_histories.index') }}" class="btn btn-secondary">
                            Cancelar
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
@stop
