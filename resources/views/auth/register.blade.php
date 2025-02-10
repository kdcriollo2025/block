@extends('adminlte::auth.register')

@section('auth_body')
    <form action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Nombre completo" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Cedula field --}}
        <div class="input-group mb-3">
            <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror"
                   value="{{ old('cedula') }}" placeholder="Cédula" maxlength="10">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card"></span>
                </div>
            </div>

            @error('cedula')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Correo electrónico">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Type field --}}
        <div class="input-group mb-3">
            <select name="type" class="form-control @error('type') is-invalid @enderror">
                <option value="">Seleccione el tipo de usuario</option>
                <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="medico" {{ old('type') == 'medico' ? 'selected' : '' }}>Médico</option>
            </select>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user-shield"></span>
                </div>
            </div>

            @error('type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Contraseña">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Confirmar contraseña">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-primary btn-block">
            <span class="fas fa-user-plus"></span>
            Registrar
        </button>

    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}">
            Ya tengo una cuenta
        </a>
    </p>
@stop