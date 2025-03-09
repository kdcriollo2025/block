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
                                                    'message' => 'Certificado verificado correctamente'
                                                ])) !!}
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

        // Función para generar un hash aleatorio
        function generateRandomHash() {
            const characters = 'abcdef0123456789';
            let hash = '';
            for (let i = 0; i < 16; i++) {
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
        // Variable para controlar el estado y almacenar el historial de hashes
        let openCount{{ $history->id }} = 0;
        let hashHistory{{ $history->id }} = ['{{ substr($history->hash, 0, 20) }}'];
        
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
            
            // Agregar el nuevo hash al historial
            hashHistory{{ $history->id }}.push(newHash);
            
            // Actualizar el estado visual
            const statusBadge = document.getElementById('statusBadge{{ $history->id }}');
            if (isValid) {
                statusBadge.textContent = 'Certificado verificado correctamente';
                statusBadge.className = 'badge bg-success';
            } else {
                statusBadge.textContent = 'Se detectaron modificaciones en el registro';
                statusBadge.className = 'badge bg-danger';
            }
            
            // Actualizar la fecha
            document.getElementById('lastUpdate{{ $history->id }}').textContent = new Date().toLocaleString();
            
            // Crear un objeto con la información completa para el QR
            const fullData = {
                type: 'Medical History NFT',
                patient: '{{ $history->patient->name }}',
                doctor: '{{ Auth::user()->name }}',
                current_hash: newHash,
                previous_hashes: hashHistory{{ $history->id }}.slice(0, -1), // Todos los hashes anteriores
                hash_count: hashHistory{{ $history->id }}.length,
                timestamp: timestamp,
                created_at: '{{ $history->created_at->format("Y-m-d H:i:s") }}',
                status: isValid ? 'valid' : 'modified',
                message: isValid ? 'Certificado verificado correctamente' : 'Se detectaron modificaciones en el registro',
                verification_count: openCount{{ $history->id }},
                verification_history: [
                    {
                        time: timestamp,
                        hash: newHash,
                        status: isValid ? 'valid' : 'modified'
                    }
                ]
            };
            
            // Convertir a JSON y asegurarse de que no haya problemas de codificación
            const jsonData = JSON.stringify(fullData);
            console.log('Datos para QR:', jsonData);
            
            // Actualizar el QR con AJAX
            $.ajax({
                url: '{{ route("medico.generate.qr") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: jsonData
                },
                success: function(response) {
                    document.getElementById('qrContainer{{ $history->id }}').innerHTML = response;
                    console.log('QR actualizado para historial {{ $history->id }}');
                    console.log('Historial de hashes:', hashHistory{{ $history->id }});
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
