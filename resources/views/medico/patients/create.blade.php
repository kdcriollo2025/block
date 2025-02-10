@extends('adminlte::page')

@section('title', 'Registrar Paciente')

@section('content_header')
    <h1>Registrar Nuevo Paciente</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('medico.patients.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cedula">Cédula</label>
                        <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                               value="{{ old('cedula') }}" maxlength="10" required>
                        @error('cedula')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone_number">Teléfono</label>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                               value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Resto de los campos del formulario --}}

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Registrar Paciente</button>
                <a href="{{ route('medico.patients.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@stop 