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
                                                {!! QrCode::size(200)->generate(json_encode([
                                                    'patient' => $history->patient->name,
                                                    'doctor' => Auth::user()->name,
                                                    'hash_history' => [substr($history->hash, 0, 10)],
                                                    'status' => 'Verificado',
                                                    'time' => now()->format('Y-m-d H:i:s')
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
                                                <!-- Visualización de la cadena de hashes -->
                                                <div class="mt-3">
                                                    <p><strong>Cadena de hashes:</strong> <small class="text-muted">(Haz clic en el QR para agregar un nuevo hash)</small></p>
                                                    <div class="hash-chain-container border rounded p-2 bg-light" style="max-height: 150px; overflow-y: auto;">
                                                        <div id="hashChain{{ $history->id }}" class="hash-chain">
                                                            <!-- Los hashes se agregarán dinámicamente aquí -->
                                                            <div class="hash-block mb-1 p-1 border-bottom">
                                                                <span class="badge bg-info">Bloque #1</span>
                                                                <span class="hash-value">{{ substr($history->hash, 0, 10) }}</span>
                                                                <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i:s') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
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
    .hash-chain-container {
        font-family: monospace;
        font-size: 0.85em;
    }
    .hash-block {
        display: flex;
        align-items: center;
        transition: background-color 0.3s ease;
    }
    .hash-block:hover {
        background-color: #f0f0f0;
    }
    .hash-value {
        font-weight: bold;
        color: #0056b3;
    }
    .me-2 {
        margin-right: 0.5rem;
    }
    .mx-2 {
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }
    .ms-2 {
        margin-left: 0.5rem;
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
        // Inicializar array de hashes con el hash original
        const hashHistory{{ $history->id }} = [
            "{{ substr($history->hash, 0, 10) }}"  // Hash original
        ];

        // Función para actualizar el QR
        function updateQR{{ $history->id }}() {
            // Determinar si el estado es válido (en un blockchain real, esto sería una verificación de la cadena)
            const isValid = hashHistory{{ $history->id }}.length % 2 === 1; // Simplemente alternamos para la simulación
            
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
            
            const qrData = {
                patient: "{{ $history->patient->name }}",
                doctor: "{{ Auth::user()->name }}",
                hash_history: hashHistory{{ $history->id }},
                status: isValid ? "Verificado" : "Modificado",
                time: new Date().toLocaleString()
            };
            
            console.log('Hashes en la cadena:', hashHistory{{ $history->id }});
            
            $.ajax({
                url: "{{ route('medico.generate.qr') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: JSON.stringify(qrData)
                },
                success: function(response) {
                    document.getElementById('qrContainer{{ $history->id }}').innerHTML = response;
                }
            });
        }

        // Función para actualizar la visualización de la cadena de hashes
        function updateHashChainDisplay{{ $history->id }}() {
            const hashChainContainer = document.getElementById('hashChain{{ $history->id }}');
            
            // Limpiar el contenedor
            hashChainContainer.innerHTML = '';
            
            // Agregar cada hash a la visualización
            hashHistory{{ $history->id }}.forEach((hash, index) => {
                const hashBlock = document.createElement('div');
                hashBlock.className = 'hash-block mb-1 p-1 border-bottom';
                
                const blockNumber = document.createElement('span');
                blockNumber.className = 'badge bg-info me-2';
                blockNumber.textContent = `Bloque #${index + 1}`;
                
                const hashValue = document.createElement('span');
                hashValue.className = 'hash-value mx-2';
                hashValue.textContent = hash;
                
                const timestamp = document.createElement('small');
                timestamp.className = 'text-muted ms-2';
                timestamp.textContent = index === 0 ? 
                    '{{ $history->created_at->format("d/m/Y H:i:s") }}' : 
                    new Date().toLocaleString();
                
                hashBlock.appendChild(blockNumber);
                hashBlock.appendChild(hashValue);
                hashBlock.appendChild(timestamp);
                
                hashChainContainer.appendChild(hashBlock);
            });
            
            // Hacer scroll al último hash
            hashChainContainer.scrollTop = hashChainContainer.scrollHeight;
        }

        // Hacer el QR clickeable
        $('#qrContainer{{ $history->id }}').on('click', function() {
            // Llamar al endpoint para agregar un nuevo hash a la cadena
            $.ajax({
                url: "{{ route('medico.add.hash') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    medical_history_id: {{ $history->id }},
                    previous_hashes: hashHistory{{ $history->id }}
                },
                success: function(response) {
                    if (response.success) {
                        // Agregar el nuevo hash a la cadena
                        hashHistory{{ $history->id }}.push(response.new_hash);
                        
                        // Actualizar el QR con la nueva cadena de hashes
                        updateQR{{ $history->id }}();
                        
                        // Actualizar la visualización de la cadena de hashes
                        updateHashChainDisplay{{ $history->id }}();
                        
                        console.log('Nuevo hash agregado:', response.new_hash);
                        console.log('Timestamp:', response.timestamp);
                    } else {
                        console.error('Error al agregar hash:', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error en la petición:', xhr.responseText);
                }
            });
        });

        // Cuando se abre el modal, mostrar el estado inicial
        $('#nftModal{{ $history->id }}').on('show.bs.modal', function() {
            // Actualizar el QR con los hashes actuales
            updateQR{{ $history->id }}();
            
            // Actualizar la visualización de la cadena de hashes
            updateHashChainDisplay{{ $history->id }}();
        });
        @endforeach
    });
</script>
@stop

@php
use Illuminate\Support\Facades\Schema;
@endphp
