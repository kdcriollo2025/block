@extends('adminlte::page')

@section('title', 'Registrar Nuevo Médico')

@section('content_header')
    <h1>Registrar Nuevo Médico</h1>
@stop

@section('content')
    @include('admin.medicos.form')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Validación de teléfono (solo números y guiones)
            $('#telefono').on('input', function() {
                this.value = this.value.replace(/[^0-9-]/g, '');
            });
        });
    </script>
@stop 