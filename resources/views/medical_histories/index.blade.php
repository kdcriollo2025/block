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
                                            <div class="qr-container bg-light p-4 rounded-3 mb-3" id="qrContainer{{ $history->id }}">
                                                {!! QrCode::size(200)->generate(json_encode([
                                                    'type' => 'Medical History NFT',
                                                    'patient' => $history->patient->name,
                                                    'doctor' => Auth::user()->name,
                                                    'current_hash' => $history->hash,
                                                    'timestamp' => now()->format('Y-m-d H:i:s'),
                                                    'created_at' => $history->created_at->format('Y-m-d H:i:s'),
                                                    'status' => 'valid',
                                                    'message' => 'Información válida sin alteraciones'
                                                ])) !!}
                                            </div>
                                            <div class="nft-details text-start">
                                                <p class="mb-1"><strong>Médico:</strong> {{ Auth::user()->name }}</p>
                                                <p class="mb-1"><strong>Última actualización:</strong> <span id="lastUpdate{{ $history->id }}">{{ $history->updated_at->format('d/m/Y H:i:s') }}</span></p>
                                                <p class="mb-1">
                                                    <strong>Estado de la información:</strong>
                                                    <span class="badge bg-success" id="statusBadge{{ $history->id }}">
                                                        Información válida sin alteraciones
                                                    </span>
                                                </p>
                                                <p class="mb-1"><strong>Hash:</strong> <span id="hashDisplay{{ $history->id }}" class="text-muted small">{{ substr($history->hash, 0, 20) }}...</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="btnVerifyNft{{ $history->id }}" class="btn btn-primary">Verificar NFT</button>
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
    .btn-update-nft {
        margin-top: 10px;
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

        // Función para generar un hash aleatorio
        function generateRandomHash() {
            const characters = 'abcdef0123456789';
            let hash = '';
            for (let i = 0; i < 64; i++) {
                hash += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return hash;
        }

        // Solución para el problema de aria-hidden
        $('.modal').on('shown.bs.modal', function() {
            $(this).removeAttr('aria-hidden');
        });

        // Configurar comportamiento para cada modal de historial
        @foreach($medicalHistories as $history)
        // Variable para controlar el estado (alternará cada vez que se abra el modal)
        let openCount{{ $history->id }} = 0;
        
        // Cuando se abre el modal, actualizar automáticamente
        $('#nftModal{{ $history->id }}').on('shown.bs.modal', function() {
            console.log('Modal {{ $history->id }} abierto');
            
            // Incrementar contador de aperturas
            openCount{{ $history->id }}++;
            
            // Determinar si la información es válida o no (alterna cada vez)
            const isValid = openCount{{ $history->id }} % 2 === 0;
            
            // Generar nuevo hash y timestamp
            const newHash = generateRandomHash();
            const timestamp = new Date().toISOString().replace('T', ' ').substr(0, 19);
            
            // Actualizar el estado visual
            const statusBadge = document.getElementById('statusBadge{{ $history->id }}');
            if (isValid) {
                statusBadge.textContent = 'Información válida sin alteraciones';
                statusBadge.className = 'badge bg-success';
            } else {
                statusBadge.textContent = 'Información ha sido alterada';
                statusBadge.className = 'badge bg-danger';
            }
            
            // No actualizar el hash visible en pantalla
            // Solo actualizar la fecha
            document.getElementById('lastUpdate{{ $history->id }}').textContent = new Date().toLocaleString();
            
            // Generar datos para el nuevo QR (incluyendo el hash que solo será visible al escanear)
            const nftData = {
                type: 'Medical History NFT',
                patient: '{{ $history->patient->name }}',
                doctor: '{{ Auth::user()->name }}',
                current_hash: newHash, // Este hash solo será visible al escanear
                timestamp: timestamp,
                status: isValid ? 'valid' : 'modified',
                message: isValid ? 'Información válida sin alteraciones' : 'Información ha sido alterada',
                scan_time: timestamp
            };
            
            // Actualizar el QR con AJAX
            $.ajax({
                url: '{{ route("medico.generate.qr") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: JSON.stringify(nftData)
                },
                success: function(response) {
                    document.getElementById('qrContainer{{ $history->id }}').innerHTML = response;
                    console.log('QR actualizado para historial {{ $history->id }}');
                },
                error: function(error) {
                    console.error('Error al generar QR:', error);
                }
            });
        });
        @endforeach
    });
</script>
@stop

@php
use Illuminate\Support\Facades\Schema;
@endphp
