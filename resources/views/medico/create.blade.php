<form method="POST" action="{{ route('medico.store') }}">
    @csrf
    
    <div class="form-group">
        <label for="nombre_completo">Nombre Completo *</label>
        <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="correo_electronico">Correo Electrónico *</label>
        <input type="email" name="correo_electronico" id="correo_electronico" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="especialidad">Especialidad *</label>
        <input type="text" name="especialidad" id="especialidad" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono *</label>
        <input type="text" name="telefono" id="telefono" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="contrasena">Contraseña *</label>
        <input type="password" name="contrasena" id="contrasena" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="contrasena_confirmation">Confirmar Contraseña *</label>
        <input type="password" name="contrasena_confirmation" id="contrasena_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Registrar</button>
    <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
</form> 