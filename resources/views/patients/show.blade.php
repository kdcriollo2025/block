@extends('adminlte::page')

@section('title', 'Detalles del Paciente')

@section('content_header')
    <h1>Detalles del Paciente</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('medico.patients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="{{ route('medico.patients.edit', $patient->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre:</label>
                        <p>{{ $patient->name }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Email:</label>
                        <p>{{ $patient->email }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Teléfono:</label>
                        <p>{{ $patient->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha de Nacimiento:</label>
                        <p>{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Género:</label>
                        <p>{{ $patient->gender }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Sangre:</label>
                        <p>{{ $patient->blood_type ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Dirección:</label>
                        <p>{{ $patient->address }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Número de Consultas:</label>
                        <p>{{ $patient->medicalConsultations ? $patient->medicalConsultations->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            @if($patient->medicalHistory)
            <div class="mt-4">
                <h4>Historial Médico</h4>
                <a href="{{ route('medico.medical_histories.show', $patient->medicalHistory->id) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-file-medical"></i> Ver Historial Médico
                </a>
            </div>
            @endif
        </div>
    </div>
@stop 