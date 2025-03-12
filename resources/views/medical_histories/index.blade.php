@extends('adminlte::page')

@section('title', 'Historial Médico')

@section('content_header')
    <h1>Historial Médico</h1>
@stop

@section('content')
    <a href="{{ route('medico.medical_histories.create') }}" class="btn btn-primary btn-sm mb-3">Nuevo</a>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicalHistories as $history)
                <tr>
                    <td>{{ $history->patient->name }}</td>
                    <td>{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('medico.medical_histories.show', $history) }}" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Ver Historial
                            </a>
                            <button type="button" 
                                    class="btn btn-primary btn-sm" 
                                    data-toggle="modal" 
                                    data-target="#nftModal{{ $history->id }}">
                                <i class="fas fa-certificate"></i> NFT
                            </button>
                        </div>

                        <!-- Modal NFT -->
                        <div class="modal fade" id="nftModal{{ $history->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">
                                            <i class="fas fa-certificate"></i> NFT del Historial Médico
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="nft-card p-4 mb-3 border rounded shadow-sm">
                                            <h6 class="text-primary">Certificado Digital NFT</h6>
                                            <div class="patient-info mb-3">
                                                <h5>{{ $history->patient->name }}</h5>
                                                <small class="text-muted">ID: {{ $history->id }}</small>
                                            </div>
                                            <div class="qr-container bg-light p-4 rounded-3 mb-3 cursor-pointer" id="qrContainer{{ $history->id }}" style="cursor: pointer;">
                                                <!-- QR inicial con estructura simplificada -->
                                                <img src="{{ url('/qr-generator') }}?data={{ urlencode(json_encode([
                                                    'patient' => $history->patient->name,
                                                    'doctor' => Auth::user()->name,
                                                    'hash_history' => [substr($history->hash, 0, 10)],
                                                    'status' => 'Verificado',
                                                    'time' => now()->format('Y-m-d H:i:s'),
                                                    'count' => 1
                                                ])) }}" width="200" height="200" alt="QR Code" />
                                            </div>
                                            <div class="nft-details text-start">
                                                <p class="mb-1"><strong>Médico:</strong> {{ Auth::user()->name }}</p>
                                                <p class="mb-1"><strong>Última actualización:</strong> <span id="lastUpdate{{ $history->id }}">{{ $history->updated_at->format('d/m/Y H:i:s') }}</span></p>
                                                <p class="mb-1">
                                                    <strong>Estado de la información:</strong>
                                                    <span class="badge bg-success" id="statusBadge{{ $history->id }}">
                                                        Certificado verificado correctamente
                                                    </span>
                                                </p>
                                                <div class="mt-3">
                                                    <button type="button" class="btn btn-sm btn-primary" id="addHashBtn{{ $history->id }}">
                                                        <i class="fas fa-plus-circle"></i> Agregar nuevo hash
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('css')
<style>
    .nft-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    }
    .qr-container {
        display: inline-block;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .qr-container:hover {
        transform: scale(1.02);
        cursor: pointer;
    }
    .patient-info {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .nft-details {
        font-size: 0.9em;
    }
    .bg-success {
        background-color: #28a745!important;
        color: white;
    }
    .bg-danger {
        background-color: #dc3545!important;
        color: white;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "order": [[1, "desc"]]
        });

        @foreach($medicalHistories as $history)
        // Inicializar variables para el QR
        let patientName{{ $history->id }} = "{{ $history->patient->name }}";
        let doctorName{{ $history->id }} = "{{ Auth::user()->name }}";
        let initialHash{{ $history->id }} = "{{ substr($history->hash, 0, 10) }}";
        let hashCount{{ $history->id }} = 1;
        
        // Función simple para generar un nuevo QR
        function regenerateQR{{ $history->id }}() {
            // Generar un nuevo hash aleatorio
            let newHash = Math.random().toString(16).substring(2, 12);
            hashCount{{ $history->id }}++;
            
            // Crear datos para el QR
            let qrData = {
                patient: patientName{{ $history->id }},
                doctor: doctorName{{ $history->id }},
                hash_history: [initialHash{{ $history->id }}, newHash],
                status: "Verificado",
                time: new Date().toLocaleString(),
                count: hashCount{{ $history->id }}
            };
            
            // Convertir a JSON
            let jsonData = JSON.stringify(qrData);
            
            // Actualizar la fecha
            document.getElementById('lastUpdate{{ $history->id }}').textContent = new Date().toLocaleString();
            
            // Crear la URL para el QR
            let qrUrl = "{{ url('/qr-generator') }}?data=" + encodeURIComponent(jsonData);
            
            // Actualizar la imagen del QR
            let qrContainer = document.getElementById('qrContainer{{ $history->id }}');
            qrContainer.innerHTML = '<img src="' + qrUrl + '" width="200" height="200" alt="QR Code" />';
            
            // Mostrar alerta
            alert('Nuevo hash agregado: ' + newHash + '\nTotal de hashes: ' + hashCount{{ $history->id }});
        }
        
        // Asignar la función al botón
        document.getElementById('addHashBtn{{ $history->id }}').addEventListener('click', function() {
            regenerateQR{{ $history->id }}();
        });
        
        // Asignar la función al QR
        document.getElementById('qrContainer{{ $history->id }}').addEventListener('click', function() {
            regenerateQR{{ $history->id }}();
        });
        
        @endforeach
    });
</script>
@stop

@php
use Illuminate\Support\Facades\Schema;
@endphp
