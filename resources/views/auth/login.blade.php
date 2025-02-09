@extends('adminlte::auth.login')

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('password.request') }}">
            {{ __('Olvidé mi contraseña') }}
        </a>
    </p>
@stop

<form method="POST" action="{{ route('login') }}">
    @csrf
    <!-- resto del formulario -->
</form>