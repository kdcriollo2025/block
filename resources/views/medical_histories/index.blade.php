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
                                    data-bs-toggle="modal" 
                                    data-bs-target="#nftModal{{ $history->id }}">
                                <i class="fas fa-certificate"></i> NFT
                            </button>
                        </div>

                        <!-- Modal NFT para cada historial -->
                        <div class="modal fade" id="nftModal{{ $history->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">NFT del Historial Médico</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="mb-3">
                                            <h6>Paciente: {{ $history->patient->name }}</h6>
                                            <p class="text-muted">ID: {{ $history->id }}</p>
                                            <p class="text-muted small">Hash: {{ $history->hash }}</p>
                                        </div>
                                        <div class="qr-code p-3 bg-light rounded">
                                            {!! QrCode::size(200)->generate(json_encode([
                                                'id' => $history->id,
                                                'patient' => $history->patient->name,
                                                'hash' => $history->hash,
                                                'created_at' => $history->created_at->format('Y-m-d H:i:s')
                                            ])) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <a href="{{ route('medico.medical_histories.download_pdf', $history) }}" 
                                           class="btn btn-primary">
                                            Descargar PDF con NFT
                                        </a>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
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
