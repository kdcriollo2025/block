@extends('adminlte::page')

@section('title', 'Nuevo Médico')

@section('content_header')
    <h1>Registrar Nuevo Médico</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.medicos.store') }}" method="POST" id="medicoForm">
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

            <button type="submit" class="btn btn-primary" id="submitBtn">Guardar</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('js/cedula-validator.js') }}"></script>
<script>
document.getElementById('medicoForm').addEventListener('submit', function(e) {
    const cedula = document.getElementById('cedula').value;
    const cedulaError = document.getElementById('cedulaError');
    
    if (!validarCedula(cedula)) {
        e.preventDefault();
        cedulaError.textContent = 'La cédula ingresada no es válida';
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