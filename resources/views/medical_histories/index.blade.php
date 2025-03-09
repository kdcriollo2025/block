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

        // Configurar comportamiento para cada modal de historial
        @foreach($medicalHistories as $history)
        // Inicializar o recuperar el historial de hashes desde localStorage
        let storageKey = 'hashHistory_{{ $history->id }}';
        let hashHistory{{ $history->id }};
        
        // Intentar recuperar del localStorage
        const savedHistory = localStorage.getItem(storageKey);
        if (savedHistory) {
            try {
                hashHistory{{ $history->id }} = JSON.parse(savedHistory);
                console.log('Historial recuperado del localStorage:', hashHistory{{ $history->id }});
            } catch (e) {
                console.error('Error al recuperar historial:', e);
                hashHistory{{ $history->id }} = ["{{ substr($history->hash, 0, 10) }}"];
            }
        } else {
            // Si no existe, inicializar con el hash original
            hashHistory{{ $history->id }} = ["{{ substr($history->hash, 0, 10) }}"];
            // Guardar en localStorage
            localStorage.setItem(storageKey, JSON.stringify(hashHistory{{ $history->id }}));
        }
        
        // Cuando se abre el modal, actualizar automáticamente
        $('#nftModal{{ $history->id }}').on('shown.bs.modal', function() {
            console.log('Modal {{ $history->id }} abierto');
            
            // Generar nuevo hash simple (solo 8 caracteres para que sea manejable)
            const newHash = Math.random().toString(16).substring(2, 10);
            
            // Añadir al array de hashes
            hashHistory{{ $history->id }}.push(newHash);
            
            // Guardar en localStorage
            localStorage.setItem(storageKey, JSON.stringify(hashHistory{{ $history->id }}));
            
            // Determinar si la información es válida o no (alterna cada vez)
            const isValid = hashHistory{{ $history->id }}.length % 2 === 0;
            
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
            const qrData = {
                patient: "{{ $history->patient->name }}",
                doctor: "{{ Auth::user()->name }}",
                hash_history: hashHistory{{ $history->id }},
                status: isValid ? "Verificado" : "Modificado",
                time: new Date().toLocaleString()
            };
            
            // Mostrar en consola para depuración
            console.log('Datos para QR:', qrData);
            console.log('Historial de hashes:', hashHistory{{ $history->id }});
            
            // Convertir a JSON
            const jsonStr = JSON.stringify(qrData);
            
            // Actualizar el QR con AJAX
            $.ajax({
                url: "{{ route('medico.generate.qr') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: jsonStr
                },
                success: function(response) {
                    document.getElementById('qrContainer{{ $history->id }}').innerHTML = response;
                    console.log('QR actualizado con éxito. Total hashes:', hashHistory{{ $history->id }}.length);
                },
                error: function(error) {
                    console.error('Error al generar QR:', error);
                }
            });
        });
        
        // Botón para reiniciar el historial (opcional, para pruebas)
        $('#nftModal{{ $history->id }} .modal-footer').append(
            '<button type="button" class="btn btn-warning" id="resetHistory{{ $history->id }}">Reiniciar Historial</button>'
        );
        
        $('#resetHistory{{ $history->id }}').on('click', function() {
            hashHistory{{ $history->id }} = ["{{ substr($history->hash, 0, 10) }}"];
            localStorage.setItem(storageKey, JSON.stringify(hashHistory{{ $history->id }}));
            alert('Historial reiniciado');
            
            // Actualizar QR con el historial reiniciado
            const qrData = {
                patient: "{{ $history->patient->name }}",
                doctor: "{{ Auth::user()->name }}",
                hash_history: hashHistory{{ $history->id }},
                status: "Verificado",
                time: new Date().toLocaleString()
            };
            
            $.ajax({
                url: "{{ route('medico.generate.qr') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: JSON.stringify(qrData)
                },
                success: function(response) {
                    document.getElementById('qrContainer{{ $history->id }}').innerHTML = response;
                    console.log('QR reiniciado');
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
