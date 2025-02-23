<div class="row">
    <div class="col-12">
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

                <form action="{{ route('admin.medicos.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedula">Cédula *</label>
                                <input type="text" class="form-control @error('cedula') is-invalid @enderror" 
                                    id="cedula" name="cedula" value="{{ old('cedula') }}" required>
                                @error('cedula')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="especialidad">Especialidad *</label>
                                <input type="text" class="form-control @error('especialidad') is-invalid @enderror" 
                                    id="especialidad" name="especialidad" value="{{ old('especialidad') }}" required>
                                @error('especialidad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono *</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                    id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                                @error('telefono')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Resto de los campos... -->

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <a href="{{ route('admin.medicos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 