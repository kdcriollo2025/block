@extends('adminlte::page')

@section('title', 'Médicos')

@section('content_header')
    <h1>{{ isset($medico) ? 'Editar Médico' : 'Registrar Nuevo Médico' }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ isset($medico) ? route('admin.medicos.update', $medico->id) : route('admin.medicos.store') }}"
                  method="POST">
                @csrf
                @if (isset($medico))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $medico->name ?? '') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                        <label for="cedula">Cédula</label>
                        <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                               value="{{ old('cedula') }}" maxlength="10" required>
                        @error('cedula')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                        <div class="form-group">
                            <label for="specialty">Especialidad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('specialty') is-invalid @enderror" 
                                   id="specialty" name="specialty" value="{{ old('specialty', $medico->specialty ?? '') }}" required>
                            @error('specialty')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if (!isset($medico))
                            <div class="form-group">
                                <label for="password">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $medico->email ?? '') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ old('phone_number', $medico->phone_number ?? '') }}" required>
                            @error('phone_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if (!isset($medico))
                            <div class="form-group">
                                <label for="password_confirmation">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($medico) ? 'Actualizar' : 'Registrar' }}
                    </button>
                    <a href="{{ route('admin.medicos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Validación de teléfono (solo números y guiones)
            $('#phone_number').on('input', function() {
                this.value = this.value.replace(/[^0-9-]/g, '');
            });
        });
    </script>
@stop 