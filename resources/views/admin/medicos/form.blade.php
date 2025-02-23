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
                            <label for="name">Nombre</label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $medico->name ?? '') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $medico->email ?? '') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="especialidad">Especialidad</label>
                            <input type="text" 
                                   name="especialidad" 
                                   id="especialidad"
                                   class="form-control @error('especialidad') is-invalid @enderror" 
                                   value="{{ old('especialidad', $medico->especialidad ?? '') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" 
                                   name="telefono" 
                                   id="telefono"
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   value="{{ old('telefono', $medico->telefono ?? '') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
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