@extends('adminlte::page')

@section('title', 'Nuevo Paciente')

@section('content_header')
    <h1>Crear Paciente</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('medico.patients.store') }}" method="POST" id="patientForm">
            @csrf
            
            <div class="form-group">
                <label for="name">Nombre Completo *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="cedula">Cédula *</label>
                <input type="text" name="cedula" id="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                       maxlength="10" required>
                <div class="invalid-feedback" id="cedulaError"></div>
                @error('cedula')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- ... resto del formulario ... -->

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('js/cedula-validator.js') }}"></script>
<script>
document.getElementById('patientForm').addEventListener('submit', function(e) {
    const cedula = document.getElementById('cedula').value;
    if (!validarCedula(cedula)) {
        e.preventDefault();
        document.getElementById('cedulaError').textContent = 'La cédula ingresada no es válida';
        document.getElementById('cedula').classList.add('is-invalid');
    }
});

document.getElementById('cedula').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
    this.classList.remove('is-invalid');
    document.getElementById('cedulaError').textContent = '';
});
</script>
@stop 