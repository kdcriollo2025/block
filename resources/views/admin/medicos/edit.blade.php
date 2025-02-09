<div class="form-group">
    <label for="cedula">Cédula *</label>
    <input type="text" name="cedula" id="cedula" 
           class="form-control @error('cedula') is-invalid @enderror"
           value="{{ old('cedula', $medico->cedula) }}" 
           maxlength="10" required>
    <div class="invalid-feedback" id="cedulaError"></div>
    @error('cedula')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div> 