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
                                            <div class="qr-container bg-light p-4 rounded-3 mb-3">
                                                {!! QrCode::size(200)->generate(json_encode([
                                                    'type' => 'Medical History NFT',
                                                    'patient' => $history->patient->name,
                                                    'doctor' => Auth::user()->name,
                                                    'current_hash' => $history->hash,
                                                    'hash_version' => count(explode('|', $history->hash)),
                                                    'timestamp' => $history->updated_at->format('Y-m-d H:i:s'),
                                                    'created_at' => $history->created_at->format('Y-m-d H:i:s'),
                                                    'status' => $history->changes()->exists() ? 'modified' : 'valid',
                                                    'message' => $history->changes()->exists() ? 'Información ha sido alterada' : 'Información válida sin alteraciones'
                                                ])) !!}
                                            </div>
                                            <div class="nft-details text-start">
                                                <p class="mb-1"><strong>Médico:</strong> {{ Auth::user()->name }}</p>
                                                <p class="mb-1"><strong>Última actualización:</strong> {{ $history->updated_at->format('d/m/Y H:i:s') }}</p>
                                                <p class="mb-1">
                                                    <strong>Estado de la información:</strong>
                                                    <span class="badge bg-{{ $history->changes()->exists() ? 'warning' : 'success' }}">
                                                        {{ $history->changes()->exists() ? 'Información ha sido alterada' : 'Información válida sin alteraciones' }}
                                                    </span>
                                                </p>
                                                @if($history->changes()->exists())
                                                    <div class="mt-2 small">
                                                        <p class="mb-1"><strong>Resumen de cambios:</strong></p>
                                                        @php
                                                            $changeTypes = $history->changes()
                                                                ->selectRaw('change_type, count(*) as total')
                                                                ->groupBy('change_type')
                                                                ->pluck('total', 'change_type')
                                                                ->toArray();
                                                        @endphp
                                                        <ul class="list-unstyled">
                                                            <li>Agregados: {{ $changeTypes['added'] ?? 0 }}</li>
                                                            <li>Modificados: {{ $changeTypes['modified'] ?? 0 }}</li>
                                                            <li>Eliminados: {{ $changeTypes['deleted'] ?? 0 }}</li>
                                                        </ul>
                                                        @if($lastChange = $history->changes()->latest()->first())
                                                            <p class="mb-0">
                                                                <small>Último cambio: {{ $lastChange->created_at->format('d/m/Y H:i:s') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
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
    });
</script>
@stop

@php
use Illuminate\Support\Facades\Schema;
@endphp
