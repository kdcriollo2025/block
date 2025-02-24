<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Médico - Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Dashboard Médico (Vista de Prueba)</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h4>Información del Usuario</h4>
                    <p><strong>Nombre:</strong> {{ $nombre }}</p>
                    <p><strong>Email:</strong> {{ $email }}</p>
                    <p><strong>Tipo:</strong> {{ $tipo }}</p>
                </div>

                <div class="mt-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Log para verificar que la página se carga
        console.log('Dashboard cargado correctamente');
        
        // Agregar token CSRF a todas las peticiones AJAX
        document.addEventListener('DOMContentLoaded', function() {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.axios = {
                defaults: {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }
            };
        });
    </script>
</body>
</html> 