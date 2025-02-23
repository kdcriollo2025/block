@extends('adminlte::page')

@section('title', isset($medico) ? 'Editar Médico' : 'Nuevo Médico')

@section('content_header')
    <h1>{{ isset($medico) ? 'Editar Médico' : 'Nuevo Médico' }}</h1>
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

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($medico) ? route('admin.medicos.update', $medico->id) : route('admin.medicos.store') }}" 
                  method="POST">
                @csrf
                @if(isset($medico))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $medico->user->name ?? '') }}"
                                   {{ isset($medico) ? 'disabled' : 'required' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $medico->user->email ?? '') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cedula">Cédula *</label>
                            <input type="text" 
                                   name="cedula" 
                                   id="cedula"
                                   class="form-control @error('cedula') is-invalid @enderror" 
                                   value="{{ old('cedula', $medico->cedula ?? '') }}" 
                                   {{ isset($medico) ? 'disabled' : 'required' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="specialty">Especialidad *</label>
                            <input type="text" 
                                   name="specialty" 
                                   id="specialty"
                                   class="form-control @error('specialty') is-invalid @enderror" 
                                   value="{{ old('specialty', $medico->specialty ?? '') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Teléfono *</label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $medico->phone ?? '') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                @if(!isset($medico))
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña *</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation"
                                   class="form-control" 
                                   required>
                        </div>
                    </div>
                </div>
                @else
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cambiar Contraseña</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Nueva Contraseña</label>
                                            <input type="password" 
                                                   name="password" 
                                                   id="password"
                                                   class="form-control @error('password') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                            <input type="password" 
                                                   name="password_confirmation" 
                                                   id="password_confirmation"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($medico) ? 'Actualizar' : 'Crear' }}
                    </button>
                    <a href="{{ route('admin.medicos.index') }}" class="btn btn-secondary">
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
<script>
    // Validación de cédula ecuatoriana
    document.getElementById('cedula').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        e.target.value = value;
    });

    // Validación de teléfono
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        e.target.value = value;
    });
</script>
@stop 